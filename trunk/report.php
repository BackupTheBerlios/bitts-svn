<?php
/****************************************************************************
 * CODE FILE   : report.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 15 september 2008
 * Description : Data gathering and reporting functions
 */

  // application_top //
  require('includes/application_top.php');
  // PDF class
  require(DIR_WS_CLASSES . 'pdf.php');

  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee_login']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));

  switch (tep_post_or_get('action')) {
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

      function write_table_footer($pdf, $total_value) {
        $pdf->SetX(220);
        $pdf->SetFont('', 'B');
        $pdf->Cell(40, 6, REPORT_TABLE_HEADER_TOTAL, 0, 0, 'R');
        $pdf->Cell(0, 6, tep_number_db_to_user($total_value, 2), 'T', 0, 'R');
        $pdf->SetFont('');
        $pdf->Ln();
      }

      $database = $_SESSION['database'];
      // *** Create pdf object ***
      $pdf = new PDF('L');
      $pdf->SetTitle(REPORT_NAME_PROJECTS);
      $pdf->SetAuthor(TITLE);

      $projects_query_string = 'SELECT ts.timesheets_start_date, ts.timesheets_end_date, cus.customers_id, cus.customers_name, pr.projects_id, pr.projects_name, rl.roles_id, rl.roles_name,act.activities_date, emp.employees_fullname, act.activities_ticket_number, act.activities_amount, units.units_name, tar.tariffs_amount, act.activities_amount * tar.tariffs_amount AS total ' .
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
                               'WHERE ts.timesheets_start_date = "2008-08-01" ' .
                               'ORDER BY ts.timesheets_start_date, cus.customers_id, pr.projects_id, rl.roles_id, act.activities_date, emp.employees_id';
      $projects_query = $database->query($projects_query_string);
      $projects_array = array();
      $customers_id = '';
      while ($projects_result = $database->fetch_array($projects_query)) {
        if ($customers_id != $projects_result['customers_id']) {
          $customers_id = $projects_result['customers_id'];
          $projects_id = '';
          $roles_id = '';
          if ($table_header_set) {
            write_table_footer($pdf, $total_value);
            $table_header_set = false;
          }
          $pdf->AddPage();
          $pdf->ChapterTitle($projects_result['customers_id'], $projects_result['customers_name'] . '     (' . REPORT_TEXT_PERIOD . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($projects_result['timesheets_start_date'])) . ' - ' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($projects_result['timesheets_end_date'])) . ')');
        }
        if ($projects_id != $projects_result['projects_id']) {
          $projects_id = $projects_result['projects_id'];
          $roles_id = '';
          if ($table_header_set) {
            write_table_footer($pdf, $total_value);
            $table_header_set = false;
          }
          $pdf->Cell(0, 6, $projects_result['projects_id'] . ' : ' . $projects_result['projects_name'], 1, 1);
          $pdf->Ln(4);
        }
        if ($roles_id != $projects_result['roles_id']) {
          $roles_id = $projects_result['roles_id'];
          if ($table_header_set) {
            write_table_footer($pdf, $total_value);
            $table_header_set = false;
          }
          $pdf->SetX(20);
          $pdf->Cell(0, 6, $projects_result['roles_id'] . ' : ' . $projects_result['roles_name'], 1, 1);
          $pdf->Ln(4);
        }
        if (!$table_header_set) {
          $pdf->SetX(40);
          $pdf->SetFont('', 'B');
          $pdf->SetFillColor(191, 191, 191);
          $pdf->Cell(30, 6, REPORT_TABLE_HEADER_DATE, 'R', 0, 'C', true);
          $pdf->Cell(55, 6, REPORT_TABLE_HEADER_EMPLOYEE_NAME, 'LR', 0, 'C', true);
          $pdf->Cell(35, 6, REPORT_TABLE_HEADER_TICKET_NUMBER, 'LR', 0, 'C', true);
          $pdf->Cell(20, 6, REPORT_TABLE_HEADER_ACTIVITY_AMOUNT, 'LR', 0, 'C', true);
          $pdf->Cell(60, 6, REPORT_TABLE_HEADER_UNITS_NAME, 'LR', 0, 'C', true);
          $pdf->Cell(20, 6, REPORT_TABLE_HEADER_TARIFF, 'LR', 0, 'C', true);
          $pdf->Cell(0, 6, REPORT_TABLE_HEADER_TOTAL, 'L', 0, 'C', true);
          $pdf->Ln();
          $pdf->SetFont('');
          $table_header_set = true;
          $total_value = 0;
        }
        $pdf->SetX(40);
        $pdf->Cell(30, 6, tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($projects_result['activities_date'])), 0, 0, 'C');
        $pdf->Cell(55, 6, $projects_result['employees_fullname']);
        $pdf->Cell(35, 6, $projects_result['activities_ticket_number'], 0, 0, 'C');
        $pdf->Cell(20, 6, tep_number_db_to_user($projects_result['activities_amount'], 2), 0, 0, 'R');
        $pdf->Cell(60, 6, $projects_result['units_name']);
        $pdf->Cell(20, 6, tep_number_db_to_user($projects_result['tariffs_amount'], 2), 0, 0, 'R');
        $pdf->Cell(0, 6, tep_number_db_to_user($projects_result['total'], 2), 0, 0, 'R');
        $total_value += $projects_result['total'];
        $pdf->Ln();
      }
      if ($table_header_set) {
        write_table_footer($pdf, $total_value);
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