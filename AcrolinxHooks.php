<?php
/**
 * Static functions called by various outside hooks, as well as by
 * extension.json.
 *
 * @author Yaron Koren
 * @file
 * @ingroup Acrolinx
 */

use MediaWiki\Config\Config;
use MediaWiki\EditPage\EditPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Title\Title;

// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

class AcrolinxHooks implements
	\MediaWiki\Hook\BeforePageDisplayHook,
	\MediaWiki\Hook\EditPage__showEditForm_initialHook,
	\MediaWiki\Hook\MakeGlobalVariablesScriptHook
{

	/**
	 * @param Config $config
	 * @param Title $title
	 *
	 * @return bool
	 */
	public static function enableAcrolinxForPage( Config $config, $title ) {
		return in_array( $title->getNamespace(), $config->get( 'AcrolinxNamespaces' ) );
	}

	/**
	 * @param string[] &$vars
	 * @param OutputPage $out
	 *
	 * @return void
	 */
	public function onMakeGlobalVariablesScript( &$vars, $out ): void {
		$context = $out->getContext();
		$config = $context->getConfig();
		$mwUserLanguage = $context->getLanguage()->getCode();

		$vars['wgAcrolinxServerAddress'] = $config->get( 'AcrolinxServerAddress' );
		$vars['wgAcrolinxClientSignature'] = $config->get( 'AcrolinxClientSignature' );
		$vars['wgAcrolinxPageLocationID'] = $config->get( 'AcrolinxPageLocationID' );

		// More processing may be needed here, to convert from
		// MediaWiki language codes to Acrolinx ones...
		$vars['wgAcrolinxPageLanguage'] = $config->get( MainConfigNames::LanguageCode );
		$vars['wgAcrolinxUserLanguage'] = $mwUserLanguage;
	}

	/**
	 * @param EditPage $editPage
	 * @param OutputPage $output
	 *
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEditPage__showEditForm_initial( $editPage, $output ) {
		$title = $editPage->getTitle();
		if ( self::enableAcrolinxForPage( $output->getConfig(), $title ) ) {
			$output->addModules( 'ext.acrolinx' );
		}
	}

	/**
	 * @param string[] &$otherModules
	 *
	 * @return bool
	 */
	public static function addToFormEditPage( &$otherModules ) {
		// We'll just enable Acrolinx for all forms, for now.
		$otherModules[] = 'ext.acrolinx';
		return true;
	}

	/**
	 * Adds extension modules for Visual Editor mode
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		// TODO: perhaps find a way to detect VE/Forms more precisely to
		// avoid loading the library code on regular pages

		/*$isEditOrForm = in_array(
			$out->getRequest()->getVal('action'),
			[ 'edit', 'formedit' ]
		);
		$isVe = $out->getRequest()->getVal('veaction') === 'edit';*/
		$isEnabled = self::enableAcrolinxForPage( $out->getConfig(), $out->getTitle() );

		// phpcs:ignore MediaWiki.WhiteSpace.SpaceBeforeSingleLineComment.NewLineComment
		if ( !$isEnabled /*|| !( $isEditOrForm || $isVe )*/ ) {
			return;
		}

		$out->addModules( 'ext.acrolinx' );
	}

}
