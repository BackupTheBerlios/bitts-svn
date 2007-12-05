<?php
/****************************************************************************
 * CLASS FILE  : timesheet.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class timesheet {
    private $timesheet_id, $start_date, $end_date, $activities;

    function timesheet($timesheet_id = '') {
      $this->timesheet_id = $timesheet_id;
      $this->activities = array();

      if (tep_not_null($this->timesheet_id)) {
        $this->timesheet_id = tep_db_prepare_input($this->timesheet_id);

        $timesheet_query = tep_db_query("select start_date, end_date from " . TABLE_TIMESHEETS . " where timesheet_id = '" . (int)$this->timesheet_id . "'");
        $timesheet_result = tep_db_fetch_array($timesheet_query);

        $this->$start_date = $timesheet_result['start_date'];
        $this->$end_date = $timesheet_result['end_date'];

        // Retrieve all activities for this timesheet
        $this->activities = activity::get_array($this->timesheet_id);
      }
    }
  }
?>