<?php
/*
	Plugin Name: Amazing WP e-Commerce
	Plugin URI: http://www.wordpress.org/extend/plugins
	Description: Creates some shorthand functions for WP e-Commerce built-in functions, enables list and grid views. Also enables and creates gallery by mimicing Gold Cart plugin.
	Version: 1.0.1
	Author: Risto Niinemets
	Author URI: http://risto.niinemets.eu
	License: GPL2
*/

/*  Copyright 2013  Risto Niinemets (email: risto.niinemets@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Is WP E-commerce active?
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active('wp-e-commerce/wp-shopping-cart.php')) :

/**
 * 	Enables WP E-Commerce Gold Cart Shopping Cart plugin. It allows you to show additional product images.
 *
 *	@since 				1.0
 *
 *	@param product_id 	Product ID - default is the current loop product ID
 *	@param force 		False means that the function will respect Store settings
 *
 *	@uses get_post_type()
 *	@uses is_single()
 *	@uses get_option()
 *	@uses get_product_id()
 *	@uses get_additional_images()
 *	@uses wp_get_attachment_image()
 *	@uses wp_get_attachment_src()
 *	
 *	@return void 		Echoes out the thumbnails of additional product images
**/
function gold_shpcrt_display_gallery ( $product_id = false, $force = false ) {
	if ( 'wpsc-product' === get_post_type() && !is_single() ) return;

	// Get options to display gallery
	$show_gallery 	= get_option('show_gallery');

	if( $show_gallery == "0" && $force !== true ) return;

	// Get product ID if unset
	$product_id 	= ( $product_id === false ) ? get_product_id() : $product_id;

	// Get images
	$images			= get_additional_images( $product_id );
	

	// If we found some images
	if ( $images ) {
		// Get single product view thumbnail image size
		$img_width 	= get_option('single_view_image_width');

		/*
			We found some pictures.
			Now create a container with width of single product view thumbnail image
		*/

		// Apply filters on style and classes
		$cont_style = 'width: '. $img_width .'px; text-align: center;';
		$cont_class = array( 'gallery_images' );

		$cont_style = apply_filters( 'amazing_gallery_container_style', $cont_style );
		$cont_class = apply_filters( 'amazing_gallery_container_class', $cont_class );

		$gallery	= '<div class="'. @implode( ' ', $cont_class ) .'" style="'. $cont_style .'">';

		// Get thumbnail (gallery) images size
		$gal_height	= get_option('wpsc_gallery_image_height');
		$gal_width 	= get_option('wpsc_gallery_image_width');

		// Loop through images
		foreach ( $images as $image ) {
			// Create arguments to fetch image
			// And apply filters on arguments
			$img_arguments		= array( "alt" => get_product_title(), "title" => get_product_title(), "class" => "product_thumbnail" );
			$img_arguments		= apply_filters( 'amazing_gallery_thumbnail', $img_arguments );

			// Get thumbnail
			$thumbnail			= wp_get_attachment_image( $image->ID, array( $gal_width, $gal_height ) , false, $img_arguments );

			// Get full size image URL
			$fullsize			= wp_get_attachment_url( $image->ID );

			// Create classes and apply filters on thumbnail link
			$thumb_class		= array( 'thickbox' );
			$thumb_class		= apply_filters( 'amazing_gallery_link_class', $thumb_class );
			$thumb_class 		= implode( ' ', $thumb_class );

			$gallery			.= '<a href="'. $fullsize .'" class="'. $thumb_class .'" rel="'. get_product_title() .'">'. $thumbnail .'</a> ';
		}

		$gallery 	.= '</div>';
	} else {
		$gallery	= "";
	}

	// Output images
	return $gallery;
}

/**
 *	Fetches all images related to the product, excluding featured image.
 *
 *	@since 1.0
 *
 *	@param product_id 	Product ID
 *
 *	@uses get_post_thumbnail_id()
 *	@uses get_children()
 *
 *	@return (array) Product images
**/
function get_additional_images( $product_id ) {
	// Get post thumbnail ID to exclude it
	$post_thumbnail	= get_post_thumbnail_id( $product_id );

	// Get images
	$images			= get_children(array(
		'post_parent'		=> $product_id,
		'post_type'			=> 'attachment',
		'exclude'			=> $post_thumbnail,
		'order'				=> 'ASC',
		'orderby'			=> 'ID',
		'post_mime_type'	=> 'image'));

	return $images;
}


/**
 *	Enables Product Grid View
 *
 *	@author 	Risto Niinemets
 *	@since 		1.0
 *
 *	@return (boolean) Always true
**/
function product_display_grid() {
	return true;
}

