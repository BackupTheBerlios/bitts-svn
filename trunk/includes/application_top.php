<?php
/****************************************************************************
 * CODE FILE   : application_top.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 14 april 2008
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

  // Start the session
  session_start();

  // Include server parameters
  require('includes/configuration.php');

  // define the project version
  define('PROJECT_VERSION', 'BitTS v0.1');

  // Include the list of application filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

  // Include the list of application database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

  // Customization for the design layout
  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)

  // Include the database functions
  require(DIR_WS_CLASSES . 'database.php');

  // Make a connection to the database...
  $database = new database();
  $_SESSION['database'] = $database;
  $database->connect() or die('Unable to connect to database server!');

  // Set the application parameters
  $configuration_query = $database->query('select configuration_key, configuration_value from ' . TABLE_CONFIGURATION);
  while ($configuration_result = $database->fetch_array($configuration_query)) {
    define($configuration_result['configuration_key'], $configuration_result['configuration_value']);
  }

  // Define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

  // Set the language
  if (!isset($_SESSION['language']) || isset($_GET['language'])) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $_SESSION['language'] = $lng->language['directory'];
    $_SESSION['languages_id'] = $lng->language['id'];
  }

  // Include the language translations
  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '.php');

  // Include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

  // infobox
  require(DIR_WS_CLASSES . 'boxes.php');

  // Include the employee class
  require(DIR_WS_CLASSES . 'employee.php');

  // Include the timesheet class
  require(DIR_WS_CLASSES . 'timesheet.php');

  // Get logged-on / authenticated user's name
  if ( isset( $_SERVER['LOGON_USER'] ) && $_SERVER['LOGON_USER'] != '' ) {
  	$_SESSION['employee'] = new employee($_SERVER['LOGON_USER'], 'employees_login');
  } else if ( isset( $_SERVER['AUTH_USER'] ) && $_SERVER['AUTH_USER'] != '' ) {
  	$_SESSION['employee'] = new employee($_SERVER['AUTH_USER'], 'employees_login');
  } else {
    // TEST LOGIN !!!!!!!!!!!!REMOVE !!!!!!!!!!!!!!!!!
  	$_SESSION['employee'] = new employee('e.beukhof', 'employees_login');
    //$_SESSION['employee'] = null;
  }
?>