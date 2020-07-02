<?php
//custom Publix Co-Op Calendar field
if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class GF_Field_Coop_Calendar extends GF_Field {

	public $type = 'publix_coop_calendar';

	public function get_form_editor_field_title() {
		return esc_attr__( 'Co-Op Calendar', 'gravityforms' );
	}

	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'choices_setting',
			'rules_setting',
			'visibility_setting',
			'duplicate_setting',
			'description_setting',
			'css_class_setting',
			'other_choice_setting',
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

		$form_id         = $form['id'];
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id            = $this->id;
		$field_id      = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$disabled_text = $is_form_editor ? 'disabled="disabled"' : '';
		
		ob_start();
		
		?>
		<style>
			.coop-calendar-entry {
				position: relative;
			}
			#coop-calendar-wrapper .coop-calendar-entry input[type="radio"] {
				-webkit-appearance: none;
				display: block !important;
				position: absolute;
				top: 0;
				left: 0;
				width: 100% !important;
				height: 100%;
				margin: 0;
			}
			.coop-calendar-entry.faded{
				opacity:.7;
			}
			.due_date{
				font-size:12px;
				color:#444;
				font-weight:400;
				margin-left:5px;
			}
			.coop-calendar-entry.active .due_date, .coop-calendar-entry:hover .due_date{
				color:#fff;
				font-weight:700;
			}
			.coop-calendar-entry.faded:hover{
				opacity:1;
			}
			
		</style>
		<script>
			(function($){
				$(document).ready(function(){
					$('.coop-calendar-entry').click(function(){
						$('.coop-calendar-entry').removeClass('active').addClass('faded');
						$(this).addClass('active').removeClass('faded');
						$('#<?php echo $field_id; ?>').val($(this).attr('data-inputval'));
					})
				})
			})(jQuery);
		</script>
		<?php
		
		$script = ob_get_clean();
		
		return sprintf( "%s<div id='coop-calendar-wrapper' class='ginput_container ginput_container_radio'><ul class='gfield_radio' id='%s'>%s</ul></div>", $script, $field_id, $this->get_radio_choices( $value, $disabled_text, $form_id ) );
		
	}
	
	public function get_radio_choices( $value = '', $disabled_text = '', $form_id = 0 ) {
		$choices = '';
		
		$calendar_choices = array();
		$calendar_entries = get_field('coop_cal_entry', 'options');
		foreach($calendar_entries as $calendar_entry){
			$submission_end = $calendar_entry['submission_end'];
			$submission_end_stamp = strtotime($submission_end);
			$today_midnight = strtotime('today midnight');
			
			// Make sure the submission end date has not yet passed
			if($submission_end_stamp > $today_midnight){
				$calendar_choices[] = array(
					'value' => $calendar_entry['unique_id'],
					'text' => $calendar_entry['popular_holidays']
				);
			}
		}
		
		$this->choices = $calendar_choices;
		
		if ( is_array( $this->choices ) ) {
			$is_entry_detail    = $this->is_entry_detail();
			$is_form_editor     = $this->is_form_editor();
			$is_admin           = $is_entry_detail || $is_form_editor;

			$field_choices      = $this->choices;
			$needs_other_choice = $this->enableOtherChoice;
			$editor_limited     = false;

			$choice_id = 0;
			$count     = 1;


			foreach ( $field_choices as $choice ) {

				$choices .= $this->get_choice_html( $choice, $choice_id, $value, $disabled_text, $is_admin );

				if ( $is_form_editor && $count >= 5 ) {
					$editor_limited = true;
					break;
				}

				$count ++;
			}

			$total = sizeof( $field_choices );
			if ( $is_form_editor && ( $count < $total ) ) {
				$choices .= "<li class='gchoice_total'>" . sprintf( esc_html__( '%d of %d items shown. Edit field to view all', 'gravityforms' ), $count, $total ) . '</li>';
			}
		}

		/**
		 * Allows the HTML for multiple choices to be overridden.
		 *
		 * @since unknown
		 *
		 * @param string         $choices The choices HTML.
		 * @param GF_Field_Radio $field   The current field object.
		 */
		return gf_apply_filters( array( 'gform_field_choices', $this->formId ), $choices, $this );
	}

	/**
	 * Returns the choice HTML.
	 *
	 * @since 2.4.17
	 *
	 * @param array  $choice        The choice properties.
	 * @param int    &$choice_id    The choice number.
	 * @param string $value         The current field value.
	 * @param string $disabled_text The disabled attribute or an empty string.
	 * @param bool   $is_admin      Indicates if this is the form editor or entry detail page.
	 *
	 * @return string
	 */
	public function get_choice_html( $choice, &$choice_id, $value, $disabled_text, $is_admin ) {
		$form_id = absint( $this->formId );

		if ( $is_admin || $form_id == 0 ) {
			$id = $this->id . '_' . $choice_id ++;
		} else {
			$id = $form_id . '_' . $this->id . '_' . $choice_id ++;
		}

		$field_value = ! empty( $choice['value'] ) || $this->enableChoiceValue ? $choice['value'] : $choice['text'];

		if ( rgblank( $value ) && rgget( 'view' ) != 'entry' ) {
			$checked = rgar( $choice, 'isSelected' ) ? "checked='checked'" : '';
		} else {
			$checked = GFFormsModel::choice_value_match( $this, $choice, $value ) ? "checked='checked'" : '';
		}

		$tabindex    = $this->get_tabindex();
		$input_focus = '';
		
		$calendar_entry = array();
		$calendar_entries = get_field('coop_cal_entry', 'options');
		foreach($calendar_entries as $cal_entry){
			if($choice['value'] == $cal_entry['unique_id']){
				$calendar_entry = $cal_entry;
				break;
			}
		}
		$theme_images = array();
		foreach(get_field('theme_pages', 'options') as $theme_page){
			$image = wp_get_attachment_image_src($theme_page['image'], 'thumbnail');
			$theme_images[$theme_page['key']] = $image[0];
		}
		
		ob_start();
		?>
		<div class="coop-calendar-entry">
			<input name='input_<?php echo $this->id; ?>' type='radio' value='<?php echo $field_value; ?>' <?php echo $checked ? 'checked="checked"' : ''; ?> id='choice_<?php echo $id; ?>' />
			
			<span class="dates"><?php echo date("n/j/y", strtotime($calendar_entry['start_date'])); ?> &ndash; <?php echo date("n/j/y", strtotime($calendar_entry['end_date'])); ?></span>
			<span class="holidays"><?php echo $calendar_entry['popular_holidays']; ?></span>
			<span class="due_date">Due Date: <?php echo date("n/j/y", strtotime($calendar_entry['submission_end'])); ?></span>
			<?php if(is_array($calendar_entry['theme_pages']) && !empty($calendar_entry['theme_pages'])) : ?>
				<?php foreach($calendar_entry['theme_pages'] as $theme) : ?>
					<span class="theme-icon" style="background-image:url('<?php echo $theme_images[$theme]; ?>')"></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php
		
		return ob_get_clean();
	}

	public function get_value_default() {
		return $this->is_form_editor() ? $this->defaultValue : GFCommon::replace_variables_prepopulate( $this->defaultValue );
	}

	public function get_value_submission( $field_values, $get_from_post_global_var = true ) {

		$value = $this->get_input_value_submission( 'input_' . $this->id, $this->inputName, $field_values, $get_from_post_global_var );
		if ( $value == 'gf_other_choice' ) {
			//get value from text box
			$value = $this->get_input_value_submission( 'input_' . $this->id . '_other', $this->inputName, $field_values, $get_from_post_global_var );
		}

		return $value;
	}

	public function get_value_entry_list( $value, $entry, $field_id, $columns, $form ) {
		return wp_kses_post( GFCommon::selection_display( $value, $this, $entry['currency'] ) );
	}

	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		return wp_kses_post( GFCommon::selection_display( $value, $this, $currency, $use_text ) );
	}

	/**
	 * Gets merge tag values.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @uses GFCommon::to_money()
	 * @uses GFCommon::format_post_category()
	 * @uses GFFormsModel::is_field_hidden()
	 * @uses GFFormsModel::get_choice_text()
	 * @uses GFCommon::format_variable_value()
	 * @uses GFCommon::implode_non_blank()
	 *
	 * @param array|string $value      The value of the input.
	 * @param string       $input_id   The input ID to use.
	 * @param array        $entry      The Entry Object.
	 * @param array        $form       The Form Object
	 * @param string       $modifier   The modifier passed.
	 * @param array|string $raw_value  The raw value of the input.
	 * @param bool         $url_encode If the result should be URL encoded.
	 * @param bool         $esc_html   If the HTML should be escaped.
	 * @param string       $format     The format that the value should be.
	 * @param bool         $nl2br      If the nl2br function should be used.
	 *
	 * @return string The processed merge tag.
	 */
	public function get_value_merge_tag( $value, $input_id, $entry, $form, $modifier, $raw_value, $url_encode, $esc_html, $format, $nl2br ) {
		$modifiers       = $this->get_modifiers();
		$use_value       = in_array( 'value', $modifiers );
		$format_currency = ! $use_value && in_array( 'currency', $modifiers );
		$use_price       = $format_currency || ( ! $use_value && in_array( 'price', $modifiers ) );

		if ( is_array( $raw_value ) && (string) intval( $input_id ) != $input_id ) {
			$items = array( $input_id => $value ); // Float input Ids. (i.e. 4.1 ). Used when targeting specific checkbox items.
		} elseif ( is_array( $raw_value ) ) {
			$items = $raw_value;
		} else {
			$items = array( $input_id => $raw_value );
		}

		$ary = array();

		foreach ( $items as $input_id => $item ) {
			if ( $use_value ) {
				list( $val, $price ) = rgexplode( '|', $item, 2 );
			} elseif ( $use_price ) {
				list( $name, $val ) = rgexplode( '|', $item, 2 );
				if ( $format_currency ) {
					$val = GFCommon::to_money( $val, rgar( $entry, 'currency' ) );
				}
			} elseif ( $this->type == 'post_category' ) {
				$use_id     = strtolower( $modifier ) == 'id';
				$item_value = GFCommon::format_post_category( $item, $use_id );

				$val = RGFormsModel::is_field_hidden( $form, $this, array(), $entry ) ? '' : $item_value;
			} else {
				$val = RGFormsModel::is_field_hidden( $form, $this, array(), $entry ) ? '' : RGFormsModel::get_choice_text( $this, $raw_value, $input_id );
			}

			$ary[] = GFCommon::format_variable_value( $val, $url_encode, $esc_html, $format );
		}

		return GFCommon::implode_non_blank( ', ', $ary );
	}

	public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {

		$value = stripslashes($value);

		return $value;
	}

	public function get_value_export( $entry, $input_id = '', $use_text = false, $is_csv = false ) {
		if ( empty( $input_id ) ) {
			$input_id = $this->id;
		}

		$value = rgar( $entry, $input_id );

		return $is_csv ? $value : GFCommon::selection_display( $value, $this, rgar( $entry, 'currency' ), $use_text );
	}

	// # FIELD FILTER UI HELPERS ---------------------------------------------------------------------------------------

	/**
	 * Returns the filter operators for the current field.
	 *
	 * @since 2.4
	 *
	 * @return array
	 */
	public function get_filter_operators() {
		$operators = $this->type == 'product' ? array( 'is' ) : array( 'is', 'isnot', '>', '<' );

		return $operators;
	}

}

GF_Fields::register( new GF_Field_Coop_Calendar() );