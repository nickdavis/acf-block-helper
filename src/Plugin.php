<?php declare( strict_types=1 );

/**
 * ACF Block Helper.
 *
 * @package   NickDavis\ACFBlockHelper
 * @author    Nick Davis <inbox@nickdavis.net>
 * @license   MIT
 * @link      https://nickdavis.net
 * @copyright 2022 Nick Davis
 */

namespace NickDavis\ACFBlockHelper;

use BrightNucleus\Views;
use BrightNucleus\View\Location\FilesystemLocation;
use NickDavis\ACFBlockHelper\Infrastructure\Blocks;
use NickDavis\ACFBlockHelper\Infrastructure\Facets\CountFacet;
use NickDavis\ACFBlockHelper\Infrastructure\Facets\MapFacet;
use NickDavis\ACFBlockHelper\Infrastructure\Facets\ProximityFacet;
use NickDavis\ACFBlockHelper\Infrastructure\FacetWP;

final class Plugin {
	public function run(): void {
		foreach ( $this->services as $class ) {
			/** @var Registerable $service */
			$service = new $class;
			$service->register();
		}

		$this->register_views();
	}

	public function register_views(): void {
		$folders = [
			get_stylesheet_directory() . '/partials',
			get_template_directory() . '/partials',
			ACF_BLOCK_HELPER_DIR . 'views',
		];

		foreach ( $folders as $folder ) {
			Views::addLocation( new FilesystemLocation( $folder ) );
		}
	}

	protected array $services = [
		// Infrastructure
		Blocks::class,
	];
}
