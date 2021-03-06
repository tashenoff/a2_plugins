<?php

namespace DgoraWcas\Conflicts;

/**
 * Class Solver
 *
 * Solves conflicts with other plugins
 */
class Solver
{

    public function __construct()
    {
        $this->solveSearchWPWooCommerceIntegration();

        $this->solveDiviWithBuilderWC();
    }

    /**
     * Solves conflict with SearchWP WooCommerce Integration by SearchWP, LLC
     * Tested version: plugin SearchWP WooCommerce Integration by SearchWP v1.2.1
     *
     * Reason: Empty search page (no results). The plugin removes query_vars['s']
     *
     * @return void
     */
    public function solveSearchWPWooCommerceIntegration()
    {
        if (isset($_GET['dgwt_wcas'])) {
            add_filter('searchwp_woocommerce_forced', '__return_false', PHP_INT_MAX);
            add_filter('searchwp_short_circuit', '__return_true', PHP_INT_MAX);
        }

    }

    /**
     * Solves conflict with the DIVI builder
     * Tested version: theme DIVI v3.19.18
     *
     * Reason: WP Query for search results was overwritten ih the hook pre_get_posts
     */
    public function solveDiviWithBuilderWC()
    {
        add_action('init', function () {
            if (isset($_GET['dgwt_wcas'])) {
                remove_action('pre_get_posts', 'et_builder_wc_pre_get_posts', 10);
            }
        });
    }

}