<?php
/****************************************************************************
 * CLASS FILE  : activity.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class activity {
    private $activity_id, $date, $employee_role, $hours, $tariff, $travel_distance, $expenses, $ticket_number, $comment;

    function activity($activity_id = '') {
      $this->activity_id = $activity_id;

      if (tep_not_null($this->activity_id)) {
        $this->activity_id = tep_db_prepare_input($this->activity_id);

        $activity_query = tep_db_query("select date, employee_role_id, hours, tariff_id, travel_distance, expenses, ticket_number, comment from " . TABLE_ACTIVITIES . " where activity_id = '" . (int)$this->activity_id . "'");
        $activity_result = tep_db_fetch_array($activity_query);

        $this->$date = $activity_result['date'];
        $this->$employee_role = new employee_role($activity_result['employee_role_id']);
        $this->$hours = $activity_result['hours'];
        $this->$tariff = new tariff($activity_result['tariff_id']);
        $this->$travel_distance = $activity_result['travel_distance'];
        $this->$expenses = $activity_result['expenses'];
        $this->$ticket_number = $activity_result['ticket_number'];
        $this->$comment = $activity_result['comment'];
      }
    }

    public static function get_array($timesheet_id = '') {
      $activity_array = array();

      if (tep_not_null($timesheet_id)) {
        $index = 0;
        $timesheet_id = tep_db_prepare_input($timesheet_id);
        $activities_query = tep_db_query("select activity_id from " . TABLE_ACTIVITIES . " where timesheet_id = '" . (int)$timesheet_id . "'");
        while ($activities_result = tep_db_fetch_array($activities_query)) {
          $activity_array[$index] = new activity($activities_result['activity_id']);
          $index++;
        }
      }

      return $activity_array;
    }
  }
?>