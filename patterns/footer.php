<?php
/**
 * Title: Rodapé
 * Slug: diio-wp-theme/footer
 * Categories: diio-wp-theme
 * Inserter: no
 *
 * @package diio-wp-theme
 */

?>
<!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:paragraph -->
	<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> · <?php esc_html_e( 'feito com WordPress', 'diio-wp-theme' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:social-links {"className":"is-style-logos-only"} -->
	<ul class="wp-block-social-links is-style-logos-only">
		<!-- wp:social-link {"url":"https://github.com/","service":"github"} /-->
		<!-- wp:social-link {"url":"https://youtube.com/","service":"youtube"} /-->
		<!-- wp:social-link {"url":"https://mastodon.social/","service":"mastodon"} /-->
		<!-- wp:social-link {"url":"#","service":"feed"} /-->
	</ul>
	<!-- /wp:social-links -->
</div>
<!-- /wp:group -->
