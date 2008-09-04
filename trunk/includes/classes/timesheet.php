<?php
/****************************************************************************
 * CLASS FILE  : timesheet.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 04 september 2008
 * Description : Timesheet class
 *
 */

  class timesheet {
    private $timesheet_id, $start_date, $end_date, $locked, $employee_id, $activities;

    public function __construct($timesheet_id = 0, $employee_id = 0, $period = null) {
      $database = $_SESSION['database'];
      $this->timesheet_id = $timesheet_id;
      $this->activities = array();

      if ($this->timesheet_id != 0) {
        // Retrieve timesheet by id
        $this->timesheet_id = $database->prepare_input($this->timesheet_id);
        $timesheet_query = $database->query("select timesheets_id, timesheets_start_date, timesheets_end_date, employees_id from " . TABLE_TIMESHEETS . " where timesheets_id = '" . (int)$this->timesheet_id . "'");
      } else {
        // Timesheet might exist but we do not know the id
      	// Try to retrieve the timesheet for the given employee and period
        $this->employee_id = $database->prepare_input($employee_id);
      	$this->start_date = $database->prepare_input(tep_periodstartdate($period));
        $timesheet_query = $database->query("select timesheets_id, timesheets_start_date, timesheets_end_date, timesheets_locked, employees_id from " . TABLE_TIMESHEETS . " where employees_id = '" . (int)$this->employee_id . "' and timesheets_start_date = '" . $this->start_date . "'");
      }
      $timesheet_result = $database->fetch_array($timesheet_query);

      if (tep_not_null($timesheet_result)) {
        // Timesheet exists
        $this->timesheet_id = $timesheet_result['timesheets_id'];
      	$this->fill($timesheet_result['timesheets_start_date'],
                    $timesheet_result['timesheets_end_date'],
                    ($timesheet_result['timesheets_locked'] == 1),
                    $timesheet_result['employees_id']);

        // Retrieve all activities for this timesheet (if any exist)
        $this->activities = activity::get_array($this->timesheet_id);
      } else {
      	// Timesheet does not exist, fill with the given values
      	$this->fill(tep_periodstartdate($period), tep_periodenddate($period), false, $employee_id);
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
        case 'locked':
          return $this->locked;
        case 'empty':
          return sizeof($this->activities) == 0;
        case 'activities':
          return $this->activities;
      }
      return null;
    }

    public function fill($start_date = '0000-00-00 00:00:00', $end_date = '0000-00-00 00:00:00', $locked = false, $employee_id = 0) {
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->locked = $locked;
      $this->employee_id = $employee_id;
    }

    public function save_activity($activity_id = 0, $date = 0, $amount = 0, $tariff_id = 0, $travel_distance = 0, $expenses = 0, $ticket_number = '', $comment = '') {
      // First check if timesheet has been saved previously (timesheet_id != 0)
      // If not, first save current timesheet
      if ($this->timesheet_id == 0) {
        $this->save();
      }
      // Save activity
      $activity = new activity($activity_id);
      $activity->fill($date, $amount, $tariff_id, $travel_distance, $expenses, $ticket_number, $comment, $this->timesheet_id);
      $activity->save();
    }

    public function delete_activity($activity_id = 0) {
      // Delete activity
      $activity = new activity($activity_id);
      $activity->delete();
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new timesheet if one does not exist and retrieve the id
      if ($this->timesheet_id == 0) {
        // The timesheet does not exist
        $database->query("insert into " . TABLE_TIMESHEETS . " (timesheets_start_date, timesheets_end_date, timesheets_locked, employees_id) values ('" . $this->start_date . "', '" . $this->end_date . "', '" . ($this->locked?1:0) . "', '" . $this->employee_id . "')");
        $this->timesheet_id = $database->insert_id(); // The proper id is now known
      } else {
        // The timesheet exists, update the contents
        $this->timesheet_id = $database->prepare_input($this->timesheet_id);
        $timesheet_query = $database->query("update " . TABLE_TIMESHEETS . " set timesheets_start_date='" . $this->start_date . "', timesheets_end_date='" . $this->end_date . "', timesheets_locked='" . ($this->locked?1:0) . "', employees_id='" . $this->employee_id . "' where timesheets_id = '" . (int)$this->timesheet_id . "'");
      }
    }
  }
?>