<?php
/****************************************************************************
 * CODE FILE   : english.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 november 2013
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

define('HEADER_INFO_UNCONFIRMED_PERIOD', 'The timesheet for period %s has not been confirmed yet');

define('HEADER_TEXT_CURRENT_USER', 'Curent user : ');
define('HEADER_TEXT_NO_CURRENT_USER', 'Not logged in!');
define('HEADER_TEXT_YOUR_DATA', 'Your data');
define('HEADER_TEXT_CURRENT_PROJECTS', 'Current projects');
define('HEADER_TEXT_LOGIN', 'Log in');
define('HEADER_TEXT_LOGOUT', 'Log out');
define('HEADER_TEXT_TIMEREGISTRATION', 'Time registration');
define('HEADER_TEXT_TIMEREGISTRATION_PUNCH_CLOCK', 'Punch Clock');
define('HEADER_TEXT_TIMEREGISTRATION_CALENDAR', 'Calendar');
define('HEADER_TEXT_ANALYSIS', 'Reports');
define('HEADER_TEXT_ADMINISTRATION', 'Administration');
define('HEADER_TEXT_ADMINISTRATION_CUSTOMERS', 'Administration Customers');
define('HEADER_TEXT_ADMINISTRATION_BUSINESS_UNITS', 'Administration Business Units');
define('HEADER_TEXT_ADMINISTRATION_PROJECTS', 'Administration Projects');
define('HEADER_TEXT_ADMINISTRATION_CATEGORIES', 'Administration Categories');
define('HEADER_TEXT_ADMINISTRATION_ROLES', 'Administration Roles');
define('HEADER_TEXT_ADMINISTRATION_PROFILES', 'Administration Profiles');
define('HEADER_TEXT_ADMINISTRATION_EMPLOYEES', 'Administration Employees');
define('HEADER_TEXT_ADMINISTRATION_EMPLOYEES_ROLES', 'Administration Employees-Roles');
define('HEADER_TEXT_ADMINISTRATION_UNITS', 'Administration Units');
define('HEADER_TEXT_ADMINISTRATION_TARIFFS', 'Administration Tariffs');
define('HEADER_TEXT_ADMINISTRATION_TIMESHEETS', 'Administration Timesheets');
define('HEADER_TEXT_ADMINISTRATION_BENEFITS', 'Administration Benefits');

define('BOX_HEADING_MAINMENU', 'Menu');
define('BOX_MAINMENU_HOME', 'Home');
define('BOX_MAINMENU_TIMEREGISTRATION', 'Time registration');
define('BOX_MAINMENU_TIMEREGISTRATION_PUNCH_CLOCK', 'Punch Clock');
define('BOX_MAINMENU_TIMEREGISTRATION_CALENDAR', 'Calendar');
define('BOX_MAINMENU_ANALYSIS', 'Reports');
define('BOX_MAINMENU_ADMINISTRATION', 'Admin');
define('BOX_MAINMENU_ADMINISTRATION_GENERAL', 'General');
define('BOX_MAINMENU_ADMINISTRATION_PROFILES', 'Profiles');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES', 'Employees');
define('BOX_MAINMENU_ADMINISTRATION_TIMESHEETS', 'Timesheets');
define('BOX_MAINMENU_ADMINISTRATION_BENEFITS', 'Benefits');
define('BOX_MAINMENU_ADMINISTRATION_SYSTEM', 'System');
define('BOX_MAINMENU_ADMINISTRATION_CUSTOMERS', 'Customers');
define('BOX_MAINMENU_ADMINISTRATION_BUSINESS_UNITS', 'Business Units');
define('BOX_MAINMENU_ADMINISTRATION_CATEGORIES', 'Categories');
define('BOX_MAINMENU_ADMINISTRATION_UNITS', 'Units');
define('BOX_MAINMENU_ADMINISTRATION_PROJECTS', 'Projects');
define('BOX_MAINMENU_ADMINISTRATION_ROLES', 'Roles');
define('BOX_MAINMENU_ADMINISTRATION_EMPLOYEES_ROLES', 'Employees-Roles');
define('BOX_MAINMENU_ADMINISTRATION_TARIFFS', 'Tariffs');

define('BOX_HEADING_BENEFITS', 'Benefits');
define('BOX_BENEFITS_CREDIT', 'Prev year :');
define('BOX_BENEFITS_GRANTED', 'This year :');
define('BOX_BENEFITS_TOTAL', 'Total :');
define('BOX_BENEFITS_USED', 'Used :');
define('BOX_BENEFITS_REMAINING', 'Remaining :');
define('BOX_BENEFITS_HOURS', ' hrs');
define('BOX_BENEFITS_DAYS', ' days');

define('BODY_TEXT_LOGIN', 'Login');
define('BODY_TEXT_PASSWORD', 'Password');
define('BODY_TEXT_PASSWORD_VERIFY', 'Repeat password');
define('BODY_TEXT_FULLNAME', 'Full name');
define('BODY_TEXT_EMPLOYEE_ID', 'Personnel number');
define('BODY_TEXT_PROFILE', 'Profile');
define('BODY_TEXT_YES', 'Yes');
define('BODY_TEXT_NO', 'No');
define('BODY_TEXT_NOT_APPLICABLE', 'n/a');
$LOGIN_ERROR_LEVEL = array (0 => '',
                            1 => 'Login failed',
                            2 => 'Passwords do not match',
                            3 => 'Passwords can not be empty');

define('TEXT_ENTRY_NEW', 'New');
define('TEXT_ENTRY_SAVE', 'Save');
define('TEXT_ENTRY_CANCEL', 'Cancel');
define('TEXT_ENTRY_EDIT', 'Edit');
define('TEXT_ENTRY_DELETE', 'Delete');
define('TEXT_ENTRY_DELETE_QUESTION', 'Do you want to delete this entry?');
define('TEXT_ENTRY_DELETE_OK', 'Delete OK');
define('TEXT_ENTRY_DELETE_CANCEL', 'Cancel delete');

define('TEXT_TIMEREGISTRATION_BACK', 'Period back');
define('TEXT_TIMEREGISTRATION_PERIOD', 'Period : ');
define('TEXT_TIMEREGISTRATION_FORWARD', 'Period forward');
define('TEXT_TIMEREGISTRATION_LOCKED', 'This period has been confirmed and cannot be edited');
define('TEXT_TIMEREGISTRATION_IS_EMPTY', 'No activities available');
define('TEXT_TIMEREGISTRATION_CONFIRM', 'Confirm this period. NOTE: After this, changes cannot be made!');
define('TEXT_TIMEREGISTRATION_CONFIRM_QUESTION', 'Do you want to confirm this period?');
define('TEXT_TIMEREGISTRATION_CONFIRM_QUESTION_MINIMUM_HOURS_NOT_MET', 'Minimum hours not met, do you want to confirm this period anyway?');
define('TEXT_TIMEREGISTRATION_CONFIRM_OK', 'Confirm OK');
define('TEXT_TIMEREGISTRATION_CONFIRM_CANCEL', 'Cancel confirm');
define('TEXT_TIMEREGISTRATION_CALENDAR_DESCRIPTION', 'Overview registered amounts per day:');
define('TEXT_TIMEREGISTRATION_TICKET_LOOKUP_IS_EMPTY', 'No selectable tickets available.');

define('TEXT_PROJECTS', 'Projects');
define('TEXT_PROJECTS_NAME', 'Project name');
define('TEXT_PROJECTS_DESCRIPTION', 'Description');
define('TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME', 'Contact customer');
define('TEXT_PROJECTS_CUSTOMERS_REFERENCE', 'Reference customer');
define('TEXT_PROJECTS_START_DATE', 'Start date');
define('TEXT_PROJECTS_END_DATE', 'End date');
define('TEXT_PROJECTS_CALCULATED_HOURS', 'Calculated hours');
define('TEXT_PROJECTS_PERIOD', 'Calculation period');
$PROJECTS_CALCULATED_HOURS_PERIOD = array ('B' => 'Per billing period',
                                           'E' => 'Entire project');
define('TEXT_PROJECTS_HOURS_USED', 'Used hours');
define('TEXT_PROJECTS_HOURS_USED_PERCENTAGE', 'Used hours (%)');
define('TEXT_PROJECTS_QUESTION_ER1', 'Change employees-roles start date');
define('TEXT_PROJECTS_QUESTION_T1', 'Change tariffs start date');
define('TEXT_PROJECTS_QUESTION_ER2', 'Change employees-roles end date');
define('TEXT_PROJECTS_QUESTION_T2', 'Change tariffs end date');
define('TEXT_PROJECTS_SHOW_HISTORY', 'Show history');
define('TEXT_PROJECTS_LISTING_IS_EMPTY', 'No projects available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$PROJECT_ERROR_LEVEL = array (0  => '',
                              1  => 'Project name is missing',
                              2  => 'Start date is missing',
                              3  => 'Business Unit is missing',
                              4  => 'Customer is missing',
                              5  => 'Incorrect date',
                              6  => 'Incorrect calculated hours',
                              7  => 'Employees-Roles exist before new start date',
                              8  => 'Tariffs exist before new start date',
                              9  => 'Activities exist between old and new start date',
                              10 => 'Employees-Roles exist after new end date',
                              11 => 'Tariffs exist after new end date',
                              12 => 'Activities exist between old and new end date',
                              13 => 'Deletion not permitted, roles exist');

define('TEXT_ROLES', 'Role');
define('TEXT_ROLES_NAME', 'Name');
define('TEXT_ROLES_DESCRIPTION', 'Description');
define('TEXT_ROLES_MANDATORY_TICKET_ENTRY', 'Mandatory ticketnr');
define('TEXT_ROLES_CATEGORY', 'Category');
define('TEXT_ROLES_LISTING_IS_EMPTY', 'No roles available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$ROLE_ERROR_LEVEL = array (0 => '',
                           1 => 'Role name is missing',
                           2 => 'Category is missing',
                           3 => 'Deletion not permitted, employees-roles exist');

define('TEXT_EMPLOYEES_ROLES', 'Employee-Role');
define('TEXT_EMPLOYEES_ROLES_START_DATE', 'Start date');
define('TEXT_EMPLOYEES_ROLES_END_DATE', 'End date');
define('TEXT_EMPLOYEES_ROLES_QUESTION_T1', 'Change tariffs start date');
define('TEXT_EMPLOYEES_ROLES_QUESTION_T2', 'Change tariffs end date');
define('TEXT_EMPLOYEES_ROLES_LISTING_IS_EMPTY', 'No employee-roles selected or available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$EMPLOYEE_ROLE_ERROR_LEVEL = array (0  => '',
                                    1  => 'Role is missing',
                                    2  => 'Employee is missing',
                                    3  => 'Start date is missing',
                                    4  => 'Incorrect date',
                                    5  => 'Start date before project start date',
                                    6  => 'End date after project end date',
                                    7  => 'Duplicate entries found',
                                    8  => 'Tariffs exist before new start date',
                                    9  => 'Activities exist between old and new start date',
                                    10 => 'Tariffs exist after new end date',
                                    11 => 'Activities exist between old and new end date',
                                    12 => 'Deletion not permitted, tariffs exist');

define('TEXT_TARIFFS', 'Tarief');
define('TEXT_TARIFFS_AMOUNT', 'Amount');
define('TEXT_TARIFFS_START_DATE', 'Start date');
define('TEXT_TARIFFS_END_DATE', 'End date');
define('TEXT_TARIFFS_LISTING_IS_EMPTY', 'No tariffs available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$TARIFF_ERROR_LEVEL = array (0  => '',
                             1  => 'Amount is missing',
                             2  => 'Unit is missing',
                             3  => 'Start date is missing',
                             4  => 'Incorrect amount',
                             5  => 'Incorrect date',
                             6  => 'Start date before employee-role start date',
                             7  => 'End date after employee-role end date',
                             8  => 'Duplicate entries found',
                             9  => 'Activities exist between old and new start date',
                             10 => 'Activities exist between old and new end date',
                             11 => 'Deletion not permitted, activities exist');

define('TEXT_TIMESHEETS_PERIOD', 'Period');
define('TEXT_TIMESHEETS_START_DATE', 'Start date');
define('TEXT_TIMESHEETS_END_DATE', 'Eind date');
define('TEXT_TIMESHEETS_LOCK', 'Lock');
define('TEXT_TIMESHEETS_UNLOCK', 'Unlock');
define('TEXT_TIMESHEETS_UNCONFIRMED', 'Unconfirmed');
define('TEXT_TIMESHEETS_LISTING_IS_EMPTY', 'No timesheets available');

define('TEXT_ACTIVITY_DAY', 'Day');
define('TEXT_ACTIVITY_PROJECTNAME', 'Project');
define('TEXT_ACTIVITY_ROLENAME', 'Role');
define('TEXT_ACTIVITY_AMOUNT', 'Amount');
define('TEXT_ACTIVITY_UNIT', 'Unit');
define('TEXT_ACTIVITY_TRAVELDISTANCE', 'Travel dist. (km)');
define('TEXT_ACTIVITY_EXPENSES', 'Expenses (&euro;)');
define('TEXT_ACTIVITY_TICKETNUMBER', 'Ticket nr.');
define('TEXT_ACTIVITY_TICKETNUMBER_LOOKUP', 'Get ticket description');
define('TEXT_ACTIVITY_COMMENT', 'Remarks');
define('TEXT_ACTIVITY_COPY', 'Copy last activity of previous (work-)day');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$ACTIVITY_ERROR_LEVEL = array (0  => '',
                               1  => 'No project selected',
                               2  => 'No role selected',
                               3  => 'Incorrect amount',
                               4  => 'No unit selected',
                               5  => 'Incorrect travel distance',
                               6  => 'Incorrect expenses',
                               7  => 'Ticket nr. is mandatory',
                               32 => 'Exceeding calculated hours',
                               33 => 'Activity not valid on selected date',
                               64 => 'No usable data available');

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

define('TEXT_PUNCH_CLOCK_START', 'Start');
define('TEXT_PUNCH_CLOCK_STOP', 'Stop');
define('TEXT_PUNCH_CLOCK_TIME', 'Time');
define('TEXT_PUNCH_CLOCK_ACTIVITY', 'Activity');
define('TEXT_PUNCH_CLOCK_IS_EMPTY', 'No entries found');
define('TEXT_PUNCH_CLOCK_TOTAL', 'TOTAL');

define('TEXT_ANALYSIS_BACK', 'Period back');
define('TEXT_ANALYSIS_PERIOD', 'Period : ');
define('TEXT_ANALYSIS_FORWARD', 'Period forward');

define('TEXT_BUTTON_PDF', 'Create .pdf file');
define('TEXT_BUTTON_CSV', 'Export to .csv file');
define('REPORT_NAME_EMPLOYEES', 'Overview employees');
define('REPORT_NAME_PROJECTS', 'Overview projects');
define('REPORT_NAME_TRAVEL_DISTANCES_AND_EXPENSES', 'Overview travel distances and expenses');
define('REPORT_NAME_CONSOLIDATED_PROJECTS_PER_EMPLOYEE', 'Consolidated overview per employee');
define('REPORT_NAME_TIMESHEETS', 'Overview timesheets');
define('REPORT_EMPLOYEES_ID', 'nr');
define('REPORT_EMPLOYEES_FULLNAME', 'Name');
define('REPORT_EMPLOYEES_LOGIN', 'Login');
define('REPORT_EMPLOYEES_PROJECTLISTING', 'Projectlst');
define('REPORT_EMPLOYEES_TIMEREGISTRATION', 'Timereg');
define('REPORT_EMPLOYEES_ANALYSIS', 'Analysis');
define('REPORT_EMPLOYEES_ADMINISTRATION', 'Admin');
define('REPORT_EMPLOYEES_TIMESHEET_AVAILABLE', 'Timesheet');
define('REPORT_EMPLOYEES_TIMESHEET_LOCKED', 'Locked');
define('REPORT_EMPLOYEES_TRAVEL_DISTANCE', 'Travel dist.(km)');
define('REPORT_EMPLOYEES_EXPENSES', 'Expenses');

define('REPORT_TEXT_CUSTOMER_NAME', 'Customer:');
define('REPORT_TEXT_DATE', 'Date:');
define('REPORT_TEXT_PERIOD', 'Period:');
define('REPORT_TEXT_PROJECT_NAME', 'Project:');
define('REPORT_TEXT_ROLE_NAME', 'Role:');
define('REPORT_TEXT_EMPLOYEE_NAME', 'Employee:');
define('REPORT_TEXT_FOOTER_SIGNATURE_EMPLOYEE', 'Signature employee');
define('REPORT_TEXT_FOOTER_SIGNATURE_EMPLOYEE', 'Signature customer');
define('REPORT_TEXT_FOOTER_ACKNOWLEDGE', 'By signing this timesheet, the constituent acknowledges the specified amounts, units, travel distances and expenses.');

define('REPORT_CHECKBOX_SHOW_USER_RIGHTS', 'Show user rights');
define('REPORT_CHECKBOX_SHOW_TIMESHEET_INFO', 'Show timesheet info');
define('REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE_AND_EXPENSES', 'Show travel distance and expenses');
define('REPORT_CHECKBOX_SHOW_ALL_EMPLOYEES', 'Show all (ex-)employees');
define('REPORT_CHECKBOX_PER_EMPLOYEE', 'Per employee');
define('REPORT_CHECKBOX_SHOW_TARIFF', 'Show tariffs');
define('REPORT_CHECKBOX_SHOW_TRAVEL_DISTANCE', 'Show travel distances');
define('REPORT_CHECKBOX_SHOW_EXPENSES', 'Show expenses');
define('REPORT_CHECKBOX_SHOW_COMMENTS', 'Show comments');
define('REPORT_CHECKBOX_SHOW_SIGNATURE', 'Show signature fields');

define('REPORT_TABLE_HEADER_DATE', 'Date');
define('REPORT_TABLE_HEADER_EMPLOYEE_NAME', 'Employee');
define('REPORT_TABLE_HEADER_PROJECT_NAME', 'Project');
define('REPORT_TABLE_HEADER_ROLE_NAME', 'Role');
define('REPORT_TABLE_HEADER_ACTIVITY_AMOUNT', 'Amount');
define('REPORT_TABLE_HEADER_UNIT_NAME', 'Unit');
define('REPORT_TABLE_HEADER_IS_TARIFF', ' a ');
define('REPORT_TABLE_HEADER_TARIFF', 'Tariff');
define('REPORT_TABLE_HEADER_TRAVEL_DISTANCE', 'km');
define('REPORT_TABLE_HEADER_TRAVEL_DESCRIPTION', 'Description');
define('REPORT_TABLE_HEADER_EXPENSES', 'Expenses');
define('REPORT_TABLE_HEADER_TICKET_NUMBER', 'Ticket nr');
define('REPORT_TABLE_HEADER_TOTAL', 'Total');
define('REPORT_TABLE_HEADER_COMMENT', 'Comment');

define('EXPORT_NAME_ACTIVITIES', 'Export activities');

define('TEXT_CUSTOMERS', 'Customer');
define('TEXT_CUSTOMERS_ID', 'Customernr');
define('TEXT_CUSTOMERS_ID_EXTERNAL', 'External nr');
define('TEXT_CUSTOMERS_NAME', 'Name');
define('TEXT_CUSTOMERS_BILLING_NAME1', 'Billing name 1');
define('TEXT_CUSTOMERS_BILLING_NAME2', 'Billing name 2');
define('TEXT_CUSTOMERS_BILLING_ADDRESS', 'Billing address');
define('TEXT_CUSTOMERS_BILLING_POSTCODE', 'Billing postcode');
define('TEXT_CUSTOMERS_BILLING_CITY', 'Billing city');
define('TEXT_CUSTOMERS_BILLING_COUNTRY', 'Billing country');
define('TEXT_CUSTOMERS_BILLING_EMAIL_ADDRESS', 'Billing e-mail');
define('TEXT_CUSTOMERS_BILLING_PHONE', 'Billing phone');
define('TEXT_CUSTOMERS_BILLING_FAX', 'Billing fax');
define('TEXT_CUSTOMERS_BILLING_SHOW_LOGO', 'Show logo');
define('TEXT_CUSTOMERS_LISTING_IS_EMPTY', 'No customers available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$CUSTOMER_ERROR_LEVEL = array (0  => '',
                               1  => 'Customernumber is missing',
                               2  => 'Customer name is missing',
                               3  => 'Incorrect customernumber',
                               4  => 'Duplicate customernumber',
                               5  => 'Deletion not permitted, projects exist');

define('TEXT_PROFILE', 'Profile');
define('TEXT_PROFILES_NAME', 'Name');
define('TEXT_PROFILES_RIGHTS_LOGIN', 'Login');
define('TEXT_PROFILES_RIGHTS_PROJECTLISTING', 'Projectlst');
define('TEXT_PROFILES_RIGHTS_TIMEREGISTRATION', 'Timereg');
define('TEXT_PROFILES_RIGHTS_ANALYSIS', 'Analysis');
define('TEXT_PROFILES_RIGHTS_ADMINISTRATION', 'Admin');
define('TEXT_PROFILES_LISTING_IS_EMPTY', 'No employees available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$PROFILE_ERROR_LEVEL = array (0 => '',
                              1 => 'Profile name is missing',
                              2 => 'Deletion not permitted, employees exist');

define('TEXT_EMPLOYEES', 'Employee');
define('TEXT_EMPLOYEES_ID', 'Employeenr');
define('TEXT_EMPLOYEES_LOGIN', 'Login');
define('TEXT_EMPLOYEES_FULLNAME', 'Full name');
define('TEXT_EMPLOYEES_RESET_PASSWORD', 'Reset password');
define('TEXT_EMPLOYEES_LISTING_IS_EMPTY', 'No employees available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$EMPLOYEE_ERROR_LEVEL = array (0 => '',
                               1 => 'Employeenumber is missing',
                               2 => 'Login is missing',
                               3 => 'Full name is missing',
                               4 => 'Profile is missing',
                               5 => 'Incorrect employeenumber',
                               6 => 'Duplicate employeenumber',
                               7 => 'Deletion not permitted, empl.-roles/timesheets exist');

define('TEXT_BUSINESS_UNITS', 'Business Unit');
define('TEXT_BUSINESS_UNITS_NAME', 'Name');
define('TEXT_BUSINESS_UNITS_IMAGE', 'Image');
define('TEXT_BUSINESS_UNITS_IMAGE_POSITION', 'Position');
define('TEXT_BUSINESS_UNITS_LISTING_IS_EMPTY', 'No business units available');
$BUSINESS_UNITS_IMAGE_POSITION = array ('L' => 'Left',
                                        'C' => 'Center',
                                        'R' => 'Right');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$BUSINESS_UNIT_ERROR_LEVEL = array (0 => '',
                                    1 => 'Name is missing',
                                    2 => 'Filetype not allowed',
                                    3 => 'Destination not writeable',
                                    4 => 'Destination does not exist',
                                    5 => 'No file uploaded',
                                    6 => 'File not saved',
                                    7 => 'Deletion not permitted, projects exist');

define('TEXT_CATEGORIES', 'Category');
define('TEXT_CATEGORIES_NAME', 'Name');
define('TEXT_CATEGORIES_LISTING_IS_EMPTY', 'No categories available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$CATEGORY_ERROR_LEVEL = array (0 => '',
                               1 => 'Name is missing',
                               2 => 'Deletion not permitted, roles exist');

define('TEXT_UNITS', 'Unit');
define('TEXT_UNITS_NAME', 'Name');
define('TEXT_UNITS_DESCRIPTION', 'Description');
define('TEXT_UNITS_LISTING_IS_EMPTY', 'No units available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$UNIT_ERROR_LEVEL = array (0 => '',
                           1 => 'Name is missing',
                           2 => 'Deletion not permitted, tariffs exist');

define('TEXT_BENEFITS', 'TEXT_BENEFITS');
define('TEXT_BENEFITS_ROLE', 'Type');
define('TEXT_BENEFITS_PROPOSAL', 'Proposal');
define('TEXT_BENEFITS_CREDIT', 'Credit');
define('TEXT_BENEFITS_GRANTED', 'Granted');
define('TEXT_BENEFITS_TOTAL', 'Total');
define('TEXT_BENEFITS_USED', 'Used');
define('TEXT_BENEFITS_REMAINING', 'Remaining');
define('TEXT_BENEFITS_START_DATE', 'Start date');
define('TEXT_BENEFITS_END_DATE', 'End date');
define('TEXT_BENEFITS_COMMENT', 'Comment');
define('TEXT_BENEFITS_LISTING_IS_EMPTY', 'No employee selected or no benefit(s) available');
// Errorlevels  1..31 == severe error
// errorlevels 32..63 == attention required
$BENEFIT_ERROR_LEVEL = array (0  => '',
                               1  => 'Role is missing',
                               2  => 'Employee is missing',
                               3  => 'Start date is missing',
                               4  => 'Incorrect date',
                               5  => 'Start date before project start date',
                               6  => 'End date after project end date',
                               7  => 'Duplicate entries found',
                               8  => 'Tariffs exist before new start date',
                               9  => 'Activities exist between old and new start date',
                               10 => 'Tariffs exist after new end date',
                               11 => 'Activities exist between old and new end date',
                               12 => 'Deletion not permitted, tariffs exist');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . COMPANY_NAME . '</a>');
?>