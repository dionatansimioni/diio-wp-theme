<?php
/**
 * Portfólio: tipo de post "Projeto", tecnologias, campos extras e os blocos
 * que os exibem.
 *
 * @package diio-wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aumente ao mudar URLs do portfólio, para atualizar os links permanentes
 * também em quem só substituiu os arquivos do tema, sem reativá-lo.
 */
define( 'DIIO_REWRITE_VERSION', 2 );

/**
 * Tipo de post "Projeto" e taxonomia "Tecnologia".
 */
function diio_register_project_post_type() {
	register_post_type(
		'project',
		array(
			'labels'       => array(
				'name'          => __( 'Projetos', 'diio-wp-theme' ),
				'singular_name' => __( 'Projeto', 'diio-wp-theme' ),
				'add_new_item'  => __( 'Adicionar novo projeto', 'diio-wp-theme' ),
				'edit_item'     => __( 'Editar projeto', 'diio-wp-theme' ),
				'all_items'     => __( 'Todos os projetos', 'diio-wp-theme' ),
			),
			'public'       => true,
			'has_archive'  => 'projetos',
			'rewrite'      => array( 'slug' => 'projetos' ),
			'menu_icon'    => 'dashicons-portfolio',
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		)
	);

	register_taxonomy(
		'project_tech',
		'project',
		array(
			'labels'            => array(
				'name'          => __( 'Tecnologias', 'diio-wp-theme' ),
				'singular_name' => __( 'Tecnologia', 'diio-wp-theme' ),
				'add_new_item'  => __( 'Adicionar nova tecnologia', 'diio-wp-theme' ),
				'edit_item'     => __( 'Editar tecnologia', 'diio-wp-theme' ),
				'all_items'     => __( 'Todas as tecnologias', 'diio-wp-theme' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tecnologia' ),
		)
	);
}
add_action( 'init', 'diio_register_project_post_type' );

/**
 * Atualiza os links permanentes ao ativar o tema, para /projetos/ funcionar.
 */
function diio_flush_rewrite_rules() {
	diio_register_project_post_type();
	flush_rewrite_rules();
	update_option( 'diio_rewrite_version', DIIO_REWRITE_VERSION );
}
add_action( 'after_switch_theme', 'diio_flush_rewrite_rules' );

/**
 * Faz o mesmo quando o tema é atualizado sem ser reativado.
 */
function diio_maybe_flush_rewrite_rules() {
	if ( DIIO_REWRITE_VERSION !== (int) get_option( 'diio_rewrite_version' ) ) {
		diio_flush_rewrite_rules();
	}
}
add_action( 'init', 'diio_maybe_flush_rewrite_rules', 20 );

/**
 * Opções do campo "Status".
 *
 * @return array<string, string>
 */
function diio_project_statuses() {
	return array(
		'active'   => diio_project_text( 'status_active' ),
		'done'     => diio_project_text( 'status_done' ),
		'paused'   => diio_project_text( 'status_paused' ),
		'archived' => diio_project_text( 'status_archived' ),
	);
}

/**
 * Ícones disponíveis para a miniatura: arquivo em assets/icons => nome.
 *
 * @return array<string, string>
 */
function diio_project_icons() {
	return array(
		'code'           => __( 'Código', 'diio-wp-theme' ),
		'terminal'       => __( 'Terminal', 'diio-wp-theme' ),
		'linux'          => __( 'Linux', 'diio-wp-theme' ),
		'github'         => __( 'GitHub', 'diio-wp-theme' ),
		'youtube'        => __( 'YouTube', 'diio-wp-theme' ),
		'video'          => __( 'Vídeo', 'diio-wp-theme' ),
		'mic'            => __( 'Podcast', 'diio-wp-theme' ),
		'music'          => __( 'Música', 'diio-wp-theme' ),
		'camera'         => __( 'Foto', 'diio-wp-theme' ),
		'globe'          => __( 'Site', 'diio-wp-theme' ),
		'newspaper'      => __( 'Blog', 'diio-wp-theme' ),
		'smartphone'     => __( 'App', 'diio-wp-theme' ),
		'gamepad-2'      => __( 'Jogo', 'diio-wp-theme' ),
		'book-open'      => __( 'Livro', 'diio-wp-theme' ),
		'graduation-cap' => __( 'Curso', 'diio-wp-theme' ),
		'wrench'         => __( 'Ferramenta', 'diio-wp-theme' ),
		'bot'            => __( 'Automação', 'diio-wp-theme' ),
		'cpu'            => __( 'Hardware', 'diio-wp-theme' ),
		'server'         => __( 'Servidor', 'diio-wp-theme' ),
		'database'       => __( 'Dados', 'diio-wp-theme' ),
		'palette'        => __( 'Design', 'diio-wp-theme' ),
		'rocket'         => __( 'Lançamento', 'diio-wp-theme' ),
		'heart'          => __( 'Favorito', 'diio-wp-theme' ),
	);
}

/**
 * Cores de fundo da miniatura com ícone.
 *
 * @return array<string, string>
 */
function diio_project_icon_colors() {
	return array(
		'gradient' => __( 'Azul e fúcsia', 'diio-wp-theme' ),
		'blue'     => __( 'Azul', 'diio-wp-theme' ),
		'violet'   => __( 'Violeta', 'diio-wp-theme' ),
		'fuchsia'  => __( 'Fúcsia', 'diio-wp-theme' ),
	);
}

/**
 * SVG de um ícone da lista.
 *
 * @param string $icon Chave de diio_project_icons().
 * @return string SVG, vazio se o ícone não existir.
 */
function diio_project_icon_svg( $icon ) {
	if ( ! array_key_exists( $icon, diio_project_icons() ) ) {
		return '';
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- arquivo local do tema.
	$svg = file_get_contents( get_theme_file_path( 'assets/icons/' . $icon . '.svg' ) );
	return false === $svg ? '' : trim( $svg );
}

/**
 * Lê um campo do projeto.
 *
 * @param int    $post_id ID do projeto.
 * @param string $field   status, year, role, url, repo, url_label, repo_label,
 *                        icon, icon_color ou card_text.
 * @return string
 */
function diio_project_field( $post_id, $field ) {
	return (string) get_post_meta( $post_id, '_diio_project_' . $field, true );
}

/**
 * Caixa "Informações do projeto" na tela de edição.
 */
function diio_add_project_meta_box() {
	add_meta_box(
		'diio-project-info',
		__( 'Informações do projeto', 'diio-wp-theme' ),
		'diio_render_project_meta_box',
		'project',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes_project', 'diio_add_project_meta_box' );

/**
 * Formulário da caixa.
 *
 * @param WP_Post $post Projeto em edição.
 */
function diio_render_project_meta_box( $post ) {
	wp_nonce_field( 'diio_save_project', 'diio_project_nonce' );

	$status     = diio_project_field( $post->ID, 'status' );
	$icon       = diio_project_field( $post->ID, 'icon' );
	$icon_color = diio_project_field( $post->ID, 'icon_color' );
	$fields     = array(
		'year'       => array( diio_project_text( 'label_year' ), 'text', '2026' ),
		'role'       => array( diio_project_text( 'label_role' ), 'text', __( 'Criador e mantenedor', 'diio-wp-theme' ) ),
		'url'        => array( __( 'Link do projeto', 'diio-wp-theme' ), 'url', 'https://' ),
		'url_label'  => array( __( 'Texto desse link', 'diio-wp-theme' ), 'text', diio_project_text( 'link_url' ) ),
		'repo'       => array( __( 'Link do repositório', 'diio-wp-theme' ), 'url', 'https://github.com/' ),
		'repo_label' => array( __( 'Texto desse link', 'diio-wp-theme' ), 'text', diio_project_text( 'link_repo' ) ),
	);
	?>
	<fieldset class="diio-icon-grid">
		<legend><strong><?php esc_html_e( 'Miniatura', 'diio-wp-theme' ); ?></strong></legend>
		<label class="diio-icon-option" title="<?php esc_attr_e( 'Usar a imagem destacada', 'diio-wp-theme' ); ?>">
			<input type="radio" name="diio_project[icon]" value="" <?php checked( $icon, '' ); ?>>
			<span class="diio-icon-none"><?php esc_html_e( 'Imagem', 'diio-wp-theme' ); ?></span>
		</label>
		<?php foreach ( diio_project_icons() as $value => $label ) : ?>
			<label class="diio-icon-option" title="<?php echo esc_attr( $label ); ?>">
				<input type="radio" name="diio_project[icon]" value="<?php echo esc_attr( $value ); ?>" aria-label="<?php echo esc_attr( $label ); ?>" <?php checked( $icon, $value ); ?>>
				<?php echo diio_project_icon_svg( $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- arquivo local do tema. ?>
			</label>
		<?php endforeach; ?>
	</fieldset>
	<p>
		<label for="diio-project-icon-color"><strong><?php esc_html_e( 'Cor de fundo do ícone', 'diio-wp-theme' ); ?></strong></label><br>
		<select id="diio-project-icon-color" name="diio_project[icon_color]">
			<?php foreach ( diio_project_icon_colors() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $icon_color, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="diio-project-card-text"><strong><?php esc_html_e( 'Texto do card', 'diio-wp-theme' ); ?></strong></label><br>
		<textarea class="widefat" id="diio-project-card-text" name="diio_project[card_text]" rows="3" placeholder="<?php esc_attr_e( 'Vazio: usa o resumo do projeto', 'diio-wp-theme' ); ?>"><?php echo esc_textarea( diio_project_field( $post->ID, 'card_text' ) ); ?></textarea>
	</p>
	<p>
		<label for="diio-project-status"><strong><?php echo esc_html( diio_project_text( 'label_status' ) ); ?></strong></label><br>
		<select id="diio-project-status" name="diio_project[status]">
			<option value=""><?php esc_html_e( 'Não informar', 'diio-wp-theme' ); ?></option>
			<?php foreach ( diio_project_statuses() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php foreach ( $fields as $name => $field ) : ?>
		<p>
			<label for="diio-project-<?php echo esc_attr( $name ); ?>"><strong><?php echo esc_html( $field[0] ); ?></strong></label><br>
			<input class="widefat" id="diio-project-<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $field[1] ); ?>" name="diio_project[<?php echo esc_attr( $name ); ?>]" value="<?php echo esc_attr( diio_project_field( $post->ID, $name ) ); ?>" placeholder="<?php echo esc_attr( $field[2] ); ?>">
		</p>
	<?php endforeach; ?>
	<?php if ( current_user_can( 'manage_options' ) ) : ?>
		<p class="description"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=project&page=diio-project-settings' ) ); ?>"><?php esc_html_e( 'Renomear campos e status', 'diio-wp-theme' ); ?></a></p>
	<?php endif; ?>
	<?php
}

/**
 * Estilo do seletor de miniatura na tela de edição.
 *
 * @param string $hook Tela atual.
 */
function diio_enqueue_project_admin_assets( $hook ) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && 'project' === get_post_type() ) {
		wp_enqueue_style( 'diio-project-admin', get_theme_file_uri( 'assets/css/admin.css' ), array(), wp_get_theme()->get( 'Version' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'diio_enqueue_project_admin_assets' );

/**
 * Salva os campos da caixa.
 *
 * @param int $post_id ID do projeto.
 */
function diio_save_project_meta_box( $post_id ) {
	if ( ! isset( $_POST['diio_project_nonce'], $_POST['diio_project'] ) || ! is_array( $_POST['diio_project'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['diio_project_nonce'] ) ), 'diio_save_project' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitizado campo a campo abaixo.
	$input  = wp_unslash( $_POST['diio_project'] );
	$status = isset( $input['status'] ) ? sanitize_key( $input['status'] ) : '';
	$icon   = isset( $input['icon'] ) ? sanitize_key( $input['icon'] ) : '';
	$color  = isset( $input['icon_color'] ) ? sanitize_key( $input['icon_color'] ) : '';
	$values = array(
		'status'     => array_key_exists( $status, diio_project_statuses() ) ? $status : '',
		'icon'       => array_key_exists( $icon, diio_project_icons() ) ? $icon : '',
		'icon_color' => array_key_exists( $color, diio_project_icon_colors() ) ? $color : '',
		'card_text'  => isset( $input['card_text'] ) ? sanitize_textarea_field( $input['card_text'] ) : '',
		'year'       => isset( $input['year'] ) ? sanitize_text_field( $input['year'] ) : '',
		'role'       => isset( $input['role'] ) ? sanitize_text_field( $input['role'] ) : '',
		'url'        => isset( $input['url'] ) ? esc_url_raw( trim( $input['url'] ), array( 'http', 'https' ) ) : '',
		'url_label'  => isset( $input['url_label'] ) ? sanitize_text_field( $input['url_label'] ) : '',
		'repo'       => isset( $input['repo'] ) ? esc_url_raw( trim( $input['repo'] ), array( 'http', 'https' ) ) : '',
		'repo_label' => isset( $input['repo_label'] ) ? sanitize_text_field( $input['repo_label'] ) : '',
	);

	foreach ( $values as $field => $value ) {
		if ( '' === $value ) {
			delete_post_meta( $post_id, '_diio_project_' . $field );
		} else {
			update_post_meta( $post_id, '_diio_project_' . $field, $value );
		}
	}
}
add_action( 'save_post_project', 'diio_save_project_meta_box' );

/**
 * Blocos do projeto: miniatura, texto do card e informações.
 */
function diio_register_project_blocks() {
	wp_register_script(
		'diio-project-blocks-editor',
		get_theme_file_uri( 'assets/js/project-blocks.js' ),
		array( 'wp-blocks', 'wp-element', 'wp-block-editor' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	$blocks = array(
		'project-info'      => array( __( 'Informações do projeto', 'diio-wp-theme' ), 'portfolio', 'variant', 'full', 'diio_render_project_info_block' ),
		'project-thumbnail' => array( __( 'Miniatura do projeto', 'diio-wp-theme' ), 'format-image', 'size', 'card', 'diio_render_project_thumbnail_block' ),
		'project-text'      => array( __( 'Texto do card do projeto', 'diio-wp-theme' ), 'text', null, null, 'diio_render_project_text_block' ),
	);

	foreach ( $blocks as $name => $block ) {
		register_block_type(
			'diio/' . $name,
			array(
				'api_version'     => 3,
				'title'           => $block[0],
				'category'        => 'theme',
				'icon'            => $block[1],
				'attributes'      => $block[2] ? array(
					$block[2] => array(
						'type'    => 'string',
						'default' => $block[3],
					),
				) : array(),
				'uses_context'    => array( 'postId' ),
				'editor_script'   => 'diio-project-blocks-editor',
				'render_callback' => $block[4],
			)
		);
	}
}
add_action( 'init', 'diio_register_project_blocks' );

/**
 * Projeto em que o bloco está sendo exibido.
 *
 * @param WP_Block $block Instância do bloco.
 * @return int ID do projeto, 0 se não houver.
 */
function diio_block_project_id( $block ) {
	$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
	return $post_id && 'project' === get_post_type( $post_id ) ? $post_id : 0;
}

/**
 * Classes da miniatura com ícone.
 *
 * @param int $post_id ID do projeto.
 * @return string
 */
function diio_project_icon_classes( $post_id ) {
	$color = diio_project_field( $post_id, 'icon_color' );
	return 'is-icon has-icon-color-' . ( array_key_exists( $color, diio_project_icon_colors() ) ? $color : 'gradient' );
}

/**
 * Miniatura: o ícone escolhido ou, sem ícone, a imagem destacada.
 *
 * @param array    $attributes Atributos do bloco.
 * @param string   $content    Conteúdo (não usado).
 * @param WP_Block $block      Instância do bloco.
 * @return string
 */
function diio_render_project_thumbnail_block( $attributes, $content, $block ) {
	$post_id = diio_block_project_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$class = 'project-thumb';
	if ( isset( $attributes['size'] ) && 'banner' === $attributes['size'] ) {
		$class .= ' is-banner alignwide';
	}

	$html = diio_project_icon_svg( diio_project_field( $post_id, 'icon' ) );
	if ( $html ) {
		$class .= ' ' . diio_project_icon_classes( $post_id );
	} else {
		$html = get_the_post_thumbnail( $post_id, 'large' );
	}

	return $html ? '<div ' . get_block_wrapper_attributes( array( 'class' => $class ) ) . '>' . $html . '</div>' : '';
}

/**
 * Texto do card: o texto próprio ou, sem ele, o resumo do projeto.
 *
 * @param array    $attributes Atributos do bloco.
 * @param string   $content    Conteúdo (não usado).
 * @param WP_Block $block      Instância do bloco.
 * @return string
 */
function diio_render_project_text_block( $attributes, $content, $block ) {
	$post_id = diio_block_project_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$text = diio_project_field( $post_id, 'card_text' );
	if ( '' === $text ) {
		$text = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( get_the_excerpt( $post_id ), 20 );
	}

	return '' === $text ? '' : '<p ' . get_block_wrapper_attributes( array( 'class' => 'project-text' ) ) . '>' . nl2br( esc_html( $text ) ) . '</p>';
}

/**
 * Projeto que um bloco do core está exibindo.
 *
 * @param WP_Block $instance Instância do bloco.
 * @return int ID do projeto, 0 se o bloco estiver em outro tipo de post.
 */
function diio_core_block_project_id( $instance ) {
	$post_id = isset( $instance->context['postId'] ) ? (int) $instance->context['postId'] : 0;
	return $post_id && 'project' === get_post_type( $post_id ) ? $post_id : 0;
}

/**
 * Bloco de resumo em projetos.
 *
 * Na página do projeto, aparece só se o resumo foi escrito à mão: sem isso, o
 * WordPress o gera a partir do conteúdo, que apareceria duas vezes. Nas listas,
 * dá lugar ao texto do card, pois templates salvos no Editor do site guardam o
 * bloco de resumo de versões anteriores do tema.
 *
 * @param string   $block_content HTML do bloco.
 * @param array    $block         Dados do bloco.
 * @param WP_Block $instance      Instância do bloco.
 * @return string
 */
function diio_filter_project_excerpt_block( $block_content, $block, $instance ) {
	$post_id = diio_core_block_project_id( $instance );
	if ( ! $post_id ) {
		return $block_content;
	}
	if ( is_singular( 'project' ) && get_queried_object_id() === $post_id ) {
		return has_excerpt( $post_id ) ? $block_content : '';
	}

	$text = diio_project_field( $post_id, 'card_text' );
	return '' === $text ? $block_content : '<p class="wp-block-diio-project-text project-text">' . nl2br( esc_html( $text ) ) . '</p>';
}
add_filter( 'render_block_core/post-excerpt', 'diio_filter_project_excerpt_block', 10, 3 );

/**
 * Bloco de imagem destacada em projetos: dá lugar ao ícone escolhido, pelo
 * mesmo motivo do resumo acima.
 *
 * @param string   $block_content HTML do bloco.
 * @param array    $block         Dados do bloco.
 * @param WP_Block $instance      Instância do bloco.
 * @return string
 */
function diio_filter_project_featured_image_block( $block_content, $block, $instance ) {
	$post_id = diio_core_block_project_id( $instance );
	$svg     = $post_id ? diio_project_icon_svg( diio_project_field( $post_id, 'icon' ) ) : '';
	if ( ! $svg ) {
		return $block_content;
	}

	$class = 'wp-block-diio-project-thumbnail project-thumb ' . diio_project_icon_classes( $post_id );
	if ( is_singular( 'project' ) && get_queried_object_id() === $post_id ) {
		$class .= ' is-banner alignwide';
	}
	return '<div class="' . esc_attr( $class ) . '">' . $svg . '</div>';
}
add_filter( 'render_block_core/post-featured-image', 'diio_filter_project_featured_image_block', 10, 3 );

/**
 * Links externos do projeto.
 *
 * @param int    $post_id ID do projeto.
 * @param string $class   Classe de cada link.
 * @return string HTML dos links, vazio se não houver nenhum.
 */
function diio_project_links_html( $post_id, $class ) {
	$links = array(
		'url'  => diio_project_text( 'link_url' ),
		'repo' => diio_project_text( 'link_repo' ),
	);
	$html  = '';
	foreach ( $links as $field => $label ) {
		$url    = diio_project_field( $post_id, $field );
		$custom = diio_project_field( $post_id, $field . '_label' );
		$label  = $custom ? $custom : $label;
		if ( $url ) {
			$html .= sprintf(
				'<a class="%s" href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_attr( $class ),
				esc_url( $url ),
				esc_html( $label )
			);
		}
	}
	return $html;
}

/**
 * Renderiza o bloco.
 *
 * @param array    $attributes Atributos do bloco.
 * @param string   $content    Conteúdo (não usado).
 * @param WP_Block $block      Instância do bloco.
 * @return string
 */
function diio_render_project_info_block( $attributes, $content, $block ) {
	$post_id = diio_block_project_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$statuses = diio_project_statuses();
	$status   = diio_project_field( $post_id, 'status' );
	$year     = diio_project_field( $post_id, 'year' );
	$role     = diio_project_field( $post_id, 'role' );

	$status_html = isset( $statuses[ $status ] )
		? sprintf( '<span class="project-status is-%s">%s</span>', esc_attr( $status ), esc_html( $statuses[ $status ] ) )
		: '';

	$terms     = get_the_terms( $post_id, 'project_tech' );
	$tech_html = '';
	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$tech_html .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( get_term_link( $term ) ), esc_html( $term->name ) );
		}
		$tech_html = '<ul class="project-techs">' . $tech_html . '</ul>';
	}

	if ( isset( $attributes['variant'] ) && 'compact' === $attributes['variant'] ) {
		$badges = $status_html . ( $year ? '<span class="project-year">' . esc_html( $year ) . '</span>' : '' );
		$links  = diio_project_links_html( $post_id, 'project-link' );
		$html   = ( $badges ? '<p class="project-badges">' . $badges . '</p>' : '' )
			. $tech_html
			. ( $links ? '<p class="project-links">' . $links . '</p>' : '' );
		$class  = 'project-info is-compact';
	} else {
		$rows = array(
			array( 'label_status', $status_html ),
			array( 'label_year', esc_html( $year ) ),
			array( 'label_role', esc_html( $role ) ),
			array( 'label_tech', $tech_html ),
		);
		$list = '';
		foreach ( $rows as $row ) {
			if ( '' !== $row[1] ) {
				$list .= '<div class="project-row"><dt>' . esc_html( diio_project_text( $row[0] ) ) . '</dt><dd>' . $row[1] . '</dd></div>';
			}
		}
		$links = diio_project_links_html( $post_id, 'wp-block-button__link wp-element-button' );
		$html  = ( $list ? '<dl class="project-details">' . $list . '</dl>' : '' )
			. ( $links ? '<p class="project-actions">' . $links . '</p>' : '' );
		$class = 'project-info is-full';
	}

	if ( '' === $html ) {
		return '';
	}

	return '<div ' . get_block_wrapper_attributes( array( 'class' => $class ) ) . '>' . $html . '</div>';
}
