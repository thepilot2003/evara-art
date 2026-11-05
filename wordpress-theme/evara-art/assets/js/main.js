/**
 * Evara Art - Main JavaScript
 *
 * @package EvaraArt
 * @version 1.2.0
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
		const $menuOpenBtn = $('#evara-menu-open');
		const $menuCloseBtn = $('#evara-menu-close');

		function openMobileMenu() {
			$mobileMenu.addClass('is-open').attr('aria-hidden', 'false');
			$menuOpenBtn.attr('aria-expanded', 'true');
			$('body').css('overflow', 'hidden');
			$menuCloseBtn.focus();
		}

		function closeMobileMenu() {
			$mobileMenu.removeClass('is-open').attr('aria-hidden', 'true');
			$menuOpenBtn.attr('aria-expanded', 'false');
			$('body').css('overflow', '');
			$menuOpenBtn.focus();
		}

		$menuOpenBtn.on('click', function(e) {
			e.preventDefault();
			openMobileMenu();
		});

		$('#evara-menu-close, #evara-menu-close-overlay').on('click', function() {
			closeMobileMenu();
		});

		// 3. Search Modal Toggle
		const $searchModal = $('#evara-search-modal');
		const $searchTrigger = $('#evara-search-trigger');
		const $searchCloseBtn = $('#evara-search-close');

		function openSearchModal() {
			$searchModal.addClass('is-open').attr('aria-hidden', 'false');
			$searchTrigger.attr('aria-expanded', 'true');
			$('#evara-search-input').focus();
			$('body').css('overflow', 'hidden');
		}

		function closeSearchModal() {
			$searchModal.removeClass('is-open').attr('aria-hidden', 'true');
			$searchTrigger.attr('aria-expanded', 'false');
			$('body').css('overflow', '');
			$searchTrigger.focus();
		}

		$searchTrigger.on('click', function(e) {
			e.preventDefault();
			openSearchModal();
		});

		$('#evara-search-close, #evara-search-close-backdrop').on('click', function() {
			closeSearchModal();
		});

		// 4. Close on ESC key with full accessibility restoration
		$(document).on('keydown', function(e) {
			if (e.key === 'Escape' || e.keyCode === 27) {
				if ($mobileMenu.hasClass('is-open')) {
					closeMobileMenu();
				}
				if ($searchModal.hasClass('is-open')) {
					closeSearchModal();
				}
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
			}, { threshold: 0.12 });

			document.querySelectorAll('.reveal-on-scroll').forEach(el => {
				observer.observe(el);
			});
		}

	});

})(jQuery);
