<?php
/**
 * Title: Posts recentes
 * Slug: diio-wp-theme/recent-posts
 * Categories: diio-wp-theme, query
 * Description: Lista compacta dos posts mais recentes do blog.
 *
 * @package diio-wp-theme
 */

$diio_blog_id  = (int) get_option( 'page_for_posts' );
$diio_blog_url = $diio_blog_id ? get_permalink( $diio_blog_id ) : home_url( '/' );
?>
<!-- wp:group {"tagName":"section","className":"section","layout":{"type":"default"}} -->
<section class="wp-block-group section">
	<!-- wp:group {"className":"section-header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group section-header">
		<!-- wp:heading {"className":"section-title"} -->
		<h2 class="wp-block-heading section-title"><?php esc_html_e( 'blog', 'diio-wp-theme' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"section-link"} -->
		<p class="section-link"><a href="<?php echo esc_url( $diio_blog_url ); ?>"><?php esc_html_e( 'cat ./blog →', 'diio-wp-theme' ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":12,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"post-list"} -->
	<div class="wp-block-query post-list">
		<!-- wp:post-template -->
			<!-- wp:group {"className":"post-row","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group post-row">
				<!-- wp:post-date {"format":"Y-m-d"} /-->
				<!-- wp:post-title {"level":3,"isLink":true} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Nenhum post por aqui ainda.', 'diio-wp-theme' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->
