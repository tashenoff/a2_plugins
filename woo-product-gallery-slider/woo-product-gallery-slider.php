<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Product Gallery Slider for Woocommerce 
 * Plugin URI:        https://wordpress.org/plugins/woo-product-gallery-slider/
 * Description:       This plugin will add a carousel in your Product Gallery.
 * Version:           2.0.3
 * Author:            codeixer
 * Author URI:        http://codeixer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpgs
 * Domain Path:       /languages
 * Tested up to: 5.2
 * WC requires at least: 3.4
 * WC tested up to: 3.7.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_action('plugins_loaded', 'wpgs_hooks');



function wpgs_hooks() {

    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');

    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);

    add_action('woocommerce_before_single_product_summary', 'wpgs_product_image', 20);
    add_action('woocommerce_product_thumbnails', 'wpgs_product_thumbnails', 20);
    add_action('wp_enqueue_scripts', 'wpgs_assets');


    add_filter('plugin_action_links_'. plugin_basename( __FILE__ ).'', 'wpgs_plugin_row_meta');
   

}


//---------------------------------------------------------------------
// Woocommerce Version Check
//---------------------------------------------------------------------

function wpgs_woocommerce_version_check( $version = '3.0' )  {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}
	}
	return false;
}


//---------------------------------------------------------------------
// Add setting API
//---------------------------------------------------------------------

require_once plugin_dir_path(__FILE__) . '/inc/class.settings-api.php';

//---------------------------------------------------------------------
// Plugin Options
//---------------------------------------------------------------------

require_once plugin_dir_path(__FILE__) . '/inc/options.php';

new wpgs_Settings_API();

function wpgs_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

//---------------------------------------------------------------------
// Plugin Functions
//---------------------------------------------------------------------

if (!function_exists('wpgs_product_image')) {
    /**
     * Output the product image before the single product summary.
     */
    function wpgs_product_image() {

        require_once plugin_dir_path(__FILE__) . '/inc/product-image.php';
    }
}
if (!function_exists('wpgs_product_thumbnails')) {
    /**
     * Output the product image before the single product summary.
     */
    function wpgs_product_thumbnails() {

        require_once plugin_dir_path(__FILE__) . '/inc/product-thumbnails.php';
    }
}

/*
Link in Plugin Meta
 */

function wpgs_plugin_row_meta($links) {
   
        $row_meta = array(
        	'settings' => '<a href="'.admin_url('admin.php?page=wpgs_options').'">Settings</a>',
            'docs' => '<a href="' . esc_url('https://1.envato.market/c/1814299/275988/4415?subId1=twist&subId2=wp_twist&subId3=https%3A%2F%2Fcodecanyon.net%2Fitem%2Ftwist-product-gallery-slidercarousel-plugin-for-woocommerce%2F14849108&u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Ftwist-product-gallery-slidercarousel-plugin-for-woocommerce%2F14849108') . '" target="_blank" aria-label="' . esc_attr__('Pro Version', 'wpgs') . '" style="color:green;font-weight:600;">' . esc_html__('Pro Version', 'wpgs') . '</a>',
        );

      return  array_merge($links, $row_meta);

   
}

if (!function_exists('wpgs_assets')) {
    //---------------------------------------------------------------------
    // enqueue scripts and styles For Product Gallery
    //---------------------------------------------------------------------

    function wpgs_assets() {

       
        wp_enqueue_script('slick-js', plugin_dir_url(__FILE__) . 'assets/public/js/slick.min.js', array('jquery'), '2.0', true);
        wp_enqueue_script('venobox-js', plugin_dir_url(__FILE__) . 'assets/public/js/venobox.min.js', array('jquery'), '2.0', true);

        wp_register_script('wpgsjs', plugin_dir_url(__FILE__) . 'assets/public/js/wpgs.js', array(), '2.0', true);
        $wpgsJquery = array(
         
            'wLightboxframewidth'=> wpgs_get_option( 'Lightboxframewidth', 'wpgs_settings', '600'),
            'wcaption'=> wpgs_get_option( 'caption', 'wpgs_settings', 'true'),
            
        );
        wp_localize_script('wpgsjs','wpgs_var',$wpgsJquery);
        
        // Enqueued script with localized data.
        wp_enqueue_script( 'wpgsjs' );

        $warrows = wpgs_get_option( 'navIcon', 'wpgs_settings', 'true');
        $wautoPlay = wpgs_get_option( 'autoPlay', 'wpgs_settings', 'false');
        $wslider_thubms = wpgs_get_option( 'thubms', 'wpgs_settings', '4');
            

        $wpgs_sliderJs = "jQuery(document).ready(function(){
jQuery('.wpgs-for').slick({slidesToShow:1,slidesToScroll:1,arrows:{$warrows},fade:!1,infinite:!1,autoplay:{$wautoPlay},nextArrow:'<i class=\"flaticon-right-arrow\"></i>',prevArrow:'<i class=\"flaticon-back\"></i>',asNavFor:'.wpgs-nav'});jQuery('.wpgs-nav').slick({slidesToShow:{$wslider_thubms},slidesToScroll:1,asNavFor:'.wpgs-for',dots:!1,infinite:!1,arrows:{$warrows},centerMode:!1,focusOnSelect:!0,responsive:[{breakpoint:767,settings:{slidesToShow:3,slidesToScroll:1,vertical:!1,draggable:!0,autoplay:!1,isMobile:!0,arrows:!1}},],})

      });";
      wp_add_inline_script( 'wpgsjs', $wpgs_sliderJs );


                wp_enqueue_style('slick-style', plugin_dir_url(__FILE__) . 'assets/public/css/slick.css', null,'2.0' );
        wp_enqueue_style('slick-theme', plugin_dir_url(__FILE__) . 'assets/public/css/slick-theme.css', null, '2.0');
        wp_enqueue_style('venobox-style', plugin_dir_url(__FILE__) . 'assets/public/css/venobox.css', null, '2.0');

        $color = wpgs_get_option( 'navColor', 'wpgs_settings', '#222');
        $custom_css = "
                .wpgs-for .slick-arrow,.wpgs-nav .slick-prev::before, .wpgs-nav .slick-next::before{
                        color: {$color};
            

                
                }";
        wp_add_inline_style( 'venobox-style', $custom_css );


        wp_enqueue_style('flaticon-wpgs', plugin_dir_url(__FILE__) . 'assets/public/css/font/flaticon.css', null, '2.0');
    }

}
