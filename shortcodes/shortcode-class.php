<?php

class CCE_Shortcodes {

	var	$conf;
	var	$popup;
	var	$params;
	var	$shortcode;
	var $cparams;
	var $cshortcode;
	var $popup_title;
	var $no_preview;
	var $has_child;
	var	$output;
	var	$errors;

	function __construct( $popup ) {
		if ( file_exists( dirname( __FILE__ ) . '/config.php' ) ) {
			$this->conf = dirname( __FILE__ ) . '/config.php';
			$this->popup = $popup;

			$this->format_shortcode();
		} else {
			$this->append_error( __( 'Configuration file does not exist.', 'cc-essentials' ) );
		}
	}

	function append_output( $output ) {
		$this->output = $this->output . "\n" . $output;
	}

	function reset_output( $output ) {
		$this->output = '';
	}

	function append_error( $error ) {
		$this->errors = $this->errors . "\n" . $error;
	}

	function format_shortcode() {
		global $cce;
		require_once( $this->conf );

		if ( isset( $cce_shortcodes[$this->popup]['child_shortcode'] ) ) {
			$this->has_child = true;
		}

		if ( isset( $cce_shortcodes ) && is_array( $cce_shortcodes ) ) {
			$this->params = $cce_shortcodes[$this->popup]['params'];
			$this->shortcode = $cce_shortcodes[$this->popup]['shortcode'];
			$this->popup_title = $cce_shortcodes[$this->popup]['popup_title'];

			$this->append_output( "\n" . '<span id="_cce_shortcode" class="hidden">' . $this->shortcode . '</span>' );
			$this->append_output( "\n" . '<span id="_cce_popup" class="hidden">' . $this->popup . '</span>' );

			if ( isset( $cce_shortcodes[$this->popup]['no_preview'] ) && $cce_shortcodes[$this->popup]['no_preview'] ) {
				$this->no_preview = true;
			}

			foreach ( $this->params as $pkey => $param ) {

				// prefix the name and id with cce_
				$pkey = 'cce_' . $pkey;

				$row_start 	= '<li class="cce-sc-form-row">' . "\n";
				$row_start .= '<span class="cce-sc-form-field">' . "\n";				

				$row_end    = '</span>' . "\n";
				$row_end   .= '<span class="cce-sc-form-label"><label class="cce-label">' . $param['label'] . '</label>' . "\n";
				$row_end   .= '<span class="cce-sc-form-desc">' . $param['desc'] . '</span></span>' . "\n";
				$row_end   .= '</li>' . "\n";

				switch ( $param['type'] ) {

					case 'text' :
						$output = $row_start;
						$output .= '<input type="text" class="cce-form-text cce-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />'."\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;
					
					case 'color' :
						$output = $row_start;
						$output .= '<input type="text" class="cce-form-color cce-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />'."\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'textarea' :
						$output = $row_start;
						$output .= '<textarea rows="8" cols="30" class="cce-form-textarea cce-input" name="' . $pkey . '" id="' . $pkey . '">' . $param['std'] . '</textarea>'."\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'select' :
						$output = $row_start;
						$output .= '<select name="' . $pkey . '" id="' . $pkey . '" class="cce-form-select cce-input">' . "\n";

						if ( ! isset( $param['std'] ) ) $param['std'] = '';

						foreach ( $param['options'] as $value => $option ) {
							$output .= "<option value='$value' ". selected( $value, $param['std'], false ) .">$option</option>";
						}

						$output .= '</select>' . "\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'checkbox' :
						$output = $row_start;
						$output .= '<label for="' . $pkey . '" class="cce-form-checkbox">' . "\n";
						$output .= '<input type="checkbox" class="cce-input" name="' . $pkey . '" id="' . $pkey . '" ' . ( $param['std'] ? 'checked' : '' ) . ' />' . "\n";
						$output .= ' ' . $param['checkbox_text'] . '</label>' . "\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'image';
						$output = $row_start;
						$output .= '<a href="#" data-type="image" data-name="' . $pkey . '" data-text="Insert Image" class="cce-open-media button" title="' . esc_attr__( 'Choose Image', 'cc-essentials' ) . '">' . __( 'Choose Image', 'cc-essentials' ) . '</a>';
						$output .= '<input class="cce-input" type="text" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />';
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'video';
						$output = $row_start;
						$output .= '<a href="#" data-type="video" data-name="' . $pkey . '" data-text="Insert Video" class="cce-open-media button" title="' . esc_attr__( 'Choose Video', 'cc-essentials' ) . '">' . __( 'Choose Video', 'cc-essentials' ) . '</a>';
						$output .= '<input class="cce-input" type="text" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />';
						$output .= $row_end;
						$this->append_output( $output );
					break;

					case 'icons':
						$output = $row_start;
						$output .= '<div class="cce-all-icons">';

						$output .= '</div>';
						$output .= '<input class="cce-input" type="hidden" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />';
						$output .= $row_end;
						$this->append_output( $output );
					break;
					
					case 'map' :
						$output = $row_start;
						$output .= '<input type="text" class="cce-form-text cce-form-location cce-input" data-key="' . $pkey . '" name="address-' . $pkey . '" id="address-' . $pkey . '" placeholder="' . __('Enter a location', 'cc-essentials') . '" hidden="hidden" />'."\n";
						$output .= '<div class="cce-form-mappicker" id="picker-' . $pkey . '"></div>'."\n";
						$output .= '<input type="text" class="cce-form-text cce-input" name="lat-' . $pkey . '" id="lat-' . $pkey . '" value="" />'."\n";
						$output .= '<input type="text" class="cce-form-text cce-input" name="long-' . $pkey . '" id="long-' . $pkey . '" value="" />'."\n";
						$output .= '<input type="text" class="cce-form-text cce-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" hidden="hidden" />'."\n";
						$output .= $row_end;
						$this->append_output( $output );
					break;

				}
			}

			if ( isset( $cce_shortcodes[$this->popup]['child_shortcode'] ) ) {
				$this->cparams = $cce_shortcodes[$this->popup]['child_shortcode']['params'];
				$this->cshortcode = $cce_shortcodes[$this->popup]['child_shortcode']['shortcode'];

				$prow_start  = '<li class="cce-sc-form-row has-child">' . "\n";
				$prow_start .= '<span><a href="#" id="form-child-add" class="button-secondary">' . $cce_shortcodes[$this->popup]['child_shortcode']['clone_button'] . '</a>' . "\n";
				$prow_start .= '<div class="child-clone-rows">' . "\n";

				// for js use
				$prow_start .= '<span id="_cce_cshortcode" class="hidden">' . $this->cshortcode . '</span>' . "\n";

				// start the default row
				$prow_start .= '<div class="child-clone-row">' . "\n";
				$prow_start .= '<ul class="child-clone-row-form">' . "\n";

				$this->append_output( $prow_start );

				foreach ( $this->cparams as $cpkey => $cparam ) {
					$cpkey = 'cce_' . $cpkey;

					$crow_start  = '<li class="child-clone-row-form-row">' . "\n";
					$crow_start .= '<div class="child-clone-row-label">' . "\n";
					$crow_start .= '<label>' . $cparam['label'] . '</label>' . "\n";
					$crow_start .= '</div>' . "\n";
					$crow_start .= '<div class="child-clone-row-field">' . "\n";

					$crow_end	  = '<span class="child-clone-row-desc">' . $cparam['desc'] . '</span>' . "\n";
					$crow_end   .= '</div>' . "\n";
					$crow_end   .= '</li>' . "\n";

					switch ( $cparam['type'] ) {

						case 'text':
							$coutput  = $crow_start;
							$coutput .= '<input type="text" class="cce-form-text cce-cinput" name="' . $cpkey . '" id="' . $cpkey . '" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= $crow_end;
							$this->append_output( $coutput );
						break;

						case 'textarea':
							$coutput  = $crow_start;
							$coutput .= '<textarea rows="10" cols="30" name="' . $cpkey . '" id="' . $cpkey . '" class="cce-form-textarea cce-cinput">' . $cparam['std'] . '</textarea>' . "\n";
							$coutput .= $crow_end;
							$this->append_output( $coutput );
						break;

						case 'select' :
							$coutput  = $crow_start;
							$coutput .= '<select name="' . $cpkey . '" id="' . $cpkey . '" class="cce-form-select cce-cinput">' . "\n";

							foreach ( $cparam['options'] as $value => $option ) {
								$coutput .= '<option value="' . $value . '">' . $option . '</option>' . "\n";
							}

							$coutput .= '</select>' . "\n";
							$coutput .= $crow_end;
							$this->append_output( $coutput );
						break;

						case 'checkbox' :
							$coutput  = $crow_start;
							$coutput .= '<label for="' . $cpkey . '" class="cce-form-checkbox">' . "\n";
							$coutput .= '<input type="checkbox" class="cce-cinput" name="' . $cpkey . '" id="' . $cpkey . '" ' . ( $cparam['std'] ? 'checked' : '' ) . ' />' . "\n";
							$coutput .= ' ' . $cparam['checkbox_text'] . '</label>' . "\n";
							$coutput .= $crow_end;
							$this->append_output( $coutput );
						break;

					}
				}

				$prow_end    = '</ul>' . "\n";
				$prow_end   .= '<a href="#" class="child-clone-row-remove">Remove</a>' . "\n";
				$prow_end   .= '</div>' . "\n";
				$prow_end   .= '</div>' . "\n";
				$prow_end   .= '</span>' . "\n";
				$prow_end   .= '</li>' . "\n";

				$this->append_output( $prow_end );
			}
		}
	}
}
