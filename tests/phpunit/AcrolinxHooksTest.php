<?php

use MediaWiki\Config\HashConfig;

/**
 * Class AcrolinxHooksTest
 *
 * @group Database
 */
class AcrolinxHooksTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers AcrolinxHooks::enableAcrolinxForPage
	 */
	public function testEnableAcrolinxForPage() {
		$config = new HashConfig( [
			'AcrolinxNamespaces' => [ NS_MAIN ],
		] );
		$page = $this->getExistingTestPage( 'UTest1' );
		$this->assertTrue(
			AcrolinxHooks::enableAcrolinxForPage( $config, $page->getTitle() )
		);
		$config = new HashConfig( [
			'AcrolinxNamespaces' => [],
		] );
		$this->assertFalse(
			AcrolinxHooks::enableAcrolinxForPage( $config, $page->getTitle() )
		);
	}
}
