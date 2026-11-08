<?php
/**
 * Title: Apresentação (hero)
 * Slug: diio-wp-theme/hero
 * Categories: diio-wp-theme, banner
 * Description: Apresentação estilo terminal com título, descrição e botões.
 *
 * @package diio-wp-theme
 */

$diio_projects_url = get_post_type_archive_link( 'project' );
?>
<!-- wp:group {"tagName":"section","className":"hero","layout":{"type":"default"}} -->
<section class="wp-block-group hero">
	<!-- wp:paragraph {"className":"terminal-line"} -->
	<p class="terminal-line"><span class="prompt">$</span>whoami</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"className":"hero-title"} -->
	<h1 class="wp-block-heading hero-title"><?php esc_html_e( 'Oi! Me chamo', 'diio-wp-theme' ); ?> <span class="gradient-text"><?php esc_html_e( 'Seu Nome', 'diio-wp-theme' ); ?></span><span class="cursor">_</span></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"hero-lead"} -->
	<p class="hero-lead"><?php esc_html_e( 'Escrevo sobre Linux, software livre e tecnologia. Aqui você encontra meus projetos, artigos e experimentos.', 'diio-wp-theme' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $diio_projects_url ? $diio_projects_url : home_url( '/projetos/' ) ); ?>"><?php esc_html_e( 'ver projetos →', 'diio-wp-theme' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#contato"><?php esc_html_e( 'contato', 'diio-wp-theme' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</section>
<!-- /wp:group -->
