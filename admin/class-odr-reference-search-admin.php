<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://opendatarepository.org
 * @since      1.0.0
 *
 * @package    Odr_Reference_Search
 * @subpackage Odr_Reference_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Odr_Reference_Search
 * @subpackage Odr_Reference_Search/admin
 * @author     Nathan Stone <nate.stone@opendatarepository.org>
 */
class Odr_Reference_Search_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_init', array($this, 'odrRegisterSettings'));
        add_action('admin_menu', array($this, 'addPluginAdminMenu'), 9);

        // add_action( 'admin_enqueue_scripts', array($this, 'enqueue_styles') );
        // add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );


    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Odr_Reference_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Odr_Reference_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_register_style($this->plugin_name . '-style', plugin_dir_url(__FILE__) . 'css/odr-reference-search-admin.css', array(), $this->version, 'all');
        wp_register_style($this->plugin_name . '-coloris-style', plugin_dir_url(__FILE__) . 'css/coloris.min.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Odr_Reference_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Odr_Reference_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_register_script($this->plugin_name . '-js', plugin_dir_url(__FILE__) . 'js/odr-reference-search-admin.js', array(), $this->version, false);
        wp_register_script($this->plugin_name . '-coloris-js', plugin_dir_url(__FILE__) . 'js/coloris.min.js', array(), $this->version, false);

        add_action('admin_menu', 'odr_reference_search_add_settings_page');
    }

    public function addPluginAdminMenu()
    {
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page(
            $this->plugin_name,
            'ODR Reference Search',
            'administrator',
            $this->plugin_name,
            array($this, 'displayPluginAdminSettings'),
            // array($this, 'displayPluginAdminDashboard'),
            'dashicons-chart-area',
            26
        );

        //add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
        /*
        add_submenu_page(
            $this->plugin_name,
            'ODR Reference Search Settings',
            'Settings',
            'administrator',
            $this->plugin_name . '-settings',
            array($this, 'displayPluginAdminSettings')
        );
        */
    }

    public function displayPluginAdminDashboard()
    {
        // List all records IMA
        // https://beta.amcsd.net/odr/api/v1/search/database/0f59b751673686197f49f4e117e9/records/0/0.json
        require_once 'partials/' . $this->plugin_name . '-admin-display.php';
    }

    public function displayPluginAdminSettings()
    {

        wp_enqueue_style($this->plugin_name . '-style');
        wp_enqueue_style($this->plugin_name . '-coloris-style');
        wp_enqueue_script($this->plugin_name . '-js');
        wp_enqueue_script($this->plugin_name . '-coloris-js');

        // set this var to be used in the settings-display view
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        if (isset($_GET['error_message'])) {
            // add_action('admin_notices', array($this,'pluginNameSettingsMessages'));
            // do_action( 'admin_notices', $_GET['error_message'] );
        }
        require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
    }

    public function odr_reference_search_plugin_options_validate($input)
    {
        return $input;
    }


    function odrRegisterSettings()
    {
        register_setting(
            'odr_reference_search_plugin_options',
            'odr_reference_search_plugin_options',
            array($this, 'odr_reference_search_plugin_options_validate')
        );
        add_settings_section(
            'field_settings',
            'Field Settings',
            array($this, 'odr_reference_search_plugin_section_text'),
            $this->plugin_name
        );

        /**
         *
         * [
         *   odr-rruff-search-display datatype_id = "738"
         *   general_search = "gen"
         *   chemistry_incl = "7055"
         *   mineral_name = "7052"
         *   sample_id = "7069"
         *   redirect_url = "/odr/rruff_sample#/odr/search/display/2010"
         * ]
         *
         */
        add_settings_field(
            'odr_reference_search_datatype_id',
            'Datatype ID (numeric)',
            array($this, 'odr_reference_search_datatype_id'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_general_search',
            'General Search (gen)',
            array($this, 'odr_reference_search_general_search'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_author_names',
            'Reference Authors Field',
            array($this, 'odr_reference_search_author_names'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_title',
            'Reference Title Field',
            array($this, 'odr_reference_search_title'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_journal',
            'Reference Journal Field',
            array($this, 'odr_reference_search_journal'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_year',
            'Reference Year Field',
            array($this, 'odr_reference_search_year'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_mineral_name',
            'Mineral Name Field',
            array($this, 'odr_reference_search_mineral_name'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_redirect_url',
            'Redirect URL',
            array($this, 'odr_reference_search_redirect_url'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_cif',
            'Reference Search Theme ID',
            array($this, 'odr_reference_search_cif'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_sort_mineral_name_field',
            'Sort by Mineral Name Field ID',
            array($this, 'odr_reference_search_sort_mineral_name_field'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_sort_author_field',
            'Sort by Author Field ID',
            array($this, 'odr_reference_search_sort_author_field'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_sort_title_field',
            'Sort by Title Field ID',
            array($this, 'odr_reference_search_sort_title_field'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_sort_journal_field',
            'Sort by Journal Field ID',
            array($this, 'odr_reference_search_sort_journal_field'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_sort_year_field',
            'Sort by Year Field ID',
            array($this, 'odr_reference_search_sort_year_field'),
            $this->plugin_name,
            'field_settings'
        );
        add_settings_field(
            'odr_reference_search_help_text',
            'Search Help Text',
            array($this, 'odr_reference_search_help_text'),
            $this->plugin_name,
            'field_settings'
        );
    }

    function odr_reference_search_plugin_section_text()
    {
        echo '<p>Here you can set all the options for using the API</p>';
    }

    function odr_reference_search_datatype_id()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_datatype_id' name='odr_reference_search_plugin_options[datatype_id]' type='text' value='" . esc_attr($options['datatype_id']) . "' />";
    }

    function odr_reference_search_general_search()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_general_search' name='odr_reference_search_plugin_options[general_search]' type='text' value='" . esc_attr($options['general_search']) . "' />";
    }

    function odr_reference_search_author_names()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_author_names' name='odr_reference_search_plugin_options[author_names]' type='text' value='" . esc_attr($options['author_names']) . "' />";
    }

    function odr_reference_search_title()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_title' name='odr_reference_search_plugin_options[title]' type='text' value='" . esc_attr($options['title']) . "' />";
    }

    function odr_reference_search_journal()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_journal' name='odr_reference_search_plugin_options[journal]' type='text' value='" . esc_attr($options['journal']) . "' />";
    }

    function odr_reference_search_year()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_year' name='odr_reference_search_plugin_options[year]' type='text' value='" . esc_attr($options['year']) . "' />";
    }

    function odr_reference_search_mineral_name()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_mineral_name' name='odr_reference_search_plugin_options[mineral_name]' type='text' value='" . esc_attr($options['mineral_name']) . "' />";
    }

    function odr_reference_search_redirect_url()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_redirect_url' name='odr_reference_search_plugin_options[redirect_url]' type='text' value='" . esc_attr($options['redirect_url']) . "' />";
    }

    function odr_reference_search_cif()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_cif' name='odr_reference_search_plugin_options[cif]' type='text' value='" . esc_attr($options['cif']) . "' />";
    }

    function odr_reference_search_sort_mineral_name_field()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_sort_mineral_name_field' name='odr_reference_search_plugin_options[sort_mineral_name_field]' type='text' value='" . esc_attr($options['sort_mineral_name_field']) . "' />";
    }

    function odr_reference_search_sort_author_field()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_sort_author_field' name='odr_reference_search_plugin_options[sort_author_field]' type='text' value='" . esc_attr($options['sort_author_field']) . "' />";
    }

    function odr_reference_search_sort_title_field()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_sort_title_field' name='odr_reference_search_plugin_options[sort_title_field]' type='text' value='" . esc_attr($options['sort_title_field']) . "' />";
    }

    function odr_reference_search_sort_journal_field()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_sort_journal_field' name='odr_reference_search_plugin_options[sort_journal_field]' type='text' value='" . esc_attr($options['sort_journal_field']) . "' />";
    }

    function odr_reference_search_sort_year_field()
    {
        $options = get_option('odr_reference_search_plugin_options');
        echo "<input id='odr_reference_search_sort_year_field' name='odr_reference_search_plugin_options[sort_year_field]' type='text' value='" . esc_attr($options['sort_year_field']) . "' />";
    }

    function odr_reference_search_help_text()
    {
        $options = get_option('odr_reference_search_plugin_options');
        $content = isset($options['help_text']) ? $options['help_text'] : '';
        $editor_id = 'odr_reference_search_help_text';
        $settings = array(
            'textarea_name' => 'odr_reference_search_plugin_options[help_text]',
            'textarea_rows' => 10,
            'media_buttons' => true,
            'teeny' => false,
            'quicktags' => true,
        );
        wp_editor($content, $editor_id, $settings);
    }
}

