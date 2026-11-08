/**
 * Animações do tema diio_WP_theme.
 *
 * Tudo aqui é aprimoramento: sem JS o conteúdo aparece normalmente.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var finePointer = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

	/* ---------- Header que "encolhe" ao rolar ---------- */

	function initHeader() {
		var header = document.querySelector( '.wp-site-blocks > header' );
		if ( ! header ) {
			return;
		}
		var update = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 12 );
		};
		update();
		window.addEventListener( 'scroll', update, { passive: true } );
	}

	/* ---------- Entrada dos elementos ao rolar ---------- */

	function initReveal() {
		var groups = [
			'.section-header',
			'.wp-block-post-template > li',
			'.site-main > .wp-block-post-content > *',
			'.site-main > .wp-block-post-featured-image',
			'.site-main > .project-thumb',
			'.is-style-terminal',
		];
		var items = document.querySelectorAll( groups.join( ',' ) );
		if ( ! items.length ) {
			return;
		}

		items.forEach( function ( el ) {
			// Itens irmãos entram em cascata.
			var index = Array.prototype.indexOf.call( el.parentNode.children, el );
			el.style.setProperty( '--reveal-delay', Math.min( index, 6 ) * 80 + 'ms' );
			el.classList.add( 'reveal' );
		} );

		if ( reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
			items.forEach( function ( el ) {
				el.classList.add( 'is-visible' );
			} );
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0 }
		);

		items.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* ---------- Efeito de digitação no prompt do hero ---------- */

	function initTyping() {
		if ( reduceMotion ) {
			return;
		}
		document.querySelectorAll( '.hero .terminal-line' ).forEach( function ( line ) {
			var prompt = line.querySelector( '.prompt' );
			var textNode = prompt ? prompt.nextSibling : null;
			if ( ! textNode || textNode.nodeType !== Node.TEXT_NODE ) {
				return;
			}
			var text = textNode.textContent;
			var i = 0;
			textNode.textContent = '';
			line.classList.add( 'is-typing' );

			var type = function () {
				textNode.textContent = text.slice( 0, ++i );
				if ( i < text.length ) {
					setTimeout( type, 70 + Math.random() * 70 );
				} else {
					setTimeout( function () {
						line.classList.remove( 'is-typing' );
					}, 1200 );
				}
			};
			setTimeout( type, 500 );
		} );
	}

	/* ---------- Cards com inclinação 3D e holofote ---------- */

	function initCards() {
		if ( reduceMotion || ! finePointer ) {
			return;
		}
		document.querySelectorAll( '.card' ).forEach( function ( card ) {
			card.addEventListener( 'pointermove', function ( e ) {
				var rect = card.getBoundingClientRect();
				var x = ( e.clientX - rect.left ) / rect.width;
				var y = ( e.clientY - rect.top ) / rect.height;
				card.style.setProperty( '--mx', x * 100 + '%' );
				card.style.setProperty( '--my', y * 100 + '%' );
				card.style.transform =
					'perspective(900px) translateY(-6px)' +
					' rotateX(' + ( 0.5 - y ) * 8 + 'deg)' +
					' rotateY(' + ( x - 0.5 ) * 8 + 'deg)';
			} );
			card.addEventListener( 'pointerleave', function () {
				card.style.transform = '';
			} );
		} );
	}

	/* ---------- Barra de progresso de leitura ---------- */

	function initProgress() {
		var body = document.body;
		if ( ! body.classList.contains( 'single-post' ) && ! body.classList.contains( 'single-project' ) ) {
			return;
		}
		var bar = document.createElement( 'div' );
		bar.className = 'diio-progress';
		bar.setAttribute( 'aria-hidden', 'true' );
		body.appendChild( bar );

		var ticking = false;
		var update = function () {
			var max = root.scrollHeight - window.innerHeight;
			bar.style.setProperty( '--progress', max > 0 ? Math.min( window.scrollY / max, 1 ) : 0 );
			ticking = false;
		};
		update();
		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					ticking = true;
					window.requestAnimationFrame( update );
				}
			},
			{ passive: true }
		);
	}

	/* ---------- Alternância entre modo escuro (padrão) e claro ---------- */

	function initThemeToggle() {
		var buttons = document.querySelectorAll( '.diio-theme-toggle' );
		if ( ! buttons.length ) {
			return;
		}

		var isLight = function () {
			return root.getAttribute( 'data-theme' ) === 'light';
		};

		var syncButtons = function () {
			buttons.forEach( function ( btn ) {
				btn.setAttribute( 'aria-pressed', isLight() ? 'true' : 'false' );
				btn.setAttribute( 'aria-label', isLight() ? 'Ativar modo escuro' : 'Ativar modo claro' );
			} );
		};

		var apply = function () {
			if ( isLight() ) {
				root.removeAttribute( 'data-theme' );
			} else {
				root.setAttribute( 'data-theme', 'light' );
			}
			try {
				localStorage.setItem( 'diio-theme', isLight() ? 'light' : 'dark' );
			} catch ( e ) {}
			syncButtons();
		};

		syncButtons();

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function ( e ) {
				if ( reduceMotion || ! document.startViewTransition ) {
					apply();
					return;
				}

				// Novo tema se espalha num círculo a partir do botão.
				var rect = btn.getBoundingClientRect();
				var x = e.clientX || rect.left + rect.width / 2;
				var y = e.clientY || rect.top + rect.height / 2;
				var radius = Math.hypot( Math.max( x, window.innerWidth - x ), Math.max( y, window.innerHeight - y ) );

				document.startViewTransition( apply ).ready.then( function () {
					root.animate(
						{ clipPath: [ 'circle(0px at ' + x + 'px ' + y + 'px)', 'circle(' + radius + 'px at ' + x + 'px ' + y + 'px)' ] },
						{ duration: 650, easing: 'cubic-bezier(0.2, 0.7, 0.2, 1)', pseudoElement: '::view-transition-new(root)' }
					);
				} );
			} );
		} );
	}

	/* ---------- Easter egg: código Konami faz chover pinguins ---------- */

	function initKonami() {
		var code = [ 'ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a' ];
		var pos = 0;

		document.addEventListener( 'keydown', function ( e ) {
			var key = e.key.length === 1 ? e.key.toLowerCase() : e.key;
			pos = key === code[ pos ] ? pos + 1 : key === code[ 0 ] ? 1 : 0;
			if ( pos === code.length ) {
				pos = 0;
				penguinRain();
			}
		} );
	}

	function penguinRain() {
		var count = reduceMotion ? 0 : 40;
		for ( var i = 0; i < count; i++ ) {
			var p = document.createElement( 'span' );
			p.className = 'diio-penguin';
			p.textContent = '🐧';
			p.setAttribute( 'aria-hidden', 'true' );
			p.style.left = Math.random() * 100 + 'vw';
			p.style.fontSize = 1.2 + Math.random() * 1.8 + 'rem';
			p.style.animationDelay = Math.random() * 1.5 + 's';
			p.style.setProperty( '--fall-duration', 2.5 + Math.random() * 2 + 's' );
			p.style.setProperty( '--fall-rotate', ( Math.random() * 720 - 360 ) + 'deg' );
			p.addEventListener( 'animationend', function () {
				this.remove();
			} );
			document.body.appendChild( p );
		}
	}

	function init() {
		initHeader();
		initReveal();
		initTyping();
		initCards();
		initProgress();
		initThemeToggle();
		initKonami();

		if ( window.console ) {
			console.log(
				'%c~/ olá, dev curioso! %c tente o código Konami ↑↑↓↓←→←→BA',
				'background:linear-gradient(90deg,#4d94ff,#f040c8);color:#fff;padding:4px 8px;border-radius:4px;font-weight:bold',
				'color:#a0a6d6'
			);
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
