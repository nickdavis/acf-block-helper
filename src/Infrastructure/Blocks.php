<?php

namespace NickDavis\ACFBlockHelper\Infrastructure;

use NickDavis\ACFBlockHelper\Registerable;

final class Blocks implements Registerable {

	public function register(): void {
		add_action( 'after_setup_theme', [ $this, 'removeCorePatterns' ] );
		add_filter( 'block_categories_all', [ $this, 'register_category' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_admin_block_styles' ] );
	}

	public static function get_category_slug(): string {
		return defined( 'ACF_BLOCK_HELPER_CATEGORY_SLUG' )
			? ACF_BLOCK_HELPER_CATEGORY_SLUG
			: '';
	}

	public static function get_category_title(): string {
		return defined( 'ACF_BLOCK_HELPER_CATEGORY_TITLE' )
			? ACF_BLOCK_HELPER_CATEGORY_TITLE
			: '';
	}

	public static function get_icon(): string {
		return defined( 'ACF_BLOCK_HELPER_ICON' )
			? ACF_BLOCK_HELPER_ICON
			: '';
	}

	public static function get_prefix(): string {
		return defined( 'ACF_BLOCK_HELPER_PREFIX' )
			? ACF_BLOCK_HELPER_PREFIX
			: 'nd';
	}

	public function enqueue_admin_block_styles(): void {
		$css_path    = 'css/admin/blocks.css';
		$css_url     = ACF_BLOCK_HELPER_URL . '/' . $css_path;
		$css_version = filemtime( ACF_BLOCK_HELPER_DIR . $css_path );

		wp_enqueue_style( 'nd-admin-blocks', $css_url, array(), $css_version, 'all' );
	}

	/**
	 *
	 * @url https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
	 * @url https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 *
	 * @param $categories
	 *
	 * @return array
	 */
	public function register_category( $categories ): array {
		if (
			empty( self::get_category_slug() )
			&& empty( self::get_category_title() )
		) {
			return $categories;
		}

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => ACF_BLOCK_HELPER_CATEGORY_SLUG,
					'title' => ACF_BLOCK_HELPER_CATEGORY_TITLE,
				),
			)
		);
	}

	/**
	 * Remove 'Core' Block Patterns.
	 *
	 * @url https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/
	 */
	public function removeCorePatterns(): void {
		remove_theme_support( 'core-block-patterns' );
	}

}
