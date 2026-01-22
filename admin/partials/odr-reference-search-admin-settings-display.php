<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://opendatarepository.org
 * @since      1.0.0
 *
 * @package    Odr_Reference_Search
 * @subpackage Odr_Reference_Search/admin/partials
 */
/**
 *
 * [
 *   odr-reference-search-display datatype_id = "738"
 *   general_search = "gen"
 *   chemistry_incl = "7055"
 *   mineral_name = "7052"
 *   sample_id = "7069"
 *   redirect_url = "/odr/reference_sample#/odr/search/display/2010"
 * ]
 *
 */

// Need to load plugin CSS

?>
<h2>ODR Search Plugin Settings</h2>
<form action="options.php" method="post">
    <?php
    settings_fields( 'odr_reference_search_plugin_options' );
    do_settings_sections( $this->plugin_name ); ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
</form>