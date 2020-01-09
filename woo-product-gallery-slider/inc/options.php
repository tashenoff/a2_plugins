<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if (!class_exists('wpgs_Settings_API')):
    class wpgs_Settings_API {

        private $settings_api;

        public function __construct() {
            $this->settings_api = new WeDevs_Settings_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
          

        }
     

        public function admin_init() {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
            /**
             * If not, return the standard settings
             **/

        }

        public function admin_menu() {
            // add_options_page( 'Settings API', 'Settings API', 'delete_posts', 'settings_api_test',  );
            add_submenu_page('woocommerce', 'Gallery Settings', 'Gallery Settings', 'delete_posts', 'wpgs_options', array($this, 'wpgs_plugin_page'));
        }

        public function get_settings_sections() {
            $sections = array(
                array(
                    'id'    => 'wpgs_settings',
                    'title' => __('Product Gallery Slider for WooCommerce Settings', 'wpgs'),
                ),

            );
            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        public function get_settings_fields() {
            $settings_fields = array(
                'wpgs_settings'   => array(
                    array(
                        'name'    => 'navIcon',
                        'label'   => __('Navigation Icons', 'wpgs'),
                        'desc'    => __('Show Navigation icons. Default: Yes', 'wpgs'),
                        'type'    => 'select',
                        'default' => 'true',
                        'options' => array(
                            'true' => 'Yes',
                            'false'  => 'No',
                        ),
                    ),
                    array(
                        'name'    => 'navColor',
                        'label'   => __('Icon Color', 'wpgs'),
                        'desc'    => __('', 'wpgs'),
                        'type'    => 'color',
                        'default' => '',
                    ),
                     array(
                        'name'              => 'thubms',
                        'label'             => __('Thumbnails to Show', 'wpgs'),
                        'desc'              => __('Default: 4', 'wpgs'),

                        'type'              => 'text',
                        'default'           => '4',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    array(
                        'name'    => 'autoPlay',
                        'label'   => __('Auto Play', 'wpgs'),
                        'desc'    => __('Default: No', 'wpgs'),
                        'type'    => 'select',
                        'default' => 'false',
                        'options' => array(
                            'true' => 'Yes',
                            'false'  => 'No',
                        ),
                    ),
 					
                  
                    array(
                        'name'              => 'Lightboxframewidth',
                        'label'             => __('Lightbox Frame Width ', 'wpgs'),
                        'desc'              => __('Default: 600 px', 'wpgs'),

                        'type'              => 'text',
                        'default'           => '600',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                     array(
                        'name'    => 'caption',
                        'label'   => __('Lightbox Caption', 'wpgs'),
                        'desc'    => __('Show Image Attributes as caption in this Lightbox', 'wpgs'),
                        'type'    => 'select',
                        'default' => 'false',
                        'options' => array(
                            'true' => 'Yes',
                            'false'  => 'No',
                        ),
                    ),
                  
                   
                    
                ),
            );

            return $settings_fields;
        }

        public function wpgs_plugin_page() {
            echo '<div class="wrap">';

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
?>

<div class="multistep-ads metabox-holder">    
    <h2>
    <img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"> Multistep Checkout for Woocommerce</h2>
    <p class="about-description">With <a href="https://wordpress.org/plugins/multistep-checkout-for-woocommerce-by-codeixer/">Multistep Checkout Plugin</a> the Buyers of your website will get a new step by step User Interface for checkout page.</p>
    <a target="_blank" href="https://wordpress.org/plugins/multistep-checkout-for-woocommerce-by-codeixer/" class="button button-primary">Install Now</a>
</div>

<div class="multistep-ads metabox-holder">    
    <h2>
    <img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"> CI WooCommerce Min Max Quantity & Step Control</h2>
    <p class="about-description">CI WooCommerce Min Max Quantity allows you to define the minimum and maximum allowable product quantities per product or all products of your shop.</p>
    <a target="_blank" href="https://wordpress.org/plugins/min-max-quantity-for-woocommerce/" class="button button-primary">Install Now</a>
</div>

<div class="twist_pro metabox-holder">
	
 <h2 style="text-align: left;">Get Product gallery slider for Woocommerce Pro ($34)</h2>
 <p>Pro version features are listed below:</p>
<ul>
 
    <li>Responsive Layout</li>
    <li>Navigation support</li>
    <li>Slider AutoPlay Options</li>
    <li>Gallery Layout [Vertical(left,right) and Horizontal Silder]</li>
    <li>On/Off LightBox Setting for Thumbnails images</li>
    <li>Working with Most of Premium themes</li>
    <li>Infinite Loop</li>
    <li>Mouse Dragging option</li>
    <li>RTL support</li>
    <li>Support Video for Gallery</li>
    <li>Thumbnails Hide option</li>
    <li>Full Lightbox Control [12 options]</li>
    <li>Support woocommerce default zoom option</li>
    <li>Shortcode ready [Easy to use with any custom product page builder]</li>
    <li>Support Elementor and Visual Composer page builder</li>
</ul>
 
 <a target="_blank" href="https://1.envato.market/c/1814299/275988/4415?subId1=twist&subId2=wp_twist&subId3=https%3A%2F%2Fcodecanyon.net%2Fitem%2Ftwist-product-gallery-slidercarousel-plugin-for-woocommerce%2F14849108&u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Ftwist-product-gallery-slidercarousel-plugin-for-woocommerce%2F14849108" class="button button-primary">Buy Now</a>


</div>


</div>

    <style>
    .multistep-ads{

    float: right;
    width: 435px;
    margin-bottom: 15px;

}
    .metabox-holder {
background: #fff;
    padding: 20px;
        padding-top: 20px;
    border-radius: 3px;

}
    .twist_oofer {

    width: 470px;
    height: 85px;

}
    .offer_txt {

    position: relative;
    top: -69px;
    left: 70px;

}
        .twist_oofer img{margin-top:10px;width:60px;}
    </style>
<?php
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        public function get_pages() {
            $pages         = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }

    }
endif;
