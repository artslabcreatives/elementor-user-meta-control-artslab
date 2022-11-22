<?php
/**
 * Plugin Name: Elementor User Meta Control
 * Description: Adds a custom user metal control to the default elmentor form
 * Plugin URI:  https://plugins.artslabcreatives.com
 * Version:     1.0.1
 * Author:      Artslab Creatives
 * Author URI:  https://plugins.artslabcreatives.com
 * Text Domain: elementor-user-meta-control-artslab
 *
 * Elementor tested up to: 3.7.0
 * Elementor Pro tested up to: 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register `credit-card-number` field-type to Elementor form widget.
 *
 * @since 1.0.1
 * @param \ElementorPro\Modules\Forms\Registrars\Form_Fields_Registrar $form_fields_registrar
 * @return void
 */
function add_user_meta_field( $form_fields_registrar ) {

    require_once( __DIR__ . '/form-fields/elementor-user-meta-field.php' );

    $form_fields_registrar->register( new \Elementor_User_Meta_Field() );

}
add_action( 'elementor_pro/forms/fields/register', 'add_user_meta_field' );

require_once( __DIR__ . '/updater.php' );
new ALCEUMUpdater();