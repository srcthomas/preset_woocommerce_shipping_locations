<?php
/**
 * @package WooCommerce_Preset_Shipping_Locations
 * @version 0.1.1
 */
/*
Plugin Name: WooCommerce Preset Shipping Locations
Plugin URI: https://wordpress.org/plugins/woocommerce-preset-shipping-locations/
Description: Utilises shipping location custom post types to offer predefined shipping locations to a customer for easier shipping destination entry.
Author: Thomas Cowley
Version: 0.1.1
Author URI: https://github.com/srcthomas
Domain Path: /languages
Text Domain: woocommerce-preset-shipping-locations

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WooCommerce Preset Shipping Locations is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or any later version.

WooCommerce Preset Shipping Locations is distributed in the hope that it will be
useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WooCommerce Preset Shipping Locations. If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 */

/**
 * Check if the WooCommerce plugin is active.
 **/
if ( in_array( 'woocommerce/woocommerce.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' )	)
	) ) {

	/**
	 * Create a select element under billing details containing all shipping post titles.
	 * Once the user selects a predefined shipping destination, the script defined in the
	 * srct_checkout_update_shipping_address() function updates all shipping fields.
	 * @source https://github.com/srcthomas/preset_woocommerce_shipping_locations/
	 * @depends srct_checkout_update_shipping_address()
	 */
	add_filter( 'woocommerce_checkout_fields' , 'srct_display_default_shipping_locations' );
	function srct_display_default_shipping_locations( $fields ) {

		// Capture add all shipping post titles.
		$shipping_count = 1;
		query_posts( 'post_type=shipping_address' );
		if ( have_posts() ) {
			$shipping_address_titles['default'] = 'Select or clear...';
			while ( have_posts() ) {
				the_post();
				$shipping_address_titles[ get_the_id() ] = get_the_title();
			}
		}

		// Create a select element under billing with all shipping post titles.
		$fields['billing']['pick_up_locations'] = array(
			'type'		=> 'select',
			'options'	=> $shipping_address_titles,
			'label'		=> __( 'Donate by selecting one of our predefined shipping locations', 'woocommerce' ),
			'class'		=> array( 'form-row-wide' ),
			'clear'		=> true
		);

		return $fields;
	}

	/**
	 * Generate a script added to the head which updates shipping fields when one of the
	 * default shipping destinations are chosen.
	 * @source https://github.com/srcthomas/preset_woocommerce_shipping_locations/
	 */
	add_action( 'wp_head', 'srct_checkout_update_shipping_address', 10);
	function srct_checkout_update_shipping_address() {

		// $shipping_addresses[post_id][field_name] = field_value;
		$shipping_addresses[] = '';

		// Capture then JSONify all shipping details
		query_posts( 'post_type=shipping_address' );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				$post_id = get_the_id();

				// Store shipping addresses in an array referenced by post number.
				foreach([
					'first_name',
					'last_name',
					'company',
					'address',
					'address_extra',
					'town_city',
					'region',
					'postcode',
					'order_notes'
				] as $field) {
					$shipping_addresses[$post_id][$field] = get_post_meta( $post_id, $field );
				}
			}

			// JSONify all shipping addresses for JS to update shipping fields.
			$shipping_addresses = json_encode( $shipping_addresses );
		}
		wp_reset_query();
		?>

		<script type="text/javascript">

			jQuery((function($){

				// regionsMap[name] = code;
				var regionsMap = [];

				/*
				 * Store regions to be used to update region select element.
				 */
				$(document).ready(function mapRegions(){
					$.each($('select#shipping_state option'), function(index, region){
						regionsMap[$(region).text()] = $(region).val();
					});
				});

				/*
				 * Select or unselect 'Ship to different address' checkbox.
				 */
				function toggleShipToDiffAddr (is_diff_addr) {
					$('input#ship-to-different-address-checkbox').prop('checked', is_diff_addr);
				}

				/*
				 * Cycle through all shipping fields and either enable or disable.
				 * @isDisabled true to enable and false to display shipping fields access.
				 */
				function toggleShippingFieldAccess (is_disabled) {
					[
						$(".shipping_address select[id^='shipping_']"),
						$(".shipping_address input[id^='shipping_']"),
						$("textarea[id='order_comments']"),
					]
					.forEach(function(obj) {
						obj.prop("disabled", !is_disabled);
					});
				}

				/*
				 * Set all shipping fields to either what has been passed or an empty string.
				 * Pass no arguments to unset all fields and uncheck 'ship to different address'.
				 */
				function updateShippingFields (
					firstName,
					lastName,
					company,
					address,
					addressExtra,
					townCity,
					region,
					postcode,
					orderNotes ) {

					var addr = {
						'input#shipping_first_name'	: firstName,
						'input#shipping_last_name'	: lastName,
						'input#shipping_company'	: company,
						'input#shipping_address_1'	: address,
						'input#shipping_address_2'	: addressExtra,
						'input#shipping_city'		: townCity,
						'select#shipping_state'		: regionsMap[region],
						'input#shipping_postcode'	: postcode,
						'textarea#order_comments'	: orderNotes
					};

					for (var selector in addr) {
						$(selector).val(
							addr[selector] != 'undefined' ?
							addr[selector] :
							''
						);
					}

					toggleShipToDiffAddr(arguments.length);
				}

				/*
				 * Update shipping fields when pickup locations upddated.
				 */
				$('select#pick_up_locations').live('change', function() {

					var shipping_addresses 	= <?php echo $shipping_addresses; ?>;
					var location			= $('select#pick_up_locations').attr('value');
					var addr				= shipping_addresses[location];

					if ('default' != location && 'undefined != 'location) {
						// update fields to preset shipping fields
						updateShippingFields(
							addr.first_name,
							addr.last_name,
							addr.company,
							addr.address,
							addr.address_extra,
							addr.town_city,
							addr.region,
							addr.postcode,
							addr.order_notes
						);
						toggleShippingFieldAccess(false);
					} else {
						// Reset shipping fields to blank
						updateShippingFields();
						toggleShippingFieldAccess(true);
					}
				});
			})(jQuery));
		</script>
		<?php
	}
}
