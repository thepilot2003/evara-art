/**
 * Evara Art - Main JavaScript
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		// 1. Sticky Header Scroll Effect
		const $header = $('#site-header');
		$(window).on('scroll', function() {
			if ($(this).scrollTop() > 30) {
				$header.addClass('is-scrolled');
			} else {
				$header.removeClass('is-scrolled');
			}
		});

		// 2. Mobile Menu Drawer Toggle
		const $mobileMenu = $('#evara-mobile-menu');
		$('#evara-menu-open').on('click', function() {
			$mobileMenu.addClass('is-open').attr('aria-hidden', 'false');
			$('body').css('overflow', 'hidden');
		});

		$('#evara-menu-close, #evara-menu-close-overlay').on('click', function() {
			$mobileMenu.removeClass('is-open').attr('aria-hidden', 'true');
			$('body').css('overflow', '');
		});

		// 3. Search Modal Toggle
		const $searchModal = $('#evara-search-modal');
		$('#evara-search-trigger').on('click', function() {
			$searchModal.addClass('is-open').attr('aria-hidden', 'false');
			$('#evara-search-input').focus();
			$('body').css('overflow', 'hidden');
		});

		$('#evara-search-close, #evara-search-close-backdrop').on('click', function() {
			$searchModal.removeClass('is-open').attr('aria-hidden', 'true');
			$('body').css('overflow', '');
		});

		// 4. Close on ESC key
		$(document).on('keydown', function(e) {
			if (e.key === 'Escape') {
				$mobileMenu.removeClass('is-open');
				$searchModal.removeClass('is-open');
				$('#evara-cart-drawer').removeClass('is-open');
				$('body').css('overflow', '');
			}
		});

		// 5. Scroll Reveal Animation for Sections
		if ('IntersectionObserver' in window) {
			const observer = new IntersectionObserver((entries, obs) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-revealed');
						obs.unobserve(entry.target);
					}
				});
			}, { threshold: 0.15 });

			document.querySelectorAll('.reveal-on-scroll').forEach(el => {
				observer.observe(el);
			});
		}

	});

})(jQuery);
