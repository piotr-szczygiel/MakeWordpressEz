<?php
/**
 * @package MakeWordpressEz
 * @version 1.6
 */
/*
Plugin Name: MakeWordpressEz
Description: Lorem ipsum dolor sit amet.
Author: Wojciech Krzysztofik
Version: 1.0
*/

namespace MakingWaves;

add_action('init', function() {
    /*
    if ( class_exists( 'MakeWordpressEz' ) ) {
        $makeWordpressEz = new MakeWordpressEz();
        $makeWordpressEz->start();
    }
    add_action('admin_menu', function() {
        add_submenu_page('options-general.php', 'MakeWordpressEz', 'MakeWordpressEz', 'manage_options', 'wpautop-control-menu', 'wpautop_control_options');
    });
    */

    new MakingWordpressEz();
});

class MakingWordpressEz {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
        add_action( 'admin_init', array( $this, 'register_my_setting' ) );
    }

    public function add_menu_item() {
        add_submenu_page('options-general.php', 'MakeWordpressEz', 'MakeWordpressEz', 'manage_options', 'wpautop-control-menu', array($this, 'page_content') );
    }

    public function page_content() {
        ?>
        <h2>Making Wordpress Ez</h2>
        <p>Lorem ipsum dolor sit amet.</p>
        <div class="wrap">
            <?php settings_errors(); ?>
            <?php settings_fields( 'wp_ez' ); ?>
            <?php do_settings_sections( 'hooptsh' ); ?>
            <form action="options.php" method="post">
                <input type="submit" value="Ok" />
            </form>
        </div>
        <?php
    }

    public function register_my_setting() {
        register_setting(
            'wp_ez',
            'wp_ez',
            array(
                $this, 'validate_settings'
            ));

        add_settings_section(
            'wp_ez_section',
            'Main section',
            function() {},
            'hooptsh'
        );

        add_settings_field(
            'url',
            'Podaj URLa do eZ',
            array(
                $this,
                'render_input_field'
            ),
            'hooptsh',
            'wp_ez_section'
        );
    }

    public function validate_settings() {

    }

    public function render_input_field() {
        echo '<input type="text" name="url" placeholder="URL" />';
    }
}