=== Plugin Name ===
Contributors: RistoNiinemets
Tags: wp e-commerce, wpec, wpsc
Requires at least: 3.0.1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=XE45WDB3XEN6J&lc=EE&item_name=WordPress%20plugins&item_number=wordpress%2dplugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tested up to: 3.5.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable some of the WP e-Commerce disabled features and simplify your development.

== Description ==

Whilst developing a theme for WP e-Commerce, have you ever thought why there's a template file for a list and a grid view, even though it is disabled in Store settings (Settings - Store - Presentation)?

This plugin will enable Grid and List views and will let you to customize the grid view as it is presented in Store settings. Those settings are: Products per row, Show only images, Display Variations, Display Description, Display "Add to Cart" Button, Display "More Detail" Button.

If you have ever looked into wpsc-single_product.php file then you probably have noticed the Gold Cart plugin function in there and it does nothing. Well, I have added functionality for it. With this plugin installed, you will see additional product thumbails under the main thumbnail (screenshot 1).

Also it comes with a fancy extra feature: Extra Thumbnails. If your product has more than the featured image, then you can go to products page (catalog) and hover over the product thumbnail. It will load the extra images and start a slideshow.

== Installation ==

1. Upload `amazing-ecommerce.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use WP E-Commerce simplified functions as you need and enjoy extra features

== Frequently Asked Questions ==

= What functions are there to use? =

* (get|the)_product_price()
* (get|the)_product_description()
* (get|the)_product_thumbnail()
* (get|the)_product_image()
* (get|the)_product_title()
* (get|the)_product_id()
* get_product_categories()
* get_product_category_slug()
* list_product_categories()
* get_additional_images()

= What's the difference between "get" and "the" prefix functions? =

"Get" function return the wanted value, whereas "the" function echoes it.

= Are there any filters or actions to use? =

Yes, there are many filters to affect the work of this plugin.

Filters:

* `amazing_gallery_container_style` - (string) Gallery thumbnail images container style tag
* `amazing_gallery_container_class` - (array) Gallery thumbnail images container classes
* `amazing_gallery_thumbnail` - (array) Gallery thumbnail images arguments (title, alt tag, class)
* `amazing_gallery_link_class` - (array) Gallery thumbnail image link classes
* `before_amazing_categories_list` - (string) Categories
* `load_amazing_style` - (boolean) To prevent plugin`s stylesheet to be loaded, you have to return false.
* `load_amazing_script` - (boolean) To prevent plugin`s javascript to be loaded, you have to return false. By using this filter and returning false, you can disable the Extra Thumbnails feature of this plugin.
* `amazing_grid_classes` - (array) Grid wrapper div classes
* `amazing_extra_image_classes` - (string) Product extra thumbnail classes

= How to disable Extra Thumbnails feature? =

There is a nice way to do it:

	add_filter( 'load_amazing_script', 'disable_amazing_ecommerce_script' );

	function disable_amazing_ecommerce_script( $enable ) {
		return false;
	}

== Screenshots ==

1. Gallery (additional images) thumbnails under the main thumbnail
2. Gallery thumbnail settings
3. Extra Thumbnails being loaded in Grid View.

== Changelog ==
= 1.0.1 =
Supplied some more information of the plugin, it's features and filters.

== Upgrade Notice ==
= 1.0.1 =
Now the readme is well documentated and you can have more information without exploring the plugin itself.