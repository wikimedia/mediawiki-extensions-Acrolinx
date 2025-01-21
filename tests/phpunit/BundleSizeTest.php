<?php

namespace MediaWiki\Acrolinx\Tests;

class BundleSizeTest extends \MediaWiki\Tests\Structure\BundleSizeTestBase {

	/** @inheritDoc */
	public function getBundleSizeConfig(): string {
		return dirname( __DIR__, 2 ) . '/bundlesize.config.json';
	}
}
