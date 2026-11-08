<?php
/**
 * Title: Contato (terminal)
 * Slug: diio-wp-theme/contact
 * Categories: diio-wp-theme, call-to-action
 * Description: Bloco de contato em uma janela de terminal.
 *
 * @package diio-wp-theme
 */

?>
<!-- wp:group {"tagName":"section","anchor":"contato","className":"section","layout":{"type":"default"}} -->
<section id="contato" class="wp-block-group section">
	<!-- wp:heading {"className":"section-title"} -->
	<h2 class="wp-block-heading section-title"><?php esc_html_e( 'contato', 'diio-wp-theme' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:group {"className":"is-style-terminal","layout":{"type":"default"}} -->
	<div class="wp-block-group is-style-terminal">
		<!-- wp:paragraph {"className":"terminal-line"} -->
		<p class="terminal-line"><span class="prompt">$</span>cat contato.txt</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'e-mail:', 'diio-wp-theme' ); ?> <a href="mailto:voce@exemplo.com">voce@exemplo.com</a><br><?php esc_html_e( 'github:', 'diio-wp-theme' ); ?> <a href="https://github.com/">github.com/seu-usuario</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
