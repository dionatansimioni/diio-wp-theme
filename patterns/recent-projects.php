<?php
/**
 * Title: Projetos recentes
 * Slug: diio-wp-theme/recent-projects
 * Categories: diio-wp-theme, query
 * Description: Grade com os três projetos mais recentes do portfólio.
 *
 * @package diio-wp-theme
 */

$diio_projects_url = get_post_type_archive_link( 'project' );
?>
<!-- wp:group {"tagName":"section","align":"wide","className":"section","layout":{"type":"default"}} -->
<section class="wp-block-group alignwide section">
	<!-- wp:group {"className":"section-header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group section-header">
		<!-- wp:heading {"className":"section-title"} -->
		<h2 class="wp-block-heading section-title"><?php echo esc_html( diio_project_text( 'section_title' ) ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"section-link"} -->
		<p class="section-link"><a href="<?php echo esc_url( $diio_projects_url ? $diio_projects_url : home_url( '/projetos/' ) ); ?>"><?php echo esc_html( diio_project_text( 'section_link' ) ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:group {"className":"card","layout":{"type":"default"}} -->
			<div class="wp-block-group card">
				<!-- wp:diio/project-thumbnail /-->
				<!-- wp:post-title {"level":3,"isLink":true} /-->
				<!-- wp:diio/project-text /-->
				<!-- wp:diio/project-info {"variant":"compact"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Nenhum projeto publicado ainda. Adicione um em Projetos → Adicionar novo.', 'diio-wp-theme' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->
