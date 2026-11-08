<?php
/**
 * Title: Voltar para os projetos
 * Slug: diio-wp-theme/project-back-link
 * Categories: diio-wp-theme
 * Inserter: no
 *
 * @package diio-wp-theme
 */

$diio_projects_url = get_post_type_archive_link( 'project' );
?>
<!-- wp:paragraph {"className":"terminal-line"} -->
<p class="terminal-line"><span class="prompt">$</span><a href="<?php echo esc_url( $diio_projects_url ? $diio_projects_url : home_url( '/projetos/' ) ); ?>"><?php esc_html_e( 'cd ../projetos', 'diio-wp-theme' ); ?></a></p>
<!-- /wp:paragraph -->
