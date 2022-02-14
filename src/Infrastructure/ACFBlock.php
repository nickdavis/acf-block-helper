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

namespace NickDavis\ACFBlockHelper\Infrastructure;

use NickDavis\ACFBlockHelper\Registerable;
use BrightNucleus\Views;

abstract class ACFBlock implements Block, Registerable {

	public function register(): void {
		add_action( 'acf/init', [ $this, 'register_block' ] );
		add_action( 'acf/init', [ $this, 'register_fields' ] );
	}

	public function render( array $block ): void {
		$args = [ 'args' => $this->get_args(), 'block' => $block ];

		if ( is_admin() ) {
			$path = '/blocks/admin';

		} else {
			$path = '/blocks/' . str_replace( [ Blocks::get_prefix() . '_', '_' ], [ '', '-' ], $this->get_slug() );
		}

		echo Views::render($path, $args );
	}

	protected function get_args(): array {
		$args = [];

		$block_slug = str_replace( '-', '_', $this->get_slug() ) . '_';

		foreach ( $this->get_fields() as $field ) {
			if ( 'link' === $field['type'] ) {
				$args[ $field['key'] ] = get_field( $block_slug . $field['key'] ) ?: [];

				continue;
			}

			if ( 'image' === $field['type'] ) {
				$args[ $field['key'] ] = get_field( $block_slug . $field['key'] ) ?: 0;

				continue;
			}

			$args[ $field['key'] ] = get_field( $block_slug . $field['key'] ) ?: '';
		}

		return $args;
	}

	protected function get_fields(): array {
		return [];
	}

	protected function get_slug(): string {
		return '';
	}

	protected function get_title(): string {
		return '';
	}

	/**
	 * @param string $slug
	 * @param string $title
	 * @param array  $fields [ $key = '', $label = '', 'type' => '', 'width' => '' ]
	 */
	protected static function register_field_group( string $slug, string $title, array $fields ): void {
		$acf_fields = [];

		foreach ( $fields as $field ) {
			$acf_field_args = [
				'key'   => 'field_' . $slug . '_' . $field['key'],
				'label' => $field['label'],
				'name'  => $slug . '_' . $field['key'],
				'type'  => $field['type']
			];

			if ( 'image' === $field['type'] ) {
				$acf_field_args['return_format'] = 'id';
			}

			if ( 'post_object' === $field['type'] ) {
				$acf_field_args['post_type']     = $field['post_type'] ?: [];
				$acf_field_args['multiple']      = $field['multiple'] ?: 0;
				$acf_field_args['return_format'] = 'id';
			}

			if ( 'select' === $field['type'] && ! empty( $field['choices'] ) ) {
				$acf_field_args['choices'] = $field['choices'];
			}

			if ( 'select' === $field['type'] && ! empty( $field['default_value'] ) ) {
				$acf_field_args['default_value'] = $field['default_value'];
			}

			if ( 'wysiwyg' === $field['type'] ) {
				$acf_field_args['media_upload'] = 0;
				$acf_field_args['tabs']         = 'visual';
				$acf_field_args['toolbar']      = 'basic';
			}

			if ( ! empty( $field['conditional_logic'] ) ) {
				$acf_field_args['conditional_logic'] = $field['conditional_logic'];
			}

			if ( ! empty( $field['instructions'] ) ) {
				$acf_field_args['instructions'] = $field['instructions'];
			}

			if ( ! empty( $field['placeholder'] ) ) {
				$acf_field_args['placeholder'] = $field['placeholder'];
			}

			if ( ! empty( $field['width'] ) ) {
				$acf_field_args['wrapper']['width'] = $field['width'];
			}

			$acf_fields[] = $acf_field_args;
		}

		acf_add_local_field_group( array(
			'key'      => 'group_' . $slug,
			'title'    => $title,
			'fields'   => $acf_fields,
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/' . str_replace( '_', '-', $slug ),
					),
				),
			),
		) );
	}

}
