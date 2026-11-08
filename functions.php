<?php
/**
 * Funções do tema diio_WP_theme.
 *
 * @package diio-wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'inc/project-settings.php' );
require_once get_theme_file_path( 'inc/projects.php' );

/**
 * Recursos do tema e estilo do editor.
 */
function diio_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'diio_setup' );

/**
 * Carrega o style.css e as animações no front-end.
 */
function diio_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'diio-style', get_stylesheet_uri(), array(), $version );

	wp_enqueue_script(
		'diio-animations',
		get_theme_file_uri( 'assets/js/animations.js' ),
		array(),
		$version,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'diio_enqueue_assets' );

/**
 * Roda antes da renderização: marca o <html> com "diio-js" (para os
 * elementos animados ao rolar não piscarem) e aplica o modo claro se o
 * visitante o escolheu antes. Sem escolha salva, o tema fica escuro.
 */
function diio_js_class() {
	wp_print_inline_script_tag(
		"(function(d){d.classList.add('diio-js');try{if(localStorage.getItem('diio-theme')==='light'){d.setAttribute('data-theme','light');}}catch(e){}})(document.documentElement);"
	);
}
add_action( 'wp_head', 'diio_js_class', 1 );

/**
 * Pré-carrega as fontes usadas acima da dobra.
 */
function diio_preload_fonts() {
	$fonts = array(
		'assets/fonts/inter/inter-variable.woff2',
		'assets/fonts/jetbrains-mono/jetbrains-mono-variable.woff2',
	);
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( $font ) )
		);
	}
}
add_action( 'wp_head', 'diio_preload_fonts', 2 );

/**
 * Categoria de padrões e estilos de bloco.
 */
function diio_register_block_extras() {
	register_block_pattern_category(
		'diio-wp-theme',
		array( 'label' => __( 'diio_WP_theme', 'diio-wp-theme' ) )
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'terminal',
			'label' => __( 'Terminal', 'diio-wp-theme' ),
		)
	);
}
add_action( 'init', 'diio_register_block_extras' );
