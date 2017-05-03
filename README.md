# preset_woocommerce_shipping_locations

Two functions to add a select element to the bottom of the WooCommerce billing field which pulls titles for all shipping_address posts then populates the shipping form with meta data defined in the admin area.

I've used these functions in combination with two plugins:
- 'Custom Post Type UI' plugin to create the shipping_address post type:
  - https://github.com/WebDevStudios/custom-post-type-ui/
- 'Advanced Custom Fields' to create the meta data form elements to store and update data on a single Shipping Address page:
  - https://www.advancedcustomfields.com/

Update the query_posts function to call what ever your post type is called.
Update the meta fields to what ever you call them.