/**
 *	Enables Product List View
 *
 *	@author 	Risto Niinemets
 *	@since 		1.0
 *
 *	@return (boolean) Always true
**/
function product_display_list() {
	return true;
}

/**
 *	Prints out the product price
 *
 *	@since 		1.0
 *
 *	@see 		get_product_price();
 *
 *	@return void
**/
function the_product_price() {
	echo get_product_price();
}

/**
 *	Returns the product price
 *
 *	@since 			1.0
 *
 *	@uses 			wpsc_the_product_price();
 *
 *	@return string 	Product price
**/
function get_product_price() {
	return wpsc_the_product_price();
}

/**
 *	Outputs product thumbnail
 *
 *	@since 			1.0
 *
 *	@see 			get_product_thumbnail()
 *
 *	@return void
**/
function the_product_thumbnail() {
	echo get_product_thumbnail();
}

/**
 *	Returns the product thumbnail
 *
 *	@since 			1.0
 *
 *	@uses 			wpsc_the_product_thumbnail()
 *
 *	@return string 	Product thumbnail
**/
function get_product_thumbnail() {
	return wpsc_the_product_thumbnail();
}

/**
 *	Outputs product featured image
 *
 *	@since 			1.0
 *
 *	@see 			get_product_image()
 *
 *	@return void
**/
function the_product_image() {
	echo get_product_image();
}

/**
 *	Returns the product featured image
 *
 *	@since 			1.0
 *
 *	@uses 			wpsc_the_product_image()
 *
 *	@return string 	Product featured image
**/
function get_product_image() {
	return wpsc_the_product_image();
}


/**
 *	Outputs product title
 *
 *	@since 			1.0
 *
 *	@see 			get_product_title()
 *
 *	@return void
**/
function the_product_title() {
	echo get_product_title();
}

/**
 *	Returns the product title
 *
 *	@since 			1.0
 *
 *	@uses 			wpsc_the_product_title()
 *
 *	@return string 	Product title
**/
function get_product_title() {
	return wpsc_the_product_title();
}


/**
 *	Outputs the product ID
 *
 *	@since 			1.0
 *
 *	@see 			get_product_id()
 *
 *	@return void
**/
function the_product_id() {
	echo get_product_id();
}


/**
 *	Returns the product ID
 *
 *	@since 			1.0
 *
 *	@uses 			wpsc_the_product_id()
 *
 *	@return (int)	Product ID
**/
function get_product_id() {
	return (int)( wpsc_the_product_id() );
}

/**
 *	Returns current product (in loop) categories
 *
 *	@since 1.0
 *
 *	@uses wp_get_object_terms()
 *	@uses get_product_id()
 *
 *	@return (array)	Product categories
**/
function get_product_categories() {
	global $wp_query;

	$categories 	= wp_get_object_terms( get_product_id() , 'wpsc_product_category' );

	return $categories;
}

/**
 *	Returns wanted product category single slug or an array of all slugs, depending on
 *	user needs (param show_one)
 *
 *	@since 1.0
 *
 *	@uses get_product_categories()
 *	@global wpsc_query
 *
 *	@return (string/array)
**/
function get_product_category_slug( $show_one = false ) {
	global $wpsc_query;

	// Get product categories
	$categories 	= get_product_categories();

	// If product is associated with more than one category
	if ( count( $categories ) > 1 && isset( $wpsc_query->query_vars['wpsc_product_category'] ) ) {
		$query_data['category'] = $wpsc_query->query_vars['wpsc_product_category'];
	}
	elseif ( count( $categories ) > 0 && $show_one == true ) {
		$query_data['category'] = $categories[0]->slug;
	}
	elseif ( count( $categories ) > 0 && $show_one == false ) {
		$category_slugs	= array();

		foreach( $categories as $category ) {
			$category_slugs[]	= $category->slug;
		}

		return $category_slugs;
	}
	else {
		return false;
	}

	return $query_data['category'];
}

/**
 * 	Outputs all current product categories. Before output it will apply 'before_amazing_categories_list'
 *	filter on the string.
 *
 *	@since 1.0
 *
 *	@param separator 		How the categories will be separated?
 *
 *	@uses get_product_categories()
 *	@uses get_term_link()
 *	@uses apply_filters();
 *
 *	@return void
**/
function list_product_categories( $separator = ', ' ) {
	// Get product categories
	$categories 	= get_product_categories();
	$output_string	= "";
	$output_array 	= array();

	// If we have any at all
	if(!empty($categories)) {
		// Loop through categories and put them to array
		foreach($categories as $category) {
			$output_array[] 	= '<a href="'. get_term_link($category->slug, 'wpsc-product' ).'">'. $category->name .'</a>';
		}

		// Add all categories together
		$output_string 			= implode($separator, $output_array);
	}

	// Output
	echo apply_filters( 'before_amazing_categories_list', $output_string );
}

