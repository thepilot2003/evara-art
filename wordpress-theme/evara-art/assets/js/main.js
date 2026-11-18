/**
 * Evara Art - Main JavaScript
 *
 * @package EvaraArt
 * @version 1.4.5
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

		// 6. Automatic 1-Second 4-Slide Hero Carousel
		function initHeroSlider() {
			var $slider = $('#hero-slider');
			if (!$slider.length) return;

			var $slides = $slider.find('.slider-slide');
			var $dots = $slider.find('.slider-dot');
			var total = $slides.length;
			if (total <= 1) return;

			var current = 0;
			var interval = parseInt($slider.data('interval'), 10) || 2500;
			var timer = null;

			function goToSlide(idx) {
				$slides.removeClass('is-active');
				$dots.removeClass('active');

				$slides.eq(idx).addClass('is-active');
				$dots.eq(idx).addClass('active');
				current = idx;
			}

			function nextSlide() {
				var next = (current + 1) % total;
				goToSlide(next);
			}

			function startTimer() {
				if (timer) clearInterval(timer);
				timer = setInterval(nextSlide, interval);
			}

			function stopTimer() {
				if (timer) clearInterval(timer);
			}

			// Dot click navigation
			$dots.on('click', function(e) {
				e.preventDefault();
				var targetIdx = parseInt($(this).data('slide'), 10);
				goToSlide(targetIdx);
				startTimer();
			});

			// Pause on mouseenter, resume on mouseleave
			$slider.on('mouseenter', stopTimer).on('mouseleave', startTimer);

			// Mobile touch swipe support
			var touchStartX = 0;
			var touchEndX = 0;
			$slider.on('touchstart', function(e) {
				if (e.originalEvent && e.originalEvent.touches) {
					touchStartX = e.originalEvent.touches[0].screenX;
					stopTimer();
				}
			});

			$slider.on('touchend', function(e) {
				if (e.originalEvent && e.originalEvent.changedTouches) {
					touchEndX = e.originalEvent.changedTouches[0].screenX;
					if (touchStartX - touchEndX > 35) {
						nextSlide();
					} else if (touchEndX - touchStartX > 35) {
						var prev = (current - 1 + total) % total;
						goToSlide(prev);
					}
					startTimer();
				}
			});

			startTimer();
		}

		initHeroSlider();

		// 7. Ensure duplicate floating cart plugins are suppressed globally (Desktop & Mobile)
		function suppressDuplicateFloatingCarts() {
			var $floating = $('#moderncart-floating-cart, .moderncart-toggle-slide-out, .moderncart-floating-cart-button, .moderncart-add-to-cart-notification, .moderncart-floating-cart-count, #floating-cart, .floating-cart, .evara-floating-cart, #floating-cart-btn, .floating-cart-btn, .floating-cart-button, .xoo-wsc-cart-trigger, .xoo-wsc-basket, .woofc-count, .woofc-btn, #woofc-area, .xt_floating_cart, #xt_floating_cart, .cartpops-floating-button, .vi-wsc-floating-cart, #vi-wsc-floating-cart, .cart-floating-icon, .wp-block-woocommerce-mini-cart, .woocommerce-mini-cart-floating, [class*="floating-cart"], [id*="floating-cart"], [class*="cart-floating"], [class*="fly-cart"], [id*="fly-cart"], [class*="cart-trigger"]:not(#evara-cart-open)');
			if ($floating.length) {
				$floating.attr('style', function(i, s) {
					return (s || '') + '; display: none !important; visibility: hidden !important; pointer-events: none !important; opacity: 0 !important; width: 0 !important; height: 0 !important;';
				});
			}

			// On mobile viewports, also suppress floating WhatsApp overlay
			if (window.innerWidth <= 900) {
				$('.evara-floating-whatsapp, #evara-floating-whatsapp').attr('style', function(i, s) {
					return (s || '') + '; display: none !important; visibility: hidden !important; pointer-events: none !important;';
				});
			}
		}
		suppressDuplicateFloatingCarts();
		$(window).on('load resize orientationchange', suppressDuplicateFloatingCarts);
		// Run delayed passes in case external cart plugins load asynchronously
		setTimeout(suppressDuplicateFloatingCarts, 400);
		setTimeout(suppressDuplicateFloatingCarts, 1200);

		// 8. Enhance WooCommerce Single Product Gallery & Strip Zombie Zoom Overlays
		function enhanceProductGallery() {
			var $gallery = $('.woocommerce-product-gallery');
			if (!$gallery.length) return;

			// Proactively remove any ghost zoom overlays dynamically injected by old scripts
			$gallery.find('.zoomImg, img.zoomImg').remove();

			$gallery.css({ 'opacity': '1', 'visibility': 'visible' });
			$gallery.find('.flex-direction-nav .flex-prev').attr('aria-label', 'Previous Artwork Image');
			$gallery.find('.flex-direction-nav .flex-next').attr('aria-label', 'Next Artwork Image');

			// Trigger resize so Flexslider calculates active slide track width accurately
			setTimeout(function() {
				$(window).trigger('resize');
			}, 80);
		}
		enhanceProductGallery();
		$(window).on('load', enhanceProductGallery);

	});

})(jQuery);
