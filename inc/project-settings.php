<?php
/**
 * Tela "Projetos → Configurações": nomes dos campos, status e botões.
 *
 * @package diio-wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Textos configuráveis, agrupados por seção da tela: chave => [rótulo, padrão].
 *
 * @return array<string, array<string, array{0: string, 1: string}>>
 */
function diio_project_text_groups() {
	return array(
		__( 'Seção de projetos', 'diio-wp-theme' ) => array(
			'section_title' => array( __( 'Título da seção', 'diio-wp-theme' ), __( 'projetos', 'diio-wp-theme' ) ),
			'section_link'  => array( __( 'Link "ver todos" na página inicial', 'diio-wp-theme' ), __( 'ls ./projetos →', 'diio-wp-theme' ) ),
		),
		__( 'Nomes dos campos', 'diio-wp-theme' )  => array(
			'label_status' => array( __( 'Campo de status', 'diio-wp-theme' ), __( 'Status', 'diio-wp-theme' ) ),
			'label_year'   => array( __( 'Campo de ano', 'diio-wp-theme' ), __( 'Ano', 'diio-wp-theme' ) ),
			'label_role'   => array( __( 'Campo de papel', 'diio-wp-theme' ), __( 'Meu papel', 'diio-wp-theme' ) ),
			'label_tech'   => array( __( 'Campo de tecnologias', 'diio-wp-theme' ), __( 'Tecnologias', 'diio-wp-theme' ) ),
		),
		__( 'Opções de status', 'diio-wp-theme' )  => array(
			'status_active'   => array( __( 'Em andamento', 'diio-wp-theme' ), __( 'Em andamento', 'diio-wp-theme' ) ),
			'status_done'     => array( __( 'Concluído', 'diio-wp-theme' ), __( 'Concluído', 'diio-wp-theme' ) ),
			'status_paused'   => array( __( 'Pausado', 'diio-wp-theme' ), __( 'Pausado', 'diio-wp-theme' ) ),
			'status_archived' => array( __( 'Arquivado', 'diio-wp-theme' ), __( 'Arquivado', 'diio-wp-theme' ) ),
		),
		__( 'Texto padrão dos botões', 'diio-wp-theme' ) => array(
			'link_url'  => array( __( 'Link do projeto', 'diio-wp-theme' ), __( 'ver projeto ↗', 'diio-wp-theme' ) ),
			'link_repo' => array( __( 'Link do repositório', 'diio-wp-theme' ), __( 'código-fonte ↗', 'diio-wp-theme' ) ),
		),
	);
}

/**
 * Lê um texto configurado, ou o padrão se ele estiver vazio.
 *
 * @param string $key Chave de diio_project_text_groups().
 * @return string
 */
function diio_project_text( $key ) {
	$saved = get_option( 'diio_project_texts', array() );
	if ( ! empty( $saved[ $key ] ) ) {
		return $saved[ $key ];
	}
	foreach ( diio_project_text_groups() as $fields ) {
		if ( isset( $fields[ $key ] ) ) {
			return $fields[ $key ][1];
		}
	}
	return '';
}

/**
 * Registra a opção.
 */
function diio_register_project_settings() {
	register_setting(
		'diio_project_settings',
		'diio_project_texts',
		array(
			'type'              => 'array',
			'default'           => array(),
			'sanitize_callback' => 'diio_sanitize_project_texts',
		)
	);
}
add_action( 'admin_init', 'diio_register_project_settings' );

/**
 * Mantém só as chaves conhecidas e os textos preenchidos.
 *
 * @param mixed $input Valores enviados.
 * @return array<string, string>
 */
function diio_sanitize_project_texts( $input ) {
	$clean = array();
	if ( ! is_array( $input ) ) {
		return $clean;
	}
	foreach ( diio_project_text_groups() as $fields ) {
		foreach ( array_keys( $fields ) as $key ) {
			$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
			if ( '' !== $value ) {
				$clean[ $key ] = $value;
			}
		}
	}
	return $clean;
}

/**
 * Adiciona a tela ao menu "Projetos".
 */
function diio_add_project_settings_page() {
	add_submenu_page(
		'edit.php?post_type=project',
		__( 'Configurações dos projetos', 'diio-wp-theme' ),
		__( 'Configurações', 'diio-wp-theme' ),
		'manage_options',
		'diio-project-settings',
		'diio_render_project_settings_page'
	);
}
add_action( 'admin_menu', 'diio_add_project_settings_page' );

/**
 * Formulário da tela.
 */
function diio_render_project_settings_page() {
	$saved = get_option( 'diio_project_texts', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Configurações dos projetos', 'diio-wp-theme' ); ?></h1>
		<p><?php esc_html_e( 'Estes textos valem para todos os projetos. Deixe um campo vazio para usar o texto padrão.', 'diio-wp-theme' ); ?></p>
		<?php settings_errors( 'diio_project_settings' ); ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'diio_project_settings' ); ?>
			<?php foreach ( diio_project_text_groups() as $group => $fields ) : ?>
				<h2 class="title"><?php echo esc_html( $group ); ?></h2>
				<table class="form-table" role="presentation">
					<?php foreach ( $fields as $key => $field ) : ?>
						<tr>
							<th scope="row"><label for="diio-text-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
							<td><input class="regular-text" id="diio-text-<?php echo esc_attr( $key ); ?>" type="text" name="diio_project_texts[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( isset( $saved[ $key ] ) ? $saved[ $key ] : '' ); ?>" placeholder="<?php echo esc_attr( $field[1] ); ?>"></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
