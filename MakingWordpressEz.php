<?php
/*
Plugin Name: Making WordPress eZ
Plugin URI: http://www.makingwaves.pl
Description: Just a simple plugin that displays iframe :)
Author: Making Waves
Version: 0.1
*/

add_action( 'init', function(){

    new MakingWordpressEz();
} );

/**
 * Class MakingWordpressEz
 */
class MakingWordpressEz
{
    /**
     * @var string
     */
    const MENU_SLUG = 'making-wp-ez';

    /**
     * @var string
     */
    const PLUGIN_NAME = 'Making WordPress eZ';

    /**
     * @var string
     */
    const OPTION_GROUP = 'making-wp-ez-options';

    /**
     * @var string
     */
    const SECTION_ID = 'making-wp-ez-main-section';

    /**
     * @var string
     */
    const FIELD_URL_ID = 'url';

    /**
     * Constructor that sets up the hooks
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'adminMenuItem') );
        add_action( 'admin_init', array( $this, 'registerPluginOptions') );
    }

    /**
     * Add custom sub-menu into general options menu
     * Needs to be public, because it implements the hook.
     */
    public function adminMenuItem()
    {
        add_submenu_page(
            'options-general.php',
            self::PLUGIN_NAME,          // placed in <title> tag
            self::PLUGIN_NAME,          // visible in left menu
            'manage_options',
            self::MENU_SLUG,
            array( $this, 'displayAdminSettings')
        );
    }

    /**
     *  Render the form with settings
     */
    public function displayAdminSettings()
    {
        ?>
        <div class="wrap">
            <h2><?php print self::PLUGIN_NAME; ?></h2>
            <?php settings_errors(); ?>
            <form action="options.php" method="post">
                <?php settings_fields( self::OPTION_GROUP ); ?>
                <?php do_settings_sections( self::MENU_SLUG ); ?>
                <?php submit_button( __( 'Send', 'making-wp-ez' ) ) ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register options on the page
     */
    public function registerPluginOptions()
    {
        $options = get_option( self::OPTION_GROUP, array() );

        register_setting(
            self::OPTION_GROUP,
            self::OPTION_GROUP,                     // identifier that will be used as name for form fields
            array( $this, 'validateSettings' )
        );

        add_settings_section(
            self::SECTION_ID,
            __( 'Main settings', 'making-wp-ez' ),
            function() {},
            self::MENU_SLUG
        );

        add_settings_field(
            self::FIELD_URL_ID,
            __( 'URL to eZ', 'making-wp-ez' ),
            array( $this, 'renderInputField' ),
            self::MENU_SLUG,
            self::SECTION_ID,
            array(
                'name' => self::FIELD_URL_ID,
                'value' => isset( $options[self::FIELD_URL_ID] ) ? $options[self::FIELD_URL_ID] : ''
            )
        );
    }

    /**
     * Renders a text input field
     * @param array $args
     */
    public function renderInputField( array $args )
    {
        ?>
        <input type="text" name="<?php print self::OPTION_GROUP ?>[<?php print $args['name']; ?>]"
            value="<?php print $args['value'] ?>"/>
        <?php
    }

    /**
     * Validate settings and store it in database
     * @param array $data
     * @return array
     */
    public function validateSettings( array $data )
    {
        if ( !filter_var( $data[self::FIELD_URL_ID], FILTER_VALIDATE_URL ) ) {

            add_settings_error(
                self::MENU_SLUG,
                self::FIELD_URL_ID,
                __( 'Given URL address is not correct', 'making-wp-ez' )
            );

            $options = get_option( self::OPTION_GROUP, array() );
            $data[self::FIELD_URL_ID] = isset( $options[self::FIELD_URL_ID] ) ? $options[self::FIELD_URL_ID] : '';
        }

        return $data;
    }
}