/**
 * Evara Art - AJAX Cart Drawer Interactions
 *
 * @package EvaraArt
 * @version 1.4.5
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		function getDrawer() {
			return $('#evara-cart-drawer');
		}

		// Open Drawer
		function openDrawer() {
			var $drawer = getDrawer();
			var $openBtn = $('#evara-cart-open');
			var $closeBtn = $('#evara-cart-close');

			if ($drawer.length) {
				$drawer.addClass('is-open').attr('aria-hidden', 'false');
				$openBtn.attr('aria-expanded', 'true');
				$('body').css('overflow', 'hidden');
				if ($closeBtn.length) {
					$closeBtn.focus();
				}
			} else {
				// Fallback: If drawer element is not found, navigate directly to cart page
				var fallbackUrl = $openBtn.data('cart-url') || (typeof evara_vars !== 'undefined' && evara_vars.cart_url ? evara_vars.cart_url : '/cart/');
				window.location.href = fallbackUrl;
			}
		}

		// Close Drawer
		function closeDrawer() {
			var $drawer = getDrawer();
			var $openBtn = $('#evara-cart-open');
			$drawer.removeClass('is-open').attr('aria-hidden', 'true');
			$openBtn.attr('aria-expanded', 'false');
			$('body').css('overflow', '');
			if ($openBtn.length) {
				$openBtn.focus();
			}
		}

		// Delegated click handler so clicks on #evara-cart-open always fire reliably
		$(document).on('click', '#evara-cart-open', function(e) {
			e.preventDefault();
			openDrawer();
		});

		$(document).on('click', '#evara-cart-close', function(e) {
			e.preventDefault();
			closeDrawer();
		});

		$(document).on('click', '#evara-cart-close-overlay', function() {
			closeDrawer();
		});

		// Close on ESC key with full accessibility restoration
		$(document).on('keydown', function(e) {
			if (e.key === 'Escape' || e.keyCode === 27) {
				var $drawer = getDrawer();
				if ($drawer.hasClass('is-open')) {
					closeDrawer();
				}
			}
		});

		// Automatically open cart drawer whenever a product is added via AJAX
		$(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
			openDrawer();
		});

		// Delegate Remove Item from Side Drawer via AJAX
		$(document).on('click', '.remove-cart-item', function(e) {
			e.preventDefault();
			var $itemRow = $(this).closest('.cart-item-row');
			var removeUrl = $(this).attr('href');

			$itemRow.css({
				'opacity': '0.35',
				'pointer-events': 'none',
				'transition': 'opacity 0.25s ease'
			});

			// Safety timeout: If wc_fragment_refresh doesn't swap DOM within 2.5s, restore row
			var safetyTimer = setTimeout(function() {
				if ($itemRow.length && $.contains(document.documentElement, $itemRow[0])) {
					$itemRow.css({ 'opacity': '1', 'pointer-events': '' });
				}
			}, 2500);

			$.ajax({
				type: 'GET',
				url: removeUrl,
				dataType: 'html',
				success: function(response) {
					// Trigger standard WooCommerce fragment refresh
					$(document.body).trigger('wc_fragment_refresh');
					clearTimeout(safetyTimer);
				},
				error: function() {
					clearTimeout(safetyTimer);
					$itemRow.css({ 'opacity': '1', 'pointer-events': '' });
				}
			});
		});

		// Ensure rows are interactive whenever fragments finish updating
		$(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
			$('.cart-item-row').css({ 'opacity': '1', 'pointer-events': '' });
		});

	});

})(jQuery);
