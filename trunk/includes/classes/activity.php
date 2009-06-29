<?php
/****************************************************************************
 * CLASS FILE  : activity.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 june 2009
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
          $this->fill(tep_datetouts('%Y-%m-%d', $activity_result['activities_date']),
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
      	case 'projects_id':
          return $this->tariff->projects_id;
      	case 'project_name':
          return $this->tariff->project_name;
        case 'roles_id':
          return $this->tariff->roles_id;
        case 'role_name':
          return $this->tariff->role_name;
        case 'tariff':
          return $this->tariff;
        case 'amount':
          return $this->amount;
        case 'unit_name':
          return $this->tariff->unit->name;
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

    public static function get_array($timesheet_id = 0, $sort_order = 'activities_date asc') {
      $database = $_SESSION['database'];
      $activity_array = array();

      if (tep_not_null($timesheet_id)) {
        $index = 0;
        $timesheet_id = $database->prepare_input($timesheet_id);
        $activities_query = $database->query("select activities_id from " . TABLE_ACTIVITIES . " a" .
                                             " inner join (" . TABLE_PROJECTS . " p, " . TABLE_ROLES . " r, " . TABLE_EMPLOYEES_ROLES . " er, " . TABLE_TIMESHEETS . " ts, " . TABLE_TARIFFS . " t)" . 
                                             " on (p.projects_id=r.projects_id and r.roles_id=er.roles_id and er.employees_roles_id=t.employees_roles_id and a.timesheets_id=ts.timesheets_id and t.tariffs_id=a.tariffs_id)" .
                                             " where ts.timesheets_id = '" . (int)$timesheet_id . "'" .
                                             " order by " . $sort_order);
        while ($activities_result = $database->fetch_array($activities_query)) {
          $activity_array[$index] = new activity($activities_result['activities_id']);
          $index++;
        }
      }

      return $activity_array;
    }

    public function fill($date = 0, $amount = 0, $tariffs_id = '', $travel_distance = 0, $expenses = 0, $ticket_number = '', $comment = '', $timesheet_id = 0) {
      $this->date = $date;
      $this->amount = $this->format('amount', $amount);
      $this->tariff = new tariff($tariffs_id);
      $this->travel_distance = $this->format('travel_distance', $travel_distance);
      $this->expenses = $this->format('expenses', $expenses);
      $this->ticket_number = $ticket_number;
      $this->comment = $comment;
      $this->timesheet_id = $timesheet_id;
    }

    public function get_activities($parents_id, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2) {
      $database = $_SESSION['database'];
      $activities_array = array();

      if (tep_not_null($parents_id)) {
        $index = 0;
        $activities_query = $database->query("select activities_id from " . TABLE_ACTIVITIES . " where tariffs_id in (" . $parents_id . ") and " . $column_name1 . " " . $comparison1 . " '" . $value1 . "'" . (tep_not_null($delimiter)?' ' . $delimiter . " " . $column_name2 . " " . $comparison2 . " '" . $value2 . "'":''));
        while ($activities_result = $database->fetch_array($activities_query)) {
          $activities_array[$index] = new activity($activities_result['activities_id']);
          $index++;
        }
      }

      return $activities_array;
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

    public static function format($name, $value) {
      // Format the string
      $value = str_replace(",", ".", $value);
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

    public static function ticket_entry_is_required($tariffs_id) {
      return tariff::ticket_entry_is_required($tariffs_id);
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new activity if one does not exist and retrieve the id
      if ($this->activity_id == 0) {
        // The activity does not exist
        $database->query("insert into " . TABLE_ACTIVITIES . " (activities_date, activities_amount, tariffs_id, activities_travel_distance, activities_expenses, activities_ticket_number, activities_comment, timesheets_id) values ('" . tep_strftime(DATE_FORMAT_DATABASE, $this->date) . "', '" . $this->amount . "', '" . $this->tariff->id . "', '" . $this->travel_distance . "', '" . $this->expenses . "', '" . $this->ticket_number . "', '" . $this->comment . "', '" . $this->timesheet_id . "')");
        $this->activity_id = $database->insert_id(); // The proper id is now known
      } else {
        // The activity exists, update the contents
        $activity_query = $database->query("update " . TABLE_ACTIVITIES . " set activities_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->date) . "', activities_amount='" . $this->amount . "', tariffs_id='" . $this->tariff->id . "', activities_travel_distance='" . $this->travel_distance . "', activities_expenses='" . $this->expenses . "', activities_ticket_number='" . $this->ticket_number . "', activities_comment='" . $this->comment . "' where activities_id = '" . (int)$this->activity_id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $activity_query = $database->query("delete from " . TABLE_ACTIVITIES . " where activities_id = '" . (int)$this->activity_id . "'");
      // Reset id, otherwise one might think this activity (still) exists in db
      $this->activity_id = 0;
    }

    public static function get_former_activity_id($employee_id, $selected_date = null) {
      $db_date = tep_strftime(DATE_FORMAT_DATABASE, $selected_date);
      $database = $_SESSION['database'];

      $activity_query = $database->query("SELECT a.activities_id, a.tariffs_id" .
                                         " FROM " . TABLE_ACTIVITIES . " AS a, " . TABLE_TIMESHEETS . " AS t" .
                                         " WHERE a.timesheets_id = t.timesheets_id" . 
                                         " AND t.employees_id = '" . $employee_id . "'" .
                                         " AND a.activities_date < '" . $db_date . "'" .
                                         " ORDER BY a.activities_date DESC, a.activities_id DESC");
      $activity_result = $database->fetch_array($activity_query);

      if (tep_not_null($activity_result)) {
        // There is a resultset, keep the activity_id for now and
        // check if the activity is valid for the given employee and date
        $former_activity_id = $activity_result['activities_id'];
        $tariffs_id = $activity_result['tariffs_id'];

        // The complicated way: check the entire tree for validation
        /* $activity_query = $database->query("SELECT 1" .
                                           " FROM " . TABLE_PROJECTS . " AS p, " . TABLE_ROLES . " AS r, " . TABLE_EMPLOYEES_ROLES . " AS er, " . TABLE_TARIFFS . " AS t" .
                                           " WHERE p.projects_id = r.projects_id" .
                                           " AND r.roles_id = er.roles_id" .
                                           " AND er.employees_roles_id = t.employees_roles_id" .
                                           " AND er.employees_id = '" . $employee_id . "'" .
                                           " AND t.tariffs_id = '" . $tariffs_id . "'" .
                                           " AND p.projects_start_date <= '" . $db_date . "'" .
                                           " AND p.projects_end_date >= '" . $db_date . "'" .
                                           " AND er.employees_roles_start_date <= '" . $db_date . "'" .
                                           " AND er.employees_roles_end_date >= '" . $db_date . "'" .
                                           " AND t.tariffs_start_date <= '" . $db_date . "'" .
                                           " AND t.tariffs_end_date >= '" . $db_date . "'"); */
        // The easy way: just validate against the obtained tariffs_id
        $activity_query = $database->query("SELECT 1" .
                                           " FROM " . TABLE_TARIFFS . " AS t" .
                                           " WHERE t.tariffs_id = '" . $tariffs_id . "'" .
                                           " AND t.tariffs_start_date <= '" . $db_date . "'" .
                                           " AND t.tariffs_end_date >= '" . $db_date . "'");
        $activity_result = $database->fetch_array($activity_query);
        if (tep_not_null($activity_result)) {
          // Activity is valid for given employee and date
          return $former_activity_id;
        } else {
          // Activity is not valid for given employee and date
          return -1;
        }
      } else {
        // No former activity exists
        return 0;
      }
    }
  }
?>