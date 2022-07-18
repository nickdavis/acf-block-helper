<?php
/**
 * Placeholder Block.
 *
 * @url https://www.advancedcustomfields.com/resources/acf_register_block_type/#examples
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

$block = $this->block;

$block_id = 'block-placeholder-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

$className = 'block-placeholder';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

?>

<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="block-form">
		<h3><?= esc_html( $this->block['title'] ); ?> <?= isset( $args['title'] ) && ! empty( $this->args['title'] ) ? '<em>(' . esc_html( $this->args['title'] ) . ')</em>' : ''; ?></h3>
	</div>
</div>
