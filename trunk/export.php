<?php
/****************************************************************************
 * CODE FILE   : export.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 june 2009
 * Description : Data gathering and export functions
 */

  // application_top //
  require('includes/application_top.php');
  // CSV class
  require(DIR_WS_CLASSES . 'csv.php');

  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->is_analyst)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

    // Create a CSV object
    $csv = new CSV($_POST['action'] . '.csv', ';', ''); // Delimiters chosen to be ms-excel compatible

    switch ($_POST['action']) {
    case 'export_activities':
      $database = $_SESSION['database'];
      $periodstartdate = $database->prepare_input(tep_periodstartdate($_POST['period']));
      $activities_query_string = 'SELECT cus.customers_id, cus.customers_name, ts.timesheets_start_date, ts.timesheets_end_date, pr.projects_name, bu.business_units_name, rl.roles_name, cat.categories_name, act.activities_date, emp.employees_id, emp.employees_fullname, act.activities_amount, units.units_name, tar.tariffs_amount, act.activities_travel_distance, act.activities_expenses, act.activities_ticket_number, act.activities_comment ' .
                                 'FROM ' . TABLE_TIMESHEETS . ' AS ts ' .
                                 'INNER JOIN (' . TABLE_EMPLOYEES . ' AS emp, ' . TABLE_ACTIVITIES . ' AS act, ' . TABLE_UNITS . ', ' . TABLE_TARIFFS . ' AS tar, ' . TABLE_EMPLOYEES_ROLES . ' AS er, ' . TABLE_ROLES . ' AS rl, ' . TABLE_CATEGORIES . ' AS cat, ' . TABLE_PROJECTS . ' AS pr, ' . TABLE_CUSTOMERS . ' AS cus, ' . TABLE_BUSINESS_UNITS . ' AS bu) ' .
                                 'ON (ts.employees_id = emp.employees_id ' .
                                 'AND act.timesheets_id = ts.timesheets_id ' .
                                 'AND act.tariffs_id = tar.tariffs_id ' .
                                 'AND units.units_id = tar.units_id ' .
                                 'AND er.employees_roles_id = tar.employees_roles_id ' .
                                 'AND rl.categories_id = cat.categories_id ' .
                                 'AND rl.roles_id = er.roles_id ' .
                                 'AND pr.projects_id = rl.projects_id ' .
                                 'AND cus.customers_id = pr.customers_id ' .
                                 'AND bu.business_units_id = pr.business_units_id) ' .
                                 'WHERE ts.timesheets_start_date = "' . $periodstartdate . '" ' . 
                                 'ORDER BY cus.customers_id, pr.projects_id, rl.roles_id, act.activities_date, emp.employees_id, units.units_id';
      $activities_query = $database->query($activities_query_string);
      $csv->addrow(array('customers_id', 'customers_name', 'period_start_date', 'period_end_date', 'projects_name', 'business_units_name', 'roles_name', 'activities_date', 'employees_id', 'employees_fullname', 'amount', 'units_name', 'tariff', 'travel_distance', 'expenses', 'ticket_number', 'comment', 'categories_name'));
      while ($activities_result = $database->fetch_array($activities_query)) {
        $csv->addrow(array($activities_result['customers_id'], $activities_result['customers_name'], tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $activities_result['timesheets_start_date'])), tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $activities_result['timesheets_end_date'])), $activities_result['projects_name'], $activities_result['business_units_name'], $activities_result['roles_name'], tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $activities_result['activities_date'])), $activities_result['employees_id'], $activities_result['employees_fullname'], tep_number_db_to_user($activities_result['activities_amount'], 2), $activities_result['units_name' ], tep_number_db_to_user($activities_result['tariffs_amount'], 2), $activities_result['activities_travel_distance'], tep_number_db_to_user($activities_result['activities_expenses'], 2), $activities_result['activities_ticket_number'], $activities_result['activities_comment'], $activities_result['categories_name']));
      }
      break;
  }

  // Get the show on the road
  $csv->output('D');

  // <!-- application_bottom //-->
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  // <!-- application_bottom_eof //-->
?>