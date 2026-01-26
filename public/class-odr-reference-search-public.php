<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://opendatarepository.org
 * @since      1.0.0
 *
 * @package    Odr_Reference_Search
 * @subpackage Odr_Reference_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the public-facing side
 * including shortcode registration and asset enqueueing.
 *
 * @package    Odr_Reference_Search
 * @subpackage Odr_Reference_Search/public
 * @author     Nathan Stone <nate.stone@opendatarepository.org>
 */
class Odr_Reference_Search_Public
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

        add_shortcode('odr-reference-search-display', array($this, 'odr_reference_search_display'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/odr-reference-search-public.css', array(), $this->version, 'all');
        wp_register_style($this->plugin_name . '-modal', plugin_dir_url(__FILE__) . 'css/jquery.modal.0.9.1.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/odr-reference-search-public.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . '-modal', plugin_dir_url(__FILE__) . 'js/jquery.modal.0.9.1.js', array('jquery'), $this->version, false);
    }

    /**
     * Shortcode handler for [odr-reference-search-display]
     *
     * @since    1.0.0
     */
    public function odr_reference_search_display($atts)
    {
        // Enqueue styles and scripts
        wp_enqueue_style($this->plugin_name);
        wp_enqueue_style($this->plugin_name . '-modal');
        wp_enqueue_script($this->plugin_name);
        wp_enqueue_script($this->plugin_name . '-modal');

        // Get plugin options from admin settings
        $options = get_option('odr_reference_search_plugin_options');

        // Build the variables array for the display template
        $odr_reference_search_vars = array(
            // Core fields
            'datatype_id' => isset($options['datatype_id']) ? $options['datatype_id'] : '',
            'general_search' => isset($options['general_search']) ? $options['general_search'] : '',
            'mineral_name' => isset($options['mineral_name']) ? $options['mineral_name'] : '',
            'redirect_url' => isset($options['redirect_url']) ? $options['redirect_url'] : '',
            'cif' => isset($options['cif']) ? $options['cif'] : '',

            // Reference fields
            'reference_author' => isset($options['author_names']) ? $options['author_names'] : '',
            'reference_title' => isset($options['title']) ? $options['title'] : '',
            'reference_journal' => isset($options['journal']) ? $options['journal'] : '',
            'reference_year' => isset($options['year']) ? $options['year'] : '',

            // Sort fields
            'sort_mineral_name_field' => isset($options['sort_mineral_name_field']) ? $options['sort_mineral_name_field'] : '',
            'sort_author_field' => isset($options['sort_author_field']) ? $options['sort_author_field'] : '',
            'sort_title_field' => isset($options['sort_title_field']) ? $options['sort_title_field'] : '',
            'sort_journal_field' => isset($options['sort_journal_field']) ? $options['sort_journal_field'] : '',
            'sort_year_field' => isset($options['sort_year_field']) ? $options['sort_year_field'] : '',

            // Help text
            'help_text' => isset($options['help_text']) ? $options['help_text'] : '',
        );

        // Start output buffering
        ob_start();

        // Include the display template
        include plugin_dir_path(__FILE__) . 'partials/odr-reference-search-public-display.php';

        // Return the buffered content
        return ob_get_clean();
    }
}
