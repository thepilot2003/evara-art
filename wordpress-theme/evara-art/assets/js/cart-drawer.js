/**
 * Evara Art - AJAX Cart Drawer Interactions
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		const $drawer = $('#evara-cart-drawer');

		// Open Drawer
		function openDrawer() {
			$drawer.addClass('is-open').attr('aria-hidden', 'false');
			$('body').css('overflow', 'hidden');
		}

		// Close Drawer
		function closeDrawer() {
			$drawer.removeClass('is-open').attr('aria-hidden', 'true');
			$('body').css('overflow', '');
		}

		$('#evara-cart-open').on('click', function(e) {
			e.preventDefault();
			openDrawer();
		});

		$('#evara-cart-close, #evara-cart-close-overlay').on('click', function() {
			closeDrawer();
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

			$itemRow.css('opacity', '0.4').css('pointer-events', 'none');

			$.ajax({
				type: 'GET',
				url: removeUrl,
				dataType: 'html',
				success: function(response) {
					// Trigger WooCommerce fragment refresh
					$(document.body).trigger('wc_fragment_refresh');
				},
				error: function() {
					$itemRow.css('opacity', '1').css('pointer-events', '');
				}
			});
		});

	});

})(jQuery);