/**
 *	Registers and loads Amazing e-Commerce scripts and styles
 *
 *	@since 1.0
 *
 *	@see WP_Styles::add()
 *	@uses (object) $wp_styles
 *	@return void
**/
function load_amazing_ecommerce_assets() {
	$load_amazing_style		= true;
	$style_confirmation 	= apply_filters( 'load_amazing_style', $load_amazing_style );

	$load_amazing_script	= true;
	$script_confirmation 	= apply_filters( 'load_amazing_script', $load_amazing_script );

	// Register styles
	wp_register_style( 'amazing-ecommerce', plugins_url( 'amazing-ecommerce.css', __FILE__ ), false, '1.0', false );

	// Register scripts
	wp_register_script( 'amazing-ecommerce', plugins_url( 'amazing-ecommerce.min.js', __FILE__ ), 'jquery', '1.0', true );

	// If we still want to load style after filters?
	if( $style_confirmation == true ) {
		// Enqueue styles
		wp_enqueue_style( 'amazing-ecommerce' );
	}

	// If we still want to load script after filters?
	if( $script_confirmation == true ) {
		// Enqueue scripts
		wp_enqueue_script( 'amazing-ecommerce' );
	}
}

/**
 *	As we have enabled grid view for products and CSS for it is not configured (except clearing float
 *	after number of products), therefore we need to create columns ourselves. Also, e-Commerce does not
 *	provide us a style class or anything to find out how many user has selected per row, we need to do it ourselves.
 *
 *	@since 			1.0
 *	@uses 			get_option()
 *
 *	@return void
**/
function create_amazing_ecommerce_grid() {
	// We do not need to create anything if not a grid view
	if ( get_option('product_view') != 'grid' ) return;

	// How many products per row?
	$per_row	= get_option('grid_number_per_row');

	// What classes to add?
	$classes	= array( 'amazing-ecommerce', 'grid-' . $per_row );

	// Apply filters on classes
	$classes	= apply_filters( 'amazing_grid_classes', $classes );

	// Output a wrapper with number of products per row
	echo '<div class="'. implode( ' ', $classes ) .'">';
}

/**
 *	Finishes grid view container
 *
 *	@since 			1.0
 *	@uses 			get_option()
 *
 *	@see 			create_amazing_ecommerce_grid()
 *	@return void
**/
function finish_amazing_ecommerce_grid() {
	// We do not need to create anything if not a grid view
	if ( get_option('product_view') != 'grid' ) return;

	// Output a closing wrapper
	echo '</div>';
}

/**
 *	Outputs gallery (additional) images. Every image will have original thumbnail class
 *	and additional class "extraThumbnail".
 *
 *	@since 			1.0
 *	@return void
 *
 *	@uses wpsc_product_image()
 *	@uses get_option()
 *	@uses get_additional_images()
**/
function get_amazing_ecommerce_images() {
	// Get submitted product ID
	$product_id 	= $_REQUEST[ 'product_id' ];

	if ( !$product_id ) return;

	// Get submitted image class and apply filters on it
	$image_class	= apply_filters( 'amazing_extra_image_classes', $_REQUEST[ 'img_class' ] );

	// Get product images
	$images 		= get_additional_images( $product_id );

	// If there are any additional images
	if ( @count( $images ) > 0 ) {
		// Get images sizes
		$img_width 		= get_option( 'product_image_width' );
		$img_height 	= get_option( 'product_image_height' );

		// Loop through images
		foreach ( $images as $image ) {
			$thumbnail	= wpsc_product_image( $image->ID, $img_width, $img_height );

			// Output the image
			echo '<img src="'. $thumbnail . '" class="'. $image_class .' extraThumbnail" alt="" />';
		}
	}

	// Ajax actions need to die
	die();
}

// Some actions
add_action( 'wp_enqueue_scripts', 						'load_amazing_ecommerce_assets' );
add_action( 'wpsc_top_of_products_page', 				'create_amazing_ecommerce_grid' );
add_action( 'wpsc_theme_footer', 						'finish_amazing_ecommerce_grid' );
add_action( 'wp_ajax_extra_product_images',				'get_amazing_ecommerce_images' );
add_action( 'wp_ajax_nopriv_extra_product_images',		'get_amazing_ecommerce_images' );

// End of "Is WP E-commerce active?"
endif;
?>