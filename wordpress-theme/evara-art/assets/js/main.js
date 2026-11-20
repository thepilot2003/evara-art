/**
 * Evara Art - Main JavaScript
 *
 * @package EvaraArt
 * @version 1.4.6
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

		// Instant AJAX Live Search
		var $searchInput = $('#evara-search-input');
		var $searchResults = $('#evara-live-search-results');
		var liveSearchTimer = null;

		$searchInput.on('input', function() {
			var query = $(this).val().trim();
			clearTimeout(liveSearchTimer);

			if (query.length < 2) {
				$searchResults.hide().empty();
				return;
			}

			$searchResults.show().html('<div class="live-search-loading"><i class="fa-solid fa-spinner fa-spin"></i> Searching artworks...</div>');

			liveSearchTimer = setTimeout(function() {
				var ajaxUrl = (typeof evara_main_vars !== 'undefined' && evara_main_vars.ajax_url) ? evara_main_vars.ajax_url : '/wp-admin/admin-ajax.php';
				$.ajax({
					url: ajaxUrl,
					type: 'GET',
					data: {
						action: 'evara_live_search',
						keyword: query
					},
					dataType: 'json',
					success: function(res) {
						if (res.success && res.data.results && res.data.results.length > 0) {
							var html = '<div class="live-search-header"><span>Matching Artworks (' + res.data.total + ')</span></div>';
							html += '<div class="live-search-list">';
							res.data.results.forEach(function(item) {
								html += '<a href="' + item.permalink + '" class="live-search-item">';
								html += '  <img src="' + item.thumbnail + '" alt="' + item.title + '" class="live-search-thumb" />';
								html += '  <div class="live-search-info">';
								html += '    <h4 class="live-search-title">' + item.title + '</h4>';
								html += '    <div class="live-search-price">' + item.price + '</div>';
								html += '  </div>';
								html += '  <i class="fa-solid fa-arrow-right live-search-arrow"></i>';
								html += '</a>';
							});
							html += '</div>';
							if (res.data.total > res.data.results.length) {
								html += '<a href="' + res.data.all_url + '" class="live-search-view-all">View all ' + res.data.total + ' results &rarr;</a>';
							}
							$searchResults.html(html);
						} else {
							$searchResults.html('<div class="live-search-empty">No artworks found for &ldquo;' + query + '&rdquo;. Try searching for <em>Krishna, Lippan Art, or Ganesha</em>.</div>');
						}
					},
					error: function() {
						$searchResults.hide().empty();
					}
				});
			}, 250);
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

			// Check image count & flexslider presence
			var hasFlexViewport = $gallery.find('.flex-viewport').length > 0;
			var slideCount = $gallery.find('.woocommerce-product-gallery__image').length;
			if (!hasFlexViewport && slideCount <= 1) {
				$gallery.addClass('is-single-image').removeClass('has-multiple-images');
				$gallery.find('.flex-direction-nav, .flex-control-thumbs').hide();
			} else {
				$gallery.addClass('has-multiple-images').removeClass('is-single-image');
			}

			// Clean rogue margins that break Flexslider horizontal track (retain float and display for Flexslider)
			$gallery.find('.woocommerce-product-gallery__image').each(function() {
				this.style.removeProperty('margin-left');
				this.style.removeProperty('margin-right');
				this.style.removeProperty('justify-content');
				this.style.removeProperty('align-items');
			});

			// Proactively remove any ghost zoom or Avada fusion rollover overlays
			$gallery.find('.zoomImg, img.zoomImg, .fusion-rollover, .avada-image-rollover').remove();

			$gallery.css({ 'opacity': '1', 'visibility': 'visible' });
			$gallery.find('.flex-direction-nav .flex-prev').attr('aria-label', 'Previous Artwork Image');
			$gallery.find('.flex-direction-nav .flex-next').attr('aria-label', 'Next Artwork Image');

			// Ensure images are block and centered horizontally within the slide
			$gallery.find('.woocommerce-product-gallery__image img').css({
				'display': 'block',
				'margin-left': 'auto',
				'margin-right': 'auto'
			});

			// Trigger resize so Flexslider calculates active slide track width accurately
			setTimeout(function() {
				$(window).trigger('resize');
			}, 80);
			setTimeout(function() {
				$(window).trigger('resize');
			}, 300);
		}
		enhanceProductGallery();
		$(window).on('load', enhanceProductGallery);
		$(document).on('wc-product-gallery-after-init', enhanceProductGallery);

		// 9. Fail-Safe Cart Trigger Binding
		$(document).on('click', '#evara-cart-open, .cart-trigger', function(e) {
			var $drawer = $('#evara-cart-drawer');
			if ($drawer.length) {
				e.preventDefault();
				$drawer.addClass('is-open').attr('aria-hidden', 'false');
				$('#evara-cart-open').attr('aria-expanded', 'true');
				$('body').css('overflow', 'hidden');
				var $close = $('#evara-cart-close');
				if ($close.length) {
					$close.focus();
				}
			}
		});

	});

})(jQuery);
