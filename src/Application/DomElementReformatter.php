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

namespace NickDavis\ACFBlockHelper\Application;

use DOMDocument;

final class DomElementReformatter {

	/**
	 * @param string $content The string being passed as a DOMDocument.
	 * @param array  $elements In a [ 'html_tag' => 'class' ] format.
	 * @return string
	 *
	 * @url https://stackoverflow.com/a/22490902
	 */
	public function add_class_to_elements( string $content, array $elements ): string {
		if ( empty( $content ) ) {
			return '';
		}

		$dom = new DOMDocument();
		/**
		 * Suppress warnings.
		 * @url https://stackoverflow.com/a/6090728
		 */
		libxml_use_internal_errors(true);
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		/**
		 * Suppress warnings.
		 * @url https://stackoverflow.com/a/6090728
		 */
		libxml_clear_errors();

		foreach ( $elements as $tag => $class ) {
			$tags = $dom->getElementsByTagName( $tag );

			foreach ( $tags as $tag ) {
				if ( $tag->hasAttribute( 'class' ) ) {
					$class = $tag->getAttribute( 'class' ) . ' ' . $class;
				}

				$tag->setAttribute( 'class', $class );
			}
		}

		return $dom->saveHTML();
	}

}
