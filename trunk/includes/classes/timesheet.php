<?php
/****************************************************************************
 * CLASS FILE  : timesheet.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 april 2008
 * Description : Timesheet class
 *
 */

  class timesheet {
    private $timesheet_id, $start_date, $end_date, $employee_id, $activities;

    public function __construct($timesheet_id = 0, $employee_id = 0, $period = null) {
      $database = $_SESSION['database'];
      $this->activities = array();

      if ($timesheet_id != 0) {
        $this->timesheet_id = $database->prepare_input($this->timesheet_id);

        $timesheet_query = $database->query("select timesheets_id, timesheets_start_date, timesheets_end_date, employees_id from " . TABLE_TIMESHEETS . " where timesheets_id = '" . (int)$this->timesheet_id . "'");
      } else {
      	// Retrieve the timesheet for the current period or create a new one
        $this->employee_id = $database->prepare_input($employee_id);
      	$this->start_date = $database->prepare_input(tep_periodstartdate($period));

        $timesheet_query = $database->query("select timesheets_id, timesheets_start_date, timesheets_end_date, employees_id from " . TABLE_TIMESHEETS . " where employees_id = '" . (int)$this->employee_id . "' and timesheets_start_date = '" . $this->start_date . "'");
      }

      $timesheet_result = $database->fetch_array($timesheet_query);

      if (tep_not_null($timesheet_result)) {
        // Timesheet exists
      	$this->fill($timesheet_result['timesheets_id'],
                    $timesheet_result['timesheets_start_date'],
                    $timesheet_result['timesheets_end_date'],
                    $timesheet_result['employees_id']);

        // Retrieve all activities for this timesheet (if any exist)
//        $this->activities = activity::get_array($this->timesheet_id);
      } else {
      	// Timesheet does not exist
      	$this->fill(0, tep_periodstartdate($period), tep_periodenddate($period), $employee_id);
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'timesheet_id':
          return $this->timesheet_id;
      	case 'period':
          return tep_datetoperiod($this->start_date);
        case 'start_date':
          return $this->start_date;
        case 'end_date':
          return $this->end_date;
        case 'activities':
          return ($this->activities);
      }
      return null;
    }

    private function fill($timesheet_id = 0, $start_date = '0000-00-00 00:00:00', $end_date = '0000-00-00 00:00:00', $employee_id = 0) {
      $this->timesheet_id = $timesheet_id;
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->employee_id = $employee_id;
    }
  }
?>