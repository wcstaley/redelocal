<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}


class GF_Field_Previous_Entries extends GF_Field {

	public $type = 'previous_entries';

	public function get_form_editor_field_title() {
		return esc_attr__( 'Previous Entries', 'gravityforms' );
	}

	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'enable_enhanced_ui_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'size_setting',
			'choices_setting',
			'rules_setting',
			'placeholder_setting',
			'default_value_setting',
			'visibility_setting',
			'duplicate_setting',
			'description_setting',
			'css_class_setting',
		);
	}

	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id       = $this->id;
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$size               = $this->size;
		$class_suffix       = $is_entry_detail ? '_admin' : '';
		$class              = $size . $class_suffix;
		$css_class          = trim( esc_attr( $class ) . ' gfield_select' );
		$tabindex           = $this->get_tabindex();
		$disabled_text      = $is_form_editor ? 'disabled="disabled"' : '';
		$required_attribute = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute  = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';

		if(is_admin()){
			return "Choices will show current user's previous entries";
		}

		return sprintf( "<div class='ginput_container ginput_container_select'><select name='input_%d' id='%s' class='%s' $tabindex %s %s %s>%s</select></div>", $id, $field_id, $css_class, $disabled_text, $required_attribute, $invalid_attribute, $this->get_choices( $value ) );
	}

	public function get_choices( $value ) {
		$choices     = '';
		$placeholder = '';

		$user_id = get_current_user_id();
		if(!$user_id){
			return $choices;
		}

		$search_criteria = array();
		$search_criteria['field_filters'][] = array('key' => 'created_by', 'value' => $user_id);
		$previous_entries = GFAPI::get_entries(6, $search_criteria);
		
		if($previous_entries){
			foreach($previous_entries as $previous){
				$run_date = $previous[2397];
				if($run_date){
					$choices .= sprintf( "<option value='%s'>%s</option>", $run_date, $run_date );
				}
			}
		}
		return $choices;
	}

}

GF_Fields::register( new GF_Field_Previous_Entries() );