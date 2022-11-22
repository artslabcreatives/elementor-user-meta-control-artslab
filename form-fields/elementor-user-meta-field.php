<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Form Field - Elementor User Meta
 *
 * Add a new "Elementor User Meta" field to Elementor form widget.
 *
 * @since 1.0.0
 */
class Elementor_User_Meta_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve user meta key field unique ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field type.
	 */
	public function get_type() {
		return 'credit-card-number';
	}

	/**
	 * Get field name.
	 *
	 * Retrieve user meta key field label.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field name.
	 */
	public function get_name() {
		return esc_html__( 'Elementor User Meta', 'elementor-form-user-meta-field' );
	}

	/**
	 * Render field output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param mixed $item
	 * @param mixed $item_index
	 * @param mixed $form
	 * @return void
	 */
	public function render( $item, $item_index, $form ) {
		$form_id = $form->get_id();

		$user = get_user_meta(get_current_user_id(), $item['elementor-usermeta-key']);

		$form->add_render_attribute(
			'input' . $item_index,
			[
				'class' => 'elementor-field-textual',
				'for' => $form_id . $item_index,
				'type' => 'hidden',
				'inputmode' => 'numeric',
				'maxlength' => '19',
				'placeholder' => $user,
				'value' => $user,
			]
		);

		echo '<input ' . $form->get_render_attribute_string( 'input' . $item_index ) . '>';
	}

	/**
	 * Field validation.
	 *
	 * Validate user meta key field value to ensure it complies to certain rules.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 * @return void
	 */
	public function validation( $field, $record, $ajax_handler ) {
		if ( empty( $field['value'] ) ) {
			return;
		}
	}

	/**
	 * Update form widget controls.
	 *
	 * Add input fields to allow the user to customize the user meta key field.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widget_Base $widget The form widget instance.
	 * @return void
	 */
	public function update_controls( $widget ) {
		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$usermetasraw = get_user_meta(get_current_user_id());
		$usermetaskey = array();
		foreach ($usermetasraw as $key => $usermeta) {
			// code...
			$usermetaskey[$key] = $key;
		}

		$field_controls = [
			'elementor-usermeta-key' => [
				'name' => 'elementor-usermeta-key',
				'label' => esc_html__( 'Card Placeholder', 'elementor-form-user-meta-field' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'xxxx xxxx xxxx xxxx',
				'dynamic' => [
					'active' => true,
				],
				'options' => $usermetaskey,
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab'          => 'content',
				'inner_tab'    => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );

		$widget->update_control( 'form_fields', $control_data );
	}

	/**
	 * Field constructor.
	 *
	 * Used to add a script to the Elementor editor preview.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'elementor/preview/init', [ $this, 'editor_preview_footer' ] );
	}

	/**
	 * Elementor editor preview.
	 *
	 * Add a script to the footer of the editor preview screen.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function editor_preview_footer() {
		add_action( 'wp_footer', [ $this, 'content_template_script' ] );
	}

	/**
	 * Content template script.
	 *
	 * Add content template alternative, to display the field in Elemntor editor.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template_script() {
		?>
		<script>
		jQuery( document ).ready( () => {

			elementor.hooks.addFilter(
				'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); ?>',
				function ( inputField, item, i ) {
					const fieldType    = 'hidden';
					const fieldId      = `form_field_${i}`;
					const fieldClass   = `elementor-field-textual elementor-field ${item.css_classes}`;
					const placeholder  = item['elementor-usermeta-key'];
					const value  = item['elementor-usermeta-key'];

					return `<input type="${fieldType}" id="${fieldId}" class="${fieldClass}" placeholder="${placeholder}" value="${value}">`;
				}, 10, 3
			);

		});
		</script>
		<?php
	}

}