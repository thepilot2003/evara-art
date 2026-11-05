/**
 * Evara Art - AJAX Cart Drawer Interactions
 *
 * @package EvaraArt
 * @version 1.2.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		const $drawer = $('#evara-cart-drawer');
		const $openBtn = $('#evara-cart-open');
		const $closeBtn = $('#evara-cart-close');
		const $overlay = $('#evara-cart-close-overlay');

		// Open Drawer
		function openDrawer() {
			$drawer.addClass('is-open').attr('aria-hidden', 'false');
			$openBtn.attr('aria-expanded', 'true');
			$('body').css('overflow', 'hidden');
			$closeBtn.focus();
		}

		// Close Drawer
		function closeDrawer() {
			$drawer.removeClass('is-open').attr('aria-hidden', 'true');
			$openBtn.attr('aria-expanded', 'false');
			$('body').css('overflow', '');
			$openBtn.focus();
		}

		$openBtn.on('click', function(e) {
			e.preventDefault();
			openDrawer();
		});

		$closeBtn.on('click', function(e) {
			e.preventDefault();
			closeDrawer();
		});

		$overlay.on('click', function() {
			closeDrawer();
		});

		// Close on ESC key with full accessibility restoration
		$(document).on('keydown', function(e) {
			if (e.key === 'Escape' || e.keyCode === 27) {
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
			const $itemRow = $(this).closest('.cart-item-row');
			const removeUrl = $(this).attr('href');

			$itemRow.css({
				'opacity': '0.35',
				'pointer-events': 'none',
				'transition': 'opacity 0.25s ease'
			});

			// Safety timeout: If wc_fragment_refresh doesn't swap DOM within 2.5s, restore row
			const safetyTimer = setTimeout(function() {
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
