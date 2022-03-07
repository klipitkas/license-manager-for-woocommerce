<?php

if ( ! function_exists( 'lmw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function lmw_fs() {
        global $lmw_fs;

        if ( ! isset( $lmw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $lmw_fs = fs_dynamic_init( array(
                'id'                  => '9151',
                'slug'                => 'lmwoo',
                'type'                => 'plugin',
                'public_key'          => 'pk_1ddfcd402999fb8574c24d17a716f',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'lmwoo',
                    'first-path'     => 'admin.php?page=lmfwc_settings&tab=general',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $lmw_fs;
    }

    // Init Freemius.
    lmw_fs();
    // Signal that SDK was initiated.
    do_action( 'lmw_fs_loaded' );
}