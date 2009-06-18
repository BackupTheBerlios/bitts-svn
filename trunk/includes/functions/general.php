<?php
/****************************************************************************
 * CLASS FILE  : general.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 june 2009
 * Description : General functions
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // Formatted time string
  function tep_strftime($format, $timestamp = null) {
    /*** string tep_strftime ( string format [, int timestamp] ) ***/
    global $LANGUAGE_ARRAY_MONTH;
    global $LANGUAGE_ARRAY_DAY;

    if (isset($timestamp))
      $tepstrftime = strftime($format, $timestamp);
    else
      $tepstrftime = strftime($format);

    if (isset($LANGUAGE_ARRAY_MONTH))
      $tepstrftime = strtr($tepstrftime, $LANGUAGE_ARRAY_MONTH);
    if (isset($LANGUAGE_ARRAY_DAY))
      $tepstrftime = strtr($tepstrftime, $LANGUAGE_ARRAY_DAY);

    return $tepstrftime;
  }

  // Formatted period from formatted datetime
  function tep_datetoperiod($format = null, $date = null) {
  	if (isset($format) && isset($date))
  	  return strftime('%Y-%m', tep_datetouts($format, $date));
  	else
  	  return strftime('%Y-%m');
  }

  // Formatted datetime from formatted period
  function tep_periodstartdate($period = null) {
    if ($period == null)
      $period = tep_datetoperiod();
  	$year = (int)substr($period, 0, 4);
  	$month = (int)substr($period, 5, 2);
    return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
  }

  function tep_periodenddate($period = null) {
    if ($period == null)
      $period = tep_datetoperiod();
    $year = (int)substr($period, 0, 4);
    $month = (int)substr($period, 5, 2);
    return strftime('%Y-%m-%d', mktime(0, 0, 0, $month + 1, 0, $year));
  }

  // Test date by format
  function tep_testdate($format, $date) {
    // Available formats:
    // '%Y-%m-%d'
    // '%d-%m-%Y'
    // '%m-%d-%Y'
    $date_array = explode('-', $date);
    if (sizeof($date_array) != 3) {
      return false;
    }

    switch ($format) {
      case '%Y-%m-%d':
        $year = (int)$date_array[0];
        $month = (int)$date_array[1];
        $day = (int)$date_array[2];
        break;
      case '%d-%m-%Y':
        $day = (int)$date_array[0];
        $month = (int)$date_array[1];
        $year = (int)$date_array[2];
        break;
      case '%m-%d-%Y':
        $month = (int)$date_array[0];
        $day = (int)$date_array[1];
        $year = (int)$date_array[2];
        break;
      default:
        return false;
    }

    if ($year<1970 || $year>2099)
      return false;

    if ($day<1)
      return false;

    if (($month=1 || $month=3 || $month=5 || $month=7 || $month=8 || $month=10 || $month=12) && $day>31)
      return false;

    if (($month=4 || $month=6 || $month=9 || $month=11) && $day>30)
      return false;

    if ($month=2 && ((!tep_is_leap_year($year) && $day>28) || (tep_is_leap_year($year) && $day>29)))
      return false;

    // All seems to be in order
    return true;
  }

  ////
  // Check if year is a leap year
  function tep_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

  // UNIX timestamp from formatted datetime
  function tep_datetouts($format, $date, $offset_in_days = 0) {
    // Available formats:
    // '%Y-%m-%d'
    // '%d-%m-%Y'
    // '%m-%d-%Y'
    $format_ok=false;
    $date_array = explode('-', $date);
    if (sizeof($date_array) != 3) {
      return false;
    }

    switch ($format) {
      case '%Y-%m-%d':
        $year = (int)$date_array[0];
        $month = (int)$date_array[1];
        $day = (int)$date_array[2];
        $format_ok=true;
        break;
      case '%d-%m-%Y':
        $day = (int)$date_array[0];
        $month = (int)$date_array[1];
        $year = (int)$date_array[2];
        $format_ok=true;
        break;
      case '%m-%d-%Y':
        $month = (int)$date_array[0];
        $day = (int)$date_array[1];
        $year = (int)$date_array[2];
        $format_ok=true;
        break;
    }

    $day += $offset_in_days;

    return ($format_ok?mktime(0, 0, 0, $month, $day, $year):0);
  }

  // Return previous/next period
  function tep_next_period($period, $increase = 1) {
  	$year = (int)substr($period, 0, 4);
  	$month = (int)substr($period, 5, 2);
  	
  	$month += $increase;
  	while ($month > 12) {
  	  $year += 1;
  	  $month -= 12;
  	}
  	while ($month < 1) {
  	  $year -= 1;
  	  $month += 12;
  	}
    return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
  }

  function tep_not_null($value) {
    if (is_object($value)) {
      return !is_null($value);
    } else if (is_array($value)) {
      return (sizeof($value) > 0);
    } else {
      return (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0));
    }
  }

  function tep_get_partial_array($array = null, $key = '', $value = '') {
    $key_array = array();
    $value_array = array();
    $result_array = array();
    foreach ($array as $row) {
      array_push($key_array, $row[$key]);
      array_push($value_array, $row[$value]);
    }
    // Only combine arrays if they contain any values
    if (sizeof($key_array)!=0)
      $result_array = array_combine($key_array, $value_array);
    return $result_array;
  }

  
  
  
  
  






// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }

// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
        $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
      }
    }

    header('Location: ' . $url);

    tep_exit();
  }

  function tep_number_db_to_user($number = 0, $precision = 0) {
    return number_format($number, $precision, NUMBER_DECIMAL_POINT, NUMBER_THOUSANDS_SEPARATOR);
  }

  function tep_number_user_to_db($number = '0') {
    $number = trim($number);
    // Trim front part incl. max 2 decimals
    if (preg_match("~^([0-9]+|(?:(?:[0-9]{1,3}([.,' ]))+[0-9]{3})+)(([.,])[0-9]{1,2})?$~", $number, $matches)) {
      if (!empty($matches['2'])) {
        $prefix = preg_replace("~[".$matches['2']."]~", "", $matches['1']);
      } else {
        $prefix = $matches['1'];
      }
      if (!empty($matches['4'])) {
        $postfix = ".".preg_replace("~[".$matches['4']."]~", "", $matches['3']);
      } else {
        $postfix = false;
      }
      return $prefix.$postfix;
    }
    return 0;
  }
  
  
  
  
  
  
  
  
  
  
  
  
////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    $string = ereg_replace(' +', ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
  }

////
// Return a random row from a database query
  function tep_random_select($query) {
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = tep_rand(0, ($num_rows - 1));
      tep_db_data_seek($random_query, $random_row);
      $random_product = tep_db_fetch_array($random_query);
    }

    return $random_product;
  }


////
// Break a word in a string if it is longer than a specified length ($len)
  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

////
// Return all HTTP GET variables, except those passed as a parameter
  function tep_get_all_get_params($exclude_array = '') {
    global $HTTP_GET_VARS;

    if (!is_array($exclude_array)) $exclude_array = array();

    $get_url = '';
    if (is_array($HTTP_GET_VARS) && (sizeof($HTTP_GET_VARS) > 0)) {
      reset($HTTP_GET_VARS);
      while (list($key, $value) = each($HTTP_GET_VARS)) {
        if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
        }
      }
    }

    return $get_url;
  }

////
// Generate a path to categories
  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if (tep_not_null($current_category_id)) {
      $cp_size = sizeof($cPath_array);
      if ($cp_size == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i=0; $i<($cp_size-1); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i=0; $i<$cp_size; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    } else {
      $cPath_new = implode('_', $cPath_array);
    }

    return 'cPath=' . $cPath_new;
  }

////
// Returns the clients browser
  function tep_browser_detect($component) {
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
  }

////
// Alias function to tep_get_countries()
  function tep_get_country_name($country_id) {
    $country_array = tep_get_countries($country_id);

    return $country_array['countries_name'];
  }

////
// Wrapper function for round()
  function tep_round($number, $precision) {
    if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return tep_strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
  }


////
// Check date
  function tep_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if (strlen($date_to_check) != strlen($format_string)) {
      return false;
    }

    $size = sizeof($separators);
    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
      return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      $size = sizeof($format_string_array);
      for ($i=0; $i<$size; $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
        if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          $size = sizeof($month_abbr);
          for ($i=0; $i<$size; $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (tep_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (eregi('^[a-z]$', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (ereg('^[0-9]$', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

  function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
    setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
  }

  function tep_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }
?>