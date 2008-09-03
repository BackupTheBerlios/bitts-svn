<?php
/****************************************************************************
 * CLASS FILE  : activity.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 september 2008
 * Description : Activity class
 *
 */

  class activity {
    private $activity_id, $date, $amount, $tariff, $travel_distance, $expenses, $ticket_number, $comment, $timesheet_id;

    public function __construct($activity_id = 0) {
      $database = $_SESSION['database'];
      $this->activity_id = $activity_id;

      if ($this->activity_id != 0) {
        $this->activity_id = $database->prepare_input($this->activity_id);

        $activity_query = $database->query("select activities_date, tariffs_id, activities_amount, activities_travel_distance, activities_expenses, activities_ticket_number, activities_comment, timesheets_id from " . TABLE_ACTIVITIES . " where activities_id = '" . (int)$this->activity_id . "'");
        $activity_result = $database->fetch_array($activity_query);

        if (tep_not_null($activity_result)) {
          // Activity exists
          $this->fill($activity_result['activities_date'],
                      $activity_result['activities_amount'],
                      $activity_result['tariffs_id'],
                      $activity_result['activities_travel_distance'],
                      $activity_result['activities_expenses'],
                      $activity_result['activities_ticket_number'],
                      $activity_result['activities_comment'],
                      $activity_result['timesheets_id']);
        }
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
        case 'timesheet_id':
          return $this->timesheet_id;
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

    public function fill($date = '0000-00-00 00:00:00', $amount = 0, $tariff_id = '', $travel_distance = 0, $expenses = 0, $ticket_number = '', $comment = '', $timesheet_id = 0) {
      $this->date = $date;
      $this->amount = $amount;
      $this->tariff = new tariff($tariff_id);
      $this->travel_distance = $travel_distance;
      $this->expenses = $expenses;
      $this->ticket_number = $ticket_number;
      $this->comment = $comment;
      $this->timesheet_id = $timesheet_id;
    }

    public static function validate($name, $value) {
      $value = str_replace(",", ".", $value);
      switch ($name) {
        case 'amount': { // Format #.##
          return (substr_count($value, ".") <= 1 &&
                  is_numeric($value) &&
                  (float)$value > 0);
        }
        case 'travel_distance': { // Format # or empty
          $value = '0' . $value;
          return (substr_count($value, ".") == 0 &&
                  is_numeric($value) &&
                  (int)$value >= 0);
        }
        case 'expenses': { // Format #.## or empty
          $value = '0' . $value;
          return (substr_count($value, ".") <= 1 &&
                  is_numeric($value) &&
                  (float)$value >= 0);
        }
      }
      return false;
    }

    private function format($name, $value) {
      // Format the string
      $value = preg_replace('#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,2}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.' . sprintf('%02d','\\4')", $value);
      switch ($name) {
        case 'amount': { // Format #.##
          $value = number_format(floatval($value), 2);          
          if ($value > 999.99) {
            $value = 999.99;
          } else if ($value < 0) {
            $value = 0.00;
          }
          return $value;
        }
        case 'travel_distance': { // Format #
          $value = intval($value);          
          if ($value < 0) {
            $value = 0;
          }
          return $value;
        }
        case 'expenses': { // Format #.##
          $value = number_format(floatval($value), 2);          
          if ($value > 9999.99) {
            $value = 9999.99;
          } else if ($value < 0) {
            $value = 0.00;
          }
          return $value;
        }
      }
      return false;
    }

    public static function ticket_entry_is_required($tariff_id) {
      return tariff::ticket_entry_is_required($tariff_id);
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new activity if one does not exist and retrieve the id
      if ($this->activity_id == 0) {
        // The activity does not exist
        $database->query("insert into " . TABLE_ACTIVITIES . " (activities_date, activities_amount, tariffs_id, activities_travel_distance, activities_expenses, activities_ticket_number, activities_comment, timesheets_id) values ('" . $this->date . "', '" . $this->amount . "', '" . $this->tariff->tariff_id . "', '" . $this->travel_distance . "', '" . $this->expenses . "', '" . $this->ticket_number . "', '" . $this->comment . "', '" . $this->timesheet_id . "')");
        $this->activity_id = $database->insert_id(); // The proper id is now known
      } else {
        // The activity exists, update the contents
        $this->timesheet_id = $database->prepare_input($this->activity_id);
        $activity_query = $database->query("update " . TABLE_ACTIVITIES . " set activities_date='" . $this->date . "', activities_amount='" . $this->amount . "', tariffs_id='" . $this->tariff->tariff_id . "', activities_travel_distance='" . $this->travel_distance . "', activities_expenses='" . $this->expenses . "', activities_ticket_number='" . $this->ticket_number . "', activities_comment='" . $this->comment . "', timesheets_id='" . $this->timesheet_id . "' where activities_id = '" . (int)$this->activity_id . "'");
      }
    }
  }
?>