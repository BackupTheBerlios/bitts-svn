<?php
/****************************************************************************
 * CODE FILE   : report.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 june 2009
 * Description : Data gathering and reporting functions
 */

  // application_top //
  require('includes/application_top.php');
  // PDF class
  require(DIR_WS_CLASSES . 'pdf.php');

  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->is_analyst)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  switch ($_POST['action']) {
    case 'report_employees':
      // *** Create pdf object ***
      $pdf = new PDF('L');
      $pdf->SetTitle(REPORT_NAME_EMPLOYEES);
      $pdf->SetAuthor(TITLE);
      $pdf->AddPage();
      $pdf->SetFont('Arial', '', 12);
      $pdf->Cell(30, 6, REPORT_TEXT_DATE, 0, 0, 'L');
      $pdf->Cell(100, 6, tep_strftime(DATE_FORMAT_SHORT), 0, 0, 'L');
      $pdf->Ln();
      if ($_POST['show_timesheet_info'] || $_POST['show_travel_distance_and_expenses']) {
        $pdf->Cell(30, 6, REPORT_TEXT_PERIOD, 0, 0, 'L');
        $pdf->Cell(100, 6, $_POST['period'] . '  (' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', tep_periodstartdate($_POST['period']))) . ' - ' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', tep_periodenddate($_POST['period']))) . ')', 0, 0, 'L');
        $pdf->Ln();
      }
      $pdf->Ln(6);
      $employees_array = employee::get_array($_POST['show_all_employees']);
      $table_contents = array();
      $index = 0;
      // Set the header and orientation
      $table_header = array(REPORT_EMPLOYEES_ID,REPORT_EMPLOYEES_FULLNAME);
      $table_contents_orientation = array('R', 'L');
      $column_count = 2;
      if ($_POST['show_user_rights']) {
        // Add to header
        $table_header[$column_count] = REPORT_EMPLOYEES_IS_USER;
        $table_header[$column_count + 1] = REPORT_EMPLOYEES_IS_ANALYST;
        $table_header[$column_count + 2] = REPORT_EMPLOYEES_IS_ADMINISTRATOR;
        // Add to orientation
        $table_contents_orientation[$column_count] = 'C';
        $table_contents_orientation[$column_count + 1] = 'C';
        $table_contents_orientation[$column_count + 2] = 'C';
        $column_count += 3;
      }
      if ($_POST['show_timesheet_info']) {
        // Add to header
        $table_header[$column_count] = REPORT_EMPLOYEES_TIMESHEET_AVAILABLE;
        $table_header[$column_count + 1] = REPORT_EMPLOYEES_TIMESHEET_LOCKED;
        // Add to orientation
        $table_contents_orientation[$column_count] = 'C';
        $table_contents_orientation[$column_count + 1] = 'C';
        $column_count += 2;
      }
      if ($_POST['show_travel_distance_and_expenses']) {
        // Add to header
        $table_header[$column_count] = REPORT_EMPLOYEES_TRAVEL_DISTANCE;
        $table_header[$column_count + 1] = REPORT_EMPLOYEES_EXPENSES;
        // Add to orientation
        $table_contents_orientation[$column_count] = 'R';
        $table_contents_orientation[$column_count + 1] = 'R';
        $column_count += 2;
      }
      // Now fill in the contents
      foreach ($employees_array as $employee) {
        $table_contents[$index] = array($employee->id, $employee->fullname);
        $column_count = 2;
        if ($_POST['show_user_rights']) {
          // Add to contents
          $table_contents[$index][$column_count] = ($employee->is_user?'X':'-');
          $table_contents[$index][$column_count + 1] = ($employee->is_analyst?'X':'-');
          $table_contents[$index][$column_count + 2] = ($employee->is_administrator?'X':'-');
          $column_count += 3;
        }
        if ($_POST['show_timesheet_info'] || $_POST['show_travel_distance_and_expenses']) {
          // In both cases you need the timesheet object
          $timesheet = new timesheet(0, $employee->id, $_POST['period']);
        }
        if ($_POST['show_timesheet_info']) {
          // Add to contents
          $table_contents[$index][$column_count] = ($timesheet->id!=0?'X':'-');
          $table_contents[$index][$column_count + 1] = ($timesheet->locked?'X':'-');
          $column_count += 2;
        }
        if ($_POST['show_travel_distance_and_expenses']) {
          // Add to contents
          $table_contents[$index][$column_count] = $timesheet->total_travel_distance;
          $table_contents[$index][$column_count + 1] = tep_number_db_to_user($timesheet->total_expenses, 2);
          $column_count += 2;
        }
        $index++;
      }
      $pdf->EmployeeTable($table_header, $table_contents, $table_contents_orientation);
      break;
    case 'report_projects':
      $database = $_SESSION['database'];
      // *** Create pdf object ***
      if ($_POST['per_employee']) {
        $pdf = new PDF(); // Create a portrait pdf
      } else {
        $pdf = new PDF('L'); // All the others should be landscape
      }
      $pdf->SetTitle(REPORT_NAME_PROJECTS);
      $pdf->SetAuthor(TITLE);
      $pdf->AddPage();

      $periodstartdate = $database->prepare_input(tep_periodstartdate($_POST['period']));
      $projects_query_string = 'SELECT ts.timesheets_start_date, ts.timesheets_end_date, cus.customers_id, cus.customers_name, bu.business_units_image, pr.projects_id, pr.projects_name, rl.roles_id, rl.roles_name, rl.roles_mandatory_ticket_entry, act.activities_date, emp.employees_id, emp.employees_fullname, act.activities_amount, units.units_id, units.units_name, tar.tariffs_amount, act.activities_travel_distance, act.activities_expenses, act.activities_ticket_number, act.activities_expenses + (act.activities_amount * tar.tariffs_amount) AS total, act.activities_comment ' .
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
      if ($_POST['per_employee']) {
        $projects_query_string .= 'ORDER BY cus.customers_id, pr.projects_id, rl.roles_id, emp.employees_id, units.units_id, act.activities_date';
      } else {
        $projects_query_string .= 'ORDER BY cus.customers_id, pr.projects_id, rl.roles_id, units.units_id, act.activities_date, emp.employees_id';
      }
      $projects_query = $database->query($projects_query_string);
      $projects_array = array();

      $customers_id = '';
      $table_header_set = false;

      while ($projects_result = $database->fetch_array($projects_query)) {
        if ($customers_id != $projects_result['customers_id'] || $projects_id != $projects_result['projects_id'] || $roles_id != $projects_result['roles_id'] || ($_POST['per_employee'] && $employees_id != $projects_result['employees_id'])) {
          $customers_id = $projects_result['customers_id'];
          $projects_id = $projects_result['projects_id'];
          $roles_id = $projects_result['roles_id'];
          if ($_POST['per_employee']) {
            $employees_id = $projects_result['employees_id'];
          }
          if ($table_header_set) {
            // A previous table exists, create a footer for that one
            $pdf->InvoiceTableFooter($total_amount, $total_travel_distance, $total_expenses, $total_value);
            $table_header_set = false; // To prevent the table footer to be written again on the new page
            // New customer, project, role or employee means the timesheet of the previous one has to be signed
            // We know there is a previous one, because the table header is set
            if ($_POST['show_signature']) {
              $pdf->InvoiceSignature();
            }
            $pdf->AddPage();
          }
          $pdf->InvoiceHeader($projects_result['business_units_image'],
                              $projects_result['customers_name'],
                              tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $projects_result['timesheets_start_date'])) . ' - ' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $projects_result['timesheets_end_date'])),
                              $projects_result['projects_name'],
                              $projects_result['roles_name'],
                              ($_POST['per_employee']?$projects_result['employees_fullname']:''));
          $units_id = ''; // To trigger a new table header later on
        }
        if ($units_id != $projects_result['units_id']) {
          if ($table_header_set) {
            // A previous table exists, create a footer for that one
            $pdf->InvoiceTableFooter($total_amount, $total_travel_distance, $total_expenses, $total_value);
            $pdf->Ln(5); // Skip a few mm
          }
          // Create a new table header
          $pdf->InvoiceTableHeader($projects_result['units_name'] . ($_POST['per_employee']&&$_POST['show_tariff']?REPORT_TABLE_HEADER_IS_TARIFF.tep_number_db_to_user($projects_result['tariffs_amount'], 2):''),
                                   $_POST['per_employee'],
                                   $_POST['show_tariff'],
                                   $_POST['show_travel_distance'],
                                   $_POST['show_expenses'],
                                   $projects_result['roles_mandatory_ticket_entry']=='1',
                                   $_POST['show_comment']);
          $table_header_set = true;
          $total_amount = 0.00;
          $total_travel_distance = 0;
          $total_expenses = 0.00;
          $total_value = 0.00;
          $units_id = $projects_result['units_id'];
        }
        // And we're off creating the table contents
        $pdf->InvoiceTableContents(tep_datetouts('%Y-%m-%d', $projects_result['activities_date']),
                                   $projects_result['employees_fullname'],
                                   $projects_result['activities_amount'],
                                   $projects_result['units_name'],
                                   $projects_result['tariffs_amount'],
                                   $projects_result['activities_travel_distance'],
                                   $projects_result['activities_expenses'],
                                   $projects_result['activities_ticket_number'],
                                   $projects_result['total'],
                                   $projects_result['activities_comment']);
        $total_amount += $projects_result['activities_amount'];
        $total_travel_distance += $projects_result['activities_travel_distance'];
        $total_expenses += $projects_result['activities_expenses'];
        $total_value += $projects_result['total'];
      }
      if ($table_header_set) {
        // Create a footer for the last table
         $pdf->InvoiceTableFooter($total_amount, $total_travel_distance, $total_expenses, $total_value);
        // Finally the document needs one last signature
        if ($_POST['show_signature']) {
          $pdf->InvoiceSignature();
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
  // <!-- application_bottom //-->
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  // <!-- application_bottom_eof //--> ?>