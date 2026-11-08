/**
 * Blocos do projeto no editor. A saída real vem do PHP; aqui só aparece um
 * marcador, porque o conteúdo depende de cada projeto.
 */
( function ( wp ) {
	var el = wp.element.createElement;

	var blocks = {
		'diio/project-info': [ 'Informações do projeto', 'portfolio', '// informações do projeto: status, ano, tecnologias e links' ],
		'diio/project-thumbnail': [ 'Miniatura do projeto', 'format-image', '// miniatura do projeto: ícone ou imagem destacada' ],
		'diio/project-text': [ 'Texto do card do projeto', 'text', '// texto do card do projeto' ],
	};

	Object.keys( blocks ).forEach( function ( name ) {
		wp.blocks.registerBlockType( name, {
			apiVersion: 3,
			title: blocks[ name ][ 0 ],
			category: 'theme',
			icon: blocks[ name ][ 1 ],
			edit: function () {
				return el(
					'div',
					wp.blockEditor.useBlockProps( { className: 'project-info-placeholder' } ),
					blocks[ name ][ 2 ]
				);
			},
			save: function () {
				return null;
			},
		} );
	} );
} )( window.wp );
