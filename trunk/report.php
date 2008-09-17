<?php
/****************************************************************************
 * CODE FILE   : report.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 september 2008
 * Description : Data gathering and reporting functions
 */

  // application_top //
  require('includes/application_top.php');
  // PDF class
  require(DIR_WS_CLASSES . 'pdf.php');

  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee_login']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));

  switch ($_POST['action']) {
    case 'report_employees':
      // *** Create pdf object ***
      $pdf = new PDF();
      $pdf->SetTitle(REPORT_NAME_EMPLOYEES);
      $pdf->SetAuthor(TITLE);
      $pdf->AddPage();
      $employees_array = employee::get_array();
      $table_contents = array();
      $index = 0;
      foreach ($employees_array as $employee) {
        $table_contents[$index] = array($employee->employee_id, $employee->fullname, ($employee->is_user?'X':'-'), ($employee->is_analyst?'X':'-'), ($employee->is_administrator?'X':'-'));
        $index++;
      }
      $pdf->FancyTable(array(REPORT_EMPLOYEES_ID,REPORT_EMPLOYEES_FULLNAME,REPORT_EMPLOYEES_IS_USER,REPORT_EMPLOYEES_IS_ANALYST,REPORT_EMPLOYEES_IS_ADMINISTRATOR),$table_contents);
      break;
    case 'report_projects':
      $database = $_SESSION['database'];
      // *** Create pdf object ***
      if ($_POST['per_employee']=='true') {
        $pdf = new PDF(); // Create a portrait pdf
      } else {
        $pdf = new PDF('L'); // All the others should be landscape
      }
      $pdf->SetTitle(REPORT_NAME_PROJECTS);
      $pdf->SetAuthor(TITLE);

      $periodstartdate = $database->prepare_input(tep_periodstartdate($_POST['period']));
      $projects_query_string = 'SELECT ts.timesheets_start_date, ts.timesheets_end_date, cus.customers_id, cus.customers_name, bu.business_units_image, pr.projects_id, pr.projects_name, bu.business_units_image, rl.roles_id, rl.roles_name, rl.roles_mandatory_ticket_entry, er.employees_roles_id, act.activities_date, emp.employees_fullname, act.activities_amount, units.units_id, units.units_name, tar.tariffs_amount, act.activities_travel_distance, act.activities_expenses, act.activities_ticket_number, act.activities_expenses + (act.activities_amount * tar.tariffs_amount) AS total ' .
                               'FROM ' . TABLE_TIMESHEETS . ' AS ts ' .
                               'INNER JOIN (' . TABLE_EMPLOYEES . ' AS emp, ' . TABLE_ACTIVITIES . ' AS act, ' . TABLE_UNITS . ', ' . TABLE_TARIFFS . ' AS tar, ' . TABLE_EMPLOYEES_ROLES . ' AS er, ' . TABLE_ROLES . ' AS rl, ' . TABLE_PROJECTS . ' AS pr, ' . TABLE_CUSTOMERS . ' AS cus, ' . TABLE_BUSINESS_UNITS . ' AS bu) ' .
                               'ON (ts.employees_id = emp.employees_id ' .
                               'AND act.timesheets_id = ts.timesheets_id ' .
                               'AND act.tariffs_id = tar.tariffs_id ' .
                               'AND units.units_id = tar.units_id ' .
                               'AND er.employees_roles_id = tar.employees_roles_id ' .
                               'AND rl.roles_id = er.roles_id ' .
                               'AND pr.projects_id = rl.projects_id ' .
                               'AND cus.customers_id = pr.customers_id ' .
                               'AND bu.business_units_id = pr.business_units_id) ' .
                               'WHERE ts.timesheets_start_date = "' . $periodstartdate . '" ';
      if ($_POST['per_employee']=='true') {
        $projects_query_string .= 'ORDER BY cus.customers_id, pr.projects_id, rl.roles_id, er.employees_roles_id, tar.tariffs_id, act.activities_date';
      } else {
        // Needs some work
        $projects_query_string .= 'ORDER BY cus.customers_id, pr.projects_id, rl.roles_id, act.activities_date, emp.employees_id';
      }
      $projects_query = $database->query($projects_query_string);
      $projects_array = array();

      if ($_POST['per_employee']=='true') {
        $employees_roles_id = '';
        $table_header_set = false;
        $total_amount = 0.00;
        $total_travel_distance = 0;
        $total_expenses = 0.00;
        $total_value = 0.00;

        while ($projects_result = $database->fetch_array($projects_query)) {
          if ($employees_roles_id != $projects_result['employees_roles_id']) {
            $employees_roles_id = $projects_result['employees_roles_id'];
            if ($table_header_set) {
              $pdf->InvoiceTableFooter($total_amount, $total_travel_distance, $total_expenses, $total_value);
              $table_header_set = false;
            }
            $pdf->AddPage();
            $pdf->InvoiceHeader($projects_result['business_units_image'],
                                $projects_result['customers_name'],
                                tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($projects_result['timesheets_start_date'])) . ' - ' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($projects_result['timesheets_end_date'])),
                                $projects_result['projects_name'],
                                $projects_result['roles_name'],
                                $projects_result['employees_fullname']);
            $total_amount = 0.00;
            $total_travel_distance = 0;
            $total_expenses = 0.00;
            $total_value = 0.00;
            $pdf->InvoiceTableHeader($projects_result['units_name'] . ($_POST['show_tariff']=='true'?REPORT_TABLE_HEADER_IS_TARIFF.tep_number_db_to_user($projects_result['tariffs_amount'], 2):''),
                                     $_POST['per_employee']=='true',
                                     $_POST['show_tariff']=='true',
                                     $_POST['show_travel_distance']=='true',
                                     $_POST['show_expenses']=='true',
                                     $projects_result['roles_mandatory_ticket_entry']=='1');
            $table_header_set = true;
          }
          $pdf->InvoiceTableContents(tep_datetouts($projects_result['activities_date']),
                                     $projects_result['employees_fullname'],
                                     $projects_result['activities_amount'],
                                     $projects_result['units_name'],
                                     $projects_result['tariffs_amount'],
                                     $projects_result['activities_travel_distance'],
                                     $projects_result['activities_expenses'],
                                     $projects_result['activities_ticket_number'],
                                     $projects_result['total']);
          $total_amount += $projects_result['activities_amount'];
          $total_travel_distance += $projects_result['activities_travel_distance'];
          $total_expenses += $projects_result['activities_expenses'];
          $total_value += $projects_result['total'];
        }
        if ($table_header_set) {
          $pdf->InvoiceTableFooter($total_amount, $total_travel_distance, $total_expenses, $total_value);
        }
      }

      break;
    case 'report_timesheets':
      break;
    default:
      // *** Create pdf object ***
      $pdf = new PDF();
  }

  $pdf->Output($pdf->title . '.pdf', 'D');
?>