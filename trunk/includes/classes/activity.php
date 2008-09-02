<?php
/****************************************************************************
 * CLASS FILE  : activity.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 02 september 2008
 * Description : Activity class
 *
 */

  class activity {
    private $activity_id, $date, $tariff, $amount, $travel_distance, $expenses, $ticket_number, $comment;

    public function __construct($activity_id = '') {
      $database = $_SESSION['database'];
      $this->activity_id = $activity_id;

      if (tep_not_null($this->activity_id)) {
        $this->activity_id = $database->prepare_input($this->activity_id);

        $activity_query = $database->query("select activities_date, tariffs_id, activities_amount, activities_travel_distance, activities_expenses, activities_ticket_number, activities_comment from " . TABLE_ACTIVITIES . " where activities_id = '" . (int)$this->activity_id . "'");
        $activity_result = $database->fetch_array($activity_query);

        $this->date = $activity_result['activities_date'];
        $this->tariff = new tariff($activity_result['tariffs_id']);
        $this->amount = $activity_result['activities_amount'];
        $this->travel_distance = $activity_result['activities_travel_distance'];
        $this->expenses = $activity_result['activities_expenses'];
        $this->ticket_number = $activity_result['activities_ticket_number'];
        $this->comment = $activity_result['activities_comment'];
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'activity_id':
          return $this->activity_id;
      	case 'date':
          return $this->date;
      	case 'project_name':
          return $this->tariff->project_name;
       	case 'role_name':
          return $this->tariff->role_name;
        case 'tariff':
          return $this->tariff;
        case 'amount':
          return $this->amount;
        case 'unit_name':
          return $this->tariff->unit_name;
        case 'travel_distance':
          return $this->travel_distance;
        case 'expenses':
          return $this->expenses;
        case 'ticket_number':
          return $this->ticket_number;
        case 'comment':
          return $this->comment;
      }
      return null;
    }

    public static function get_array($timesheet_id = '') {
      $database = $_SESSION['database'];
      $activity_array = array();

      if (tep_not_null($timesheet_id)) {
        $index = 0;
        $timesheet_id = $database->prepare_input($timesheet_id);
        $activities_query = $database->query("select activities_id from " . TABLE_ACTIVITIES . " where timesheets_id = '" . (int)$timesheet_id . "' order by activities_date");
        while ($activities_result = $database->fetch_array($activities_query)) {
          $activity_array[$index] = new activity($activities_result['activities_id']);
          $index++;
        }
      }

      return $activity_array;
    }
  }
?>