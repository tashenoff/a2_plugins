<?php
if ( ! function_exists('berocket_aapf_bodycommerce_archive_module_args') ) {
    add_filter('db_archive_module_args', 'berocket_aapf_bodycommerce_archive_module_args');
    function berocket_aapf_bodycommerce_archive_module_args( $new_args ) {
        if ( function_exists('br_aapf_args_parser') ) {
            //global $wp_query, $wp_rewrite;
            $br_options = apply_filters( 'berocket_aapf_listener_br_options', BeRocket_AAPF::get_aapf_option() );

            $add_to_args = array();
            if ( ! empty($_POST['limits']) && is_array($_POST['limits']) ) {
                foreach ( $_POST['limits'] as $post_key => $t ) {
                    if( $t[0] == '_date' ) {
                        $from = $t[1];
                        $to = $t[2];
                        $from = substr($from, 0, 2).'/'.substr($from, 2, 2).'/'.substr($from, 4, 4);
                        $to = substr($to, 0, 2).'/'.substr($to, 2, 2).'/'.substr($to, 4, 4);
                        $from = date('Y-m-d 00:00:00', strtotime($from));
                        $to = date('Y-m-d 23:59:59', strtotime($to));
                        $add_to_args['date_query'] = array(
                            'after' => $from,
                            'before' => $to,
                        );
                        unset($_POST['limits'][$post_key]);
                    }
                }
            }
            $BeRocket_AAPF = BeRocket_AAPF::getInstance();
            if ( ! empty($_POST['terms']) && is_array($_POST['terms']) ) {
                $stop_sale = false;
                $check_sale = $check_notsale = 0;
                foreach ( $_POST['terms'] as $post_key => $t ) {
                    if( $t[0] == 'price' ) {
                        if( preg_match( "~\*~", $t[1] ) ) {
                            if( ! isset( $_POST['price_ranges'] ) ) {
                                $_POST['price_ranges'] = array();
                            }
                            $_POST['price_ranges'][] = $t[1];
                            unset( $_POST['terms'][$post_key] );
                        }
                    } elseif( $t[0] == '_sale' ) {
                        // if both used do nothing
                        if ( $t[0] == '_sale' and $t[3] == 'sale' ) {
                            $check_sale++;
                        }
                        if ( $t[0] == '_sale' and $t[3] == 'notsale' ) {
                            $check_notsale++;
                        }
                        unset($_POST['terms'][$post_key]);
                    } elseif( $t[0] == '_rating' ) {
                        $_POST['terms'][$post_key][0] = 'product_visibility';
                    }
                }
                if ( ! empty($br_options['slug_urls']) ) {
                    foreach ( $_POST['terms'] as $post_key => $t ) {
                        if( $t[0] == '_stock_status' ) {
                            $_stock_status = array( 'instock' => 1, 'outofstock' => 2);
                            $_POST['terms'][$post_key][1] = (isset($_stock_status[$t[1]]) ? $_stock_status[$t[1]] : $_stock_status['instock']);
                        } else {
                            $t[1] = get_term_by( 'slug', $t[3], $t[0] );
                            $t[1] = $t[1]->term_id;
                            $_POST['terms'][$post_key] = $t;
                        }
                    }
                }

                if ( ! ($check_sale and $check_notsale) ) {
                    if ( $check_sale ) {
                        $add_to_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                    } elseif( $check_notsale ) {
                        $add_to_args['post__in'] = array_merge( array( 0 ), $BeRocket_AAPF->wc_get_product_ids_not_on_sale() );
                    }
                }
            }

            $woocommerce_hide_out_of_stock_items = BeRocket_AAPF_Widget::woocommerce_hide_out_of_stock_items();

            $meta_query = $BeRocket_AAPF->remove_out_of_stock( array() , true, $woocommerce_hide_out_of_stock_items != 'yes' );

            $args = apply_filters( 'berocket_aapf_listener_wp_query_args', array() );
            foreach($add_to_args as $arg_name => $add_arg) {
                $args[$arg_name] = $add_arg;
            }
            if( ! empty($_POST['limits']) ) {
                $args = apply_filters('berocket_aapf_convert_limits_to_tax_query', $args, $_POST['limits']);
            }
            if( ! isset($args['post__in']) ) {
                $args['post__in'] = array();
            }
            if( $woocommerce_hide_out_of_stock_items == 'yes' ) {
                $args['post__in'] = $BeRocket_AAPF->remove_out_of_stock( $args['post__in'] );
            }
            if( ! br_woocommerce_version_check() ) {
                $args['post__in'] = $BeRocket_AAPF->remove_hidden( $args['post__in'] );
            }
            $args['meta_query'] = $meta_query;

            if( ! empty($_POST['limits']) ) {
                $args = apply_filters('berocket_aapf_convert_limits_to_tax_query', $args, $_POST['limits']);
            }
            if( isset($_POST['price']) && is_array($_POST['price']) ) {
                $_POST['price'] = apply_filters('berocket_min_max_filter', $_POST['price']);
            }
            if ( ! empty( $_POST['price'] ) ) {
                $min = isset( $_POST['price'][0] ) ? floatval( $_POST['price'][0] ) : 0;
                $max = isset( $_POST['price'][1] ) ? floatval( $_POST['price'][1] ) : 9999999999;

                $args['meta_query'][] = array(
                    'key'          => apply_filters('berocket_price_filter_meta_key', '_price', 'widget_2847'),
                    'value'        => array( $min, $max ),
                    'compare'      => 'BETWEEN',
                    'type'         => 'DECIMAL',
                    'price_filter' => true,
                );
            }
            /*$args['post_status']    = 'publish';
            if ( is_user_logged_in() ) {
                $args['post_status'] .= '|private';
            }*/
            $args['post_type']      = 'product';
            //$default_posts_per_page = get_option( 'posts_per_page' );
            //$args['posts_per_page'] = apply_filters( 'loop_shop_per_page', $default_posts_per_page );

            if ( ! empty($_POST['price_ranges']) && is_array($_POST['price_ranges']) ) {
                $price_range_query = array( 'relation' => 'OR' );
                foreach ( $_POST['price_ranges'] as $range ) {
                    $range = explode( '*', $range );
                    $price_range_query[] = array( 'key' => apply_filters('berocket_price_filter_meta_key', '_price', 'widget_2867'), 'compare' => 'BETWEEN', 'type' => 'NUMERIC', 'value' => array( ($range[0] - 1), $range[1] ) );
                }
                $args['meta_query'][] = $price_range_query;
            }

            if( isset($_POST['product_taxonomy']) && $_POST['product_taxonomy'] != '-1' && strpos( $_POST['product_taxonomy'], '|' ) !== FALSE ) {
                $product_taxonomy = explode( '|', $_POST['product_taxonomy'] );
                $args['taxonomy'] = $product_taxonomy[0];
                $args['term'] = $product_taxonomy[1];
            }
            if( isset($_POST['s']) && strlen($_POST['s']) > 0 ) {
                $args['s'] = $_POST['s'];
            }

            /*if( function_exists('wc_get_product_visibility_term_ids') ) {
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();

                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => array($product_visibility_term_ids['exclude-from-catalog']),
                    'operator' => 'NOT IN'
                );
            }*/
            /*
            $args = array_merge($args, WC()->query->get_catalog_ordering_args());

            $wp_query = new WP_Query( $args );

            // here we get max products to know if current page is not too big
            $is_using_permalinks = $wp_rewrite->using_permalinks();
            if ( empty( $_POST['location'] ) and ! empty ( $_GET['location'] ) ) {
                $_POST['location'] = $_GET['location'];
            } else {
                $_POST['location'] = 0;
            }
            if ( $is_using_permalinks and preg_match( "~/page/([0-9]+)~", $_POST['location'], $mathces ) or preg_match( "~paged?=([0-9]+)~", $_POST['location'], $mathces ) ) {
                $args['paged'] = min( $mathces[1], $wp_query->max_num_pages );
            }
            */

            $new_args = $new_args + $args;
        }

        return $new_args;
    }
}
