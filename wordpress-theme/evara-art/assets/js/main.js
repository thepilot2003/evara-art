/**
 * Evara Art - Main JavaScript
 *
 * @package EvaraArt
 * @version 1.3.5
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

	});

})(jQuery);
