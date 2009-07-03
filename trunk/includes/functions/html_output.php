<?php
/****************************************************************************
 * CODE FILE   : html_output.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 july 2009
 * Description : html output functions
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_APPLICATION;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_APPLICATION;
      } else {
        $link = HTTP_SERVER . DIR_WS_APPLICATION;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    // Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid)) {
      $link .= $separator . tep_output_string($_sid);
    }

    return $link;
  }

  // The HTML href link wrapper function with enabler/disabler
  function tep_href_link_switched($link, $parameters = '', $text, $is_enabled) {
  	$retval = '';
  	if ($is_enabled)
  	  $retval = '<a href="' . tep_href_link($link, $parameters) . '">';
  	else
  	  $retval = '<font color="#C0C0C0">';
  	$retval .= $text;
  	if ($is_enabled)
  	  $retval .= '</a>';
  	else
  	  $retval .= '</font>';
  	return $retval;
  }

  function tep_href_email_address($address) {
    if (preg_match( "/^ [\d\w\/+!=#|$?%{^&}*`'~-] [\d\w\/\.+!=#|$?%{^&}*`'~-]*@ [A-Z0-9] [A-Z0-9.-]{1,61} [A-Z0-9]\. [A-Z]{2,6}$/ix", $address)) {
      $retval = '<a href="mailto:' . $address . '">' . $address . '</a>';
    } else {
      $retval = $address;
    }
    return $retval;
  }

  function tep_create_parameters($new_or_changed_parameters, $relevant_other_parameters = null, $output_type = 'string', $method = 'post') {
  	$result = '';

  	if (sizeof($new_or_changed_parameters) > 0) {
  	  // Walk through the array
  	  while (list($key, $value) = each($new_or_changed_parameters)) {
        // Detect if an ampersant is needed (first entry doesn't)
        if ($output_type == 'string') {
          if ($result != '')
            $result .= '&';
          // Retrieve and add parameter
          $result .= $key . '=' . $value;
        } elseif ($output_type == 'hidden_field') {
          if (!(is_bool($value) && $value==false) && tep_not_null($value)) {
            // Boolean value 'false' does not fit very well into these fields
            // Solution is to just drop the value, after POSTing, these
            // values read 'false' anyway.
            $result .= tep_draw_hidden_field($key, $value);
          }
        }
      }
    }

  	// Walk through the other array
    for ($index = 0; $index < sizeof($relevant_other_parameters); $index++) {
      // Retrieve parameter value
      $key = $relevant_other_parameters[$index];
      $value = $_POST[$relevant_other_parameters[$index]];
      if ($key != $name && $value != '') {
        if ($output_type == 'string') {
          // Detect if an ampersant is needed (first entry doesn't)
      	  if ($result != '')
            $result .= '&';
          // Retrieve and add parameter
          $result .= $key . '=' . $value;
        } elseif ($output_type == 'hidden_field') {
          $result .= tep_draw_hidden_field($key, $value);
        }
      }
    }
  	return $result;
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title="' . tep_output_string($alt) . '"';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language or from given path
  function tep_image_submit($image, $alt = '', $parameters = '', $image_path = '') {
    $image_submit = '<input type="image" src="';
    if (tep_not_null($image_path)) {
      $image_submit .= tep_output_string($image_path . $image);
    } else {
      $image_submit .= tep_output_string(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/' . $image);
    }
    $image_submit .= '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title="' . tep_output_string($alt) . '"';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// The HTML form submit text wrapper function
  function tep_href_submit($text, $class = 'submitLinkInfoBoxContents', $parameters = '') {
    // <a href="#" onclick="parentNode.submit()" ' . $parameters . '>' . $text . '</a>
    $href_submit = '<input type="submit" class="' . $class . '" value="' . $text . '"';
    if (tep_not_null($parameters)) $href_submit .= ' ' . $parameters;
    $href_submit .= '>';
    return $href_submit;
  }

  
  
////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    return tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = 'true') {
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ($reinsert_value == 'true') {
      $field .= ' value="' . tep_output_string(stripslashes($_POST[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input style="vertical-align:middle;" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    $selection .= ' value="' . (is_bool($value)?($value?'true':'false'):$value) . '"';

    if ($checked || (tep_not_null($_POST[$name]) && $_POST[$name]==$value)) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a SELECT object
  function tep_html_select($name, $options = null, $enabled = true, $selected_value = '', $parameters = 'onChange="this.form.submit();" size="1" style="width: 100%"') {
    $retval = '<select name="' . $name . '" ' . $parameters;
    if (!$enabled) {
      $retval .= 'disabled';
    }
    $retval .= '>';

    if (tep_not_null($options)) {
      if ($selected_value=='')
        $retval .= '<option value="" selected disabled>' . TEXT_ACTIVITY_ENTRY_SELECT . '</option>';
  	  while (list($key, $value) = each($options)) {
        $retval .= '<option value="' . $key . '"' . ($selected_value==$key?' selected':'') . '>' . $value . '</option>';
  	  }
    }

    return $retval . '</select>';
  }

////
// Output a a javascript focus part
  function tep_javascript_focus($fieldname, $form = '') {
    $script = '<script language="javascript">';
    $script .= 'document.';
    if (tep_not_null($form))
      $script .= $form . '.';
    $script .= $fieldname . '.focus();</script>';
    return $script;
  }

////
// Retrieve POST or GET variable
  function tep_post_or_get_($key = '') {
    // Because of the insecurity of $_GET parameters (manually entered in the browser,
    // this function is obsolete
    if (tep_not_null($_POST[$key]))
      return $_POST[$key];
    elseif (tep_not_null($_GET[$key]))
      return $_GET[$key];
    else
      return '';
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = true, $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
?>
