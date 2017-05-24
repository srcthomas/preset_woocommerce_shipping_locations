# WooCommerce Preset Shipping Locations:

WooCommerce Preset Shipping Locations is a plugin used to help display preset shipping locations.

As this plugin facilitates only the pulling and displaying of preset shipping location data, you'll need to use this plugin combined with two other plugins:
- 'Custom Post Type UI' plugin to create the shipping_address post type:
  - https://github.com/WebDevStudios/custom-post-type-ui/
- 'Advanced Custom Fields' to create the meta data form elements to store and update data on a single Shipping Address page:
  - https://www.advancedcustomfields.com/

If enough interest arose, there will be further development to add a shipping post type that facilitates adding as much or as little data to that post type as is required.

Likewise, further development to facilitate plugin settings to define the query_post value could ensure given enough interest.

If you just want to take and use the code for your own post type, do the following:
- Update the query_posts function to call what ever your post type is called.
- Update the meta fields to what ever you call them.

If you have any ideas or suggestions, please fire them through or submit a pull request.
