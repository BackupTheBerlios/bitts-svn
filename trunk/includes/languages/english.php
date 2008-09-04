<?php
/****************************************************************************
 * CODE FILE   : english.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 04 september 2008
 * Description : English language file
 */

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%m-%d-%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %B %d, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm-d-Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('NUMBER_DECIMAL_POINT', '.');
define('NUMBER_THOUSANDS_SEPARATOR', ',');

$LANGUAGE_ARRAY_MONTH = array ("January" => "january", "February" => "february", "March" => "march", "April" => "april", "May" => "may", "June" => "june", "July" => "july", "August" => "august", "September" => "september", "October" => "october", "November" => "november", "December" => "december");

$LANGUAGE_ARRAY_DAY = array ("Monday" => "monday", "Tuesday" => "tuesday", "Wednesday" => "wednesday", "Thursday" => "thursday", "Friday" => "friday", "Saturday" => "saturday", "Sunday" => "sunday");

////
// Return date in raw format
// $date should be in format dd-mm-yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', COMPANY_NAME . ' - ' . PROJECT_NAME . ' ' . PROJECT_VERSION);

define('HEADER_TEXT_CURRENT_USER', 'Curent user : ');
define('HEADER_TEXT_NO_CURRENT_USER', 'Not logged in!');
define('HEADER_TEXT_YOUR_DATA', 'Your data');
define('HEADER_TEXT_LOGIN', 'Log in');
define('HEADER_TEXT_TIMEREGISTRATION', 'Time registration');

define('BOX_HEADING_MAINMENU', 'Menu');
define('BOX_MAINMENU_HOME', 'Home');
define('BOX_MAINMENU_TIMEREGISTRATION', 'Time registration');
define('BOX_MAINMENU_ANALYSIS', 'Reports');
define('BOX_MAINMENU_ADMINISTRATION', 'Admin');
define('BOX_MAINMENU_ADMINISTRATION_PROJECTS', 'Manage projects');
define('BOX_MAINMENU_ADMINISTRATION_CUSTOMERS', 'Manage customers');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES', 'Manage employees');

define('BODY_TEXT_LOGIN', 'Login');
define('BODY_TEXT_PASSWORD', 'Password');
define('BODY_TEXT_FULLNAME', 'Full name');
define('BODY_TEXT_EMPLOYEE_ID', 'Personnel number');
define('BODY_TEXT_IS_USER', 'Is user');
define('BODY_TEXT_IS_ANALYST', 'Is reporter');
define('BODY_TEXT_IS_ADMINISTRATOR', 'Is admin');
define('BODY_TEXT_YES', 'Yes');
define('BODY_TEXT_NO', 'No');

define('TEXT_TIMEREGISTRATION_BACK', 'Period back');
define('TEXT_TIMEREGISTRATION_PERIOD', 'Period : ');
define('TEXT_TIMEREGISTRATION_FORWARD', 'Period forward');
define('TEXT_TIMEREGISTRATION_LOCKED', 'This period has been confirmed and cannot be edited');
define('TEXT_TIMEREGISTRATION_IS_EMPTY', 'No activities available');
define('TEXT_TIMEREGISTRATION_CONFIRM', 'Confirm this period. NOTE: After this, changes cannot be made!');

define('TEXT_ACTIVITY_DAY', 'Day');
define('TEXT_ACTIVITY_PROJECTNAME', 'Project');
define('TEXT_ACTIVITY_ROLENAME', 'Role');
define('TEXT_ACTIVITY_AMOUNT', 'Amount');
define('TEXT_ACTIVITY_UNIT', 'Unit');
define('TEXT_ACTIVITY_TRAVELDISTANCE', 'Travel dist. (km)');
define('TEXT_ACTIVITY_EXPENSES', 'Expenses (&euro;)');
define('TEXT_ACTIVITY_TICKETNUMBER', 'Ticket nr.');
define('TEXT_ACTIVITY_COMMENT', 'Remarks');
define('TEXT_ACTIVITY_EDIT', 'Edit');
define('TEXT_ACTIVITY_DELETE', 'Delete');
define('TEXT_ACTIVITY_DELETE_QUESTION', 'Do you want to delete the activity above?');
define('TEXT_ACTIVITY_DELETE_OK', 'Delete OK');
define('TEXT_ACTIVITY_DELETE_CANCEL', 'Cancel delete');
$ACTIVITY_ERROR_LEVEL = array (0 => '',
                               1 => 'No project selected',
                               2 => 'No role selected',
                               3 => 'Incorrect amount',
                               4 => 'No unit selected',
                               5 => 'Incorrect travel distance',
                               6 => 'Incorrect expenses',
                               7 => 'Ticket nr. is mandatory');

define('TEXT_CALENDAR_MONDAY', 'm');
define('TEXT_CALENDAR_TUESDAY', 't');
define('TEXT_CALENDAR_WEDNESDAY', 'w');
define('TEXT_CALENDAR_THURSDAY', 't');
define('TEXT_CALENDAR_FRIDAY', 'f');
define('TEXT_CALENDAR_SATURDAY', 's');
define('TEXT_CALENDAR_SUNDAY', 's');

define('TEXT_ACTIVITY_ENTRY_SELECTED_DATE', 'Selected date: ');
define('TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED', 'No date selected');
define('TEXT_ACTIVITY_ENTRY_SELECT', 'Select');
define('TEXT_ACTIVITY_ENTRY_SAVE', 'Save');
define('TEXT_ACTIVITY_ENTRY_CANCEL', 'Cancel');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . COMPANY_NAME . '</a>');
?>