<?php
/****************************************************************************
 * CODE FILE   : application_top.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 july 2009
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

  // Include the list of application filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

  // Include the list of application database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

  // Customization for the design layout
  define('BOX_WIDTH', 175); // how wide the boxes should be in pixels (default: 175)

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

  // Include the employee and profile classes
  require(DIR_WS_CLASSES . 'employee.php');
  require(DIR_WS_CLASSES . 'profile.php');

  // Include the project and related classes
  require(DIR_WS_CLASSES . 'customer.php');
  require(DIR_WS_CLASSES . 'business_unit.php');
  require(DIR_WS_CLASSES . 'project.php');
  require(DIR_WS_CLASSES . 'category.php');
  require(DIR_WS_CLASSES . 'role.php');
  require(DIR_WS_CLASSES . 'employee_role.php');
  require(DIR_WS_CLASSES . 'tariff.php');
  require(DIR_WS_CLASSES . 'unit.php');

  // Include the timesheet and related classes
  require(DIR_WS_CLASSES . 'timesheet.php');
  require(DIR_WS_CLASSES . 'activity.php');

  // If action == logout clear server & session variables
  if ($_POST['action']=='logout') {
    if ( isset( $_SERVER['LOGON_USER'] ) ) {
      unset( $_SERVER['LOGON_USER'] );
    }
    unset( $_SESSION['employee_login'] );
    unset( $_SESSION['employee'] );
    $_POST['action']='';
  }

  // Verify if current user is not already logged-in
  if (!tep_not_null($_SESSION['employee_login'])) {
    if ( isset( $_SERVER['LOGON_USER'] ) && $_SERVER['LOGON_USER'] != '' ) {
      $_SESSION['employee_login'] = $_SERVER['LOGON_USER'];
    } else if ( isset( $_SERVER['AUTH_USER'] ) && $_SERVER['AUTH_USER'] != '' ) {
      $_SESSION['employee_login'] = $_SERVER['AUTH_USER'];
    }
  }

  // If logged-in now, create the employee object
  if (tep_not_null($_SESSION['employee_login'])) {
    $_SESSION['employee'] = new employee($_SESSION['employee_login'], 'employees_login');
  }
?>