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

interface Block {

	public function render( array $block ): void;
	public function register_block(): void;
	public function register_fields(): void;

}
