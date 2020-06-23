<?php
//custom Publix Co-Op Calendar field
if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class GF_Field_Rede_Datepicker extends GF_Field {

	public $type = 'rede_datepicker';

	public function get_form_editor_field_title() {
		return esc_attr__( 'Rede Datepicker', 'gravityforms' );
	}

	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'rules_setting',
			'visibility_setting',
			'duplicate_setting',
			'description_setting',
			'css_class_setting',
			'start_date_setting'
		);
	}

	public function is_conditional_logic_supported() {
		return true;
	}

	public function validate( $value, $form ) {
		if ( $this->isRequired && $this->enableOtherChoice && rgpost( "input_{$this->id}" ) == 'gf_other_choice' ) {
			if ( empty( $value ) || strtolower( $value ) == strtolower( GFCommon::get_other_choice_value( $this ) ) ) {
				$this->failed_validation  = true;
				$this->validation_message = empty( $this->errorMessage ) ? esc_html__( 'This field is required.', 'gravityforms' ) : $this->errorMessage;
			}
		}
	}

	public function get_first_input_id( $form ) {
		return '';
	}
	
	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$html_input_type = 'text';

		if ( $this->enablePasswordInput && ! $is_entry_detail ) {
			$html_input_type = 'password';
		}

		$id          = (int) $this->id;
		$field_id    = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$value        = esc_attr( $value );
		$size         = $this->size;
		$class_suffix = $is_entry_detail ? '_admin' : '';
		$class        = $size . $class_suffix;

		$max_length = is_numeric( $this->maxLength ) ? "maxlength='{$this->maxLength}'" : '';

		$tabindex              = $this->get_tabindex();
		$disabled_text         = $is_form_editor ? 'disabled="disabled"' : '';
		$placeholder_attribute = $this->get_field_placeholder_attribute();
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute     = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$aria_describedby      = $this->get_aria_describedby();
		
		ob_start();
		if(!$is_form_editor):
			?>
			<style>

			</style>
			<script>
				(function($){
					$(document).ready(function(){
						var firstDateAvailable = parseInt('<?php echo isset($this->firstDateAvailable) ? $this->firstDateAvailable : "21"; ?>')
						$("#<?php echo $field_id; ?>").datepicker({
							minDate: +''+firstDateAvailable,
							beforeShowDay: function(date){ 
								var day = date.getDay(); 
								return [day == 1,""];
							}
						});
					})
				})(jQuery);
			</script>
			<?php
			$settings = $this->get_filter_settings();
		endif;
		$script = ob_get_clean();

		$input = "<input name='input_{$id}' id='{$field_id}' type='{$html_input_type}' value='{$value}' class='{$class}' {$max_length} {$aria_describedby} {$tabindex} {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$disabled_text}/>";

		return sprintf( "%s<div class='ginput_container ginput_container_text'>%s</div>", $script, $input );
	}


}

GF_Fields::register( new GF_Field_Rede_Datepicker() );

// add custom setting for start date
add_action( 'gform_field_standard_settings', 'first_available_datepicker_setting', 10, 2 );
function first_available_datepicker_setting($position, $form_id){
    //create settings on position 25 (right after Field Label)
    if ( $position == 5 ) {
        ?>
		<script>
			(function($){
				
				$(document).ready(function(){
					$(document).on('gform_load_field_settings', function(event, field, form){
						$('#first_date_available').val(field.firstDateAvailable)
			        });
					$('#first_date_available').on('change', function(){
						SetFieldProperty('firstDateAvailable', $(this).val());
						console.log($(this).val());
					})
					
				})
				
			})(jQuery)
		</script>
        <li class="start_date_setting field_setting">
            <label for="field_admin_label">
                <?php esc_html_e( 'First Date Available', 'gravityforms' ); ?>
            </label>
			<select name="first_date_available" id="first_date_available">
				<option value="14">2 weeks</option>
				<option value="21">3 weeks</option>
				<option value="28">4 weeks</option>
			</select>
        </li>
        <?php
    }
}