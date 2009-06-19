<?php
/****************************************************************************
 * CLASS FILE  : employee_role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 june 2009
 * Description : Employee_role class
 */

  class employee_role {
    private $id, $roles_id, $employee, $start_date, $end_date, $tariffs;

    public function __construct($id = 0, $child_object = null) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->tariffs = array();

      if ($id != 0) {
        // Get existing employee_role
        $id = $database->prepare_input($id);

        $employees_roles_query = $database->query("select employees_roles_start_date, employees_roles_end_date, roles_id, employees_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
        $employees_roles_result = $database->fetch_array($employees_roles_query);

        $this->fill(tep_datetouts(DATE_FORMAT_DATABASE, $employees_roles_result['employees_roles_start_date']),
                    ($employees_roles_result['employees_roles_end_date']!='2099-12-31'?tep_datetouts(DATE_FORMAT_DATABASE, $employees_roles_result['employees_roles_end_date']):0),
                    $employees_roles_result['roles_id'],
                    $employees_roles_result['employees_id']);

        // Retrieve specific tariff or all tariffs for this employee_role
        if (tep_not_null($child_object)) {
          if (is_object($child_object)) {
            $this->tariffs[0] = $child_object;
          }
        } else {
          $this->tariffs = tariff::get_array($this->id);
        }
      }
    }

    public function __get($varname) {
      switch ($varname) {
      	case 'id':
          return $this->id;
      	case 'roles_id':
          return $this->roles_id;
       	case 'employee':
          return $this->employee;
       	case 'start_date':
          return $this->start_date;
      	case 'end_date':
      	  return $this->end_date;
        case 'tariffs':
          return $this->tariffs;
      }
      return null;
    }

    public function __set($varname, $value) {
      switch ($varname) {
      	case 'start_date':
          $this->start_date = $value;
          break;
      	case 'end_date':
          $this->end_date = $value;
          break;
      }
    }

    public function get_employees_roles($parents_id, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2) {
      $database = $_SESSION['database'];
      $employees_roles_array = array();

      if (tep_not_null($parents_id)) {
        $index = 0;
        $employees_roles_query = $database->query("select employees_roles_id from " . TABLE_EMPLOYEES_ROLES . " where roles_id in (" . $parents_id . ") and " . $column_name1 . " " . $comparison1 . " '" . $value1 . "'" . (tep_not_null($delimiter)?' ' . $delimiter . " " . $column_name2 . " " . $comparison2 . " '" . $value2 . "'":''));
        while ($employees_roles_result = $database->fetch_array($employees_roles_query)) {
          $employees_roles_array[$index] = new employee_role($employees_roles_result['employees_roles_id']);
          $index++;
        }
      }

      return $employees_roles_array;
    }

    public function has_tariffs($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $employees_roles_listing = '';

      if (tep_not_null($parents_id)) {
        $employees_roles_query = $database->query("select employees_roles_id from " . TABLE_EMPLOYEES_ROLES . " where roles_id in (" . $parents_id . ")");
        while ($employees_roles_result = $database->fetch_array($employees_roles_query)) {
          if (tep_not_null($employees_roles_listing)) {
            $employees_roles_listing .= ',';
          }
          $employees_roles_listing .= ''.$employees_roles_result['employees_roles_id'];
        }
      }

      $tariff = new tariff();
      return $tariff->get_tariffs($employees_roles_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public function has_activities($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $employees_roles_listing = '';

      if (tep_not_null($parents_id)) {
        $employees_roles_query = $database->query("select employees_roles_id from " . TABLE_EMPLOYEES_ROLES . " where roles_id in (" . $parents_id . ")");
        while ($employees_roles_result = $database->fetch_array($employees_roles_query)) {
          if (tep_not_null($employees_roles_listing)) {
            $employees_roles_listing .= ',';
          }
          $employees_roles_listing .= ''.$employees_roles_result['employees_roles_id'];
        }
      }

      $tariff = new tariff();
      return $tariff->has_activities($employees_roles_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public function get_array($roles_id = '') {
      $database = $_SESSION['database'];
      $employees_roles_array = array();

      $selection_criterium = '';
      if (tep_not_null($roles_id) && $roles_id != 0) {
        $roles_id = $database->prepare_input($roles_id);
        $selection_criterium = " where roles_id in (" . $roles_id . ")";
      }
      $index = 0;
      $employees_roles_query = $database->query("select employees_roles_id from " . TABLE_EMPLOYEES_ROLES . $selection_criterium);
      while ($employees_roles_result = $database->fetch_array($employees_roles_query)) {
        $employees_roles_array[$index] = new employee_role($employees_roles_result['employees_roles_id'], 'dummy');
        $index++;
      }

      return $employees_roles_array;
    }

    public function fill($start_date = 0, $end_date = 0, $roles_id = 0, $employees_id = 0) {
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->roles_id = $roles_id;
      $this->employee = new employee($employees_id);
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new employee_role if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The project does not exist
        $database->query("insert into " . TABLE_EMPLOYEES_ROLES . " (employees_roles_start_date, employees_roles_end_date, roles_id, employees_id) values ('" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', '" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', '" . $this->roles_id . "', '" . $this->employee->id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The employee_role exists, update the contents
        $units_query = $database->query("update " . TABLE_EMPLOYEES_ROLES . " set employees_roles_start_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', employees_roles_end_date='" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', roles_id='" . $this->roles_id . "', employees_id='" . $this->employee->id . "' where employees_roles_id = '" . (int)$this->id . "'");
      }
    }

    public static function get_selectable_tree($employee_id = '') {
      $employees_roles_array = array();

      if (tep_not_null($employee_id)) {
        $index = 0;
        $employee_id = tep_db_prepare_input($employee_id);
        $employees_roles_query = tep_db_query("select employees_roles_id from " . TABLE_EMPLOYEES_ROLES . " where employee_id = '" . (int)$employee_id . "'");
        while ($employees_roles_result = tep_db_fetch_array($employees_roles_query)) {
          $this->employees_roles_array[$index] = new employee_role($employees_roles_result['employees_roles_id']);
          $index++;
        }
      }
      return $employees_roles_array;
    }

    public static function get_selected_tree($tariffs_id = '') {
      $tariff = new tariff($tariffs_id);
      $employee_role = new employee_role($tariff->get_parent_id(), $tariff);
      return $employee_role;
    }

    public static function get_selectable_employees_roles($employee_id = 0, $date = '0000-00-00', $roles_id = 0) {
      $employees_roles_array = array();
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select employees_roles_id, employees_roles_start_date, employees_roles_end_date, roles_id, employees_id from " . TABLE_EMPLOYEES_ROLES . " where employees_id = '" . $employee_id . "' and employees_roles_start_date <= '" . $date . "' and employees_roles_end_date >= '" . $date . "' order by employees_roles_id");        
      while ($employees_roles_result = $database->fetch_array($employees_roles_query)) {
        array_push($employees_roles_array, $employees_roles_result);
      }
      return $employees_roles_array;
    }

    public function get_parent_id() {
      return $this->roles_id;
    }

    public static function get_role_id($id = 0) {
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return $employees_roles_result['roles_id'];
    }

    public static function get_role_name($id = 0) {
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return role::get_role_name($employees_roles_result['roles_id']);
    }

    public static function get_project_id($id = 0) {
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return role::get_project_id($employees_roles_result['roles_id']);
    }

    public static function get_project_name($id = 0) {
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return role::get_project_name($employees_roles_result['roles_id']);
    }

    public static function ticket_entry_is_required($id = 0) {
      $database = $_SESSION['database'];
      $employees_roles_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return role::ticket_entry_is_required($employees_roles_result['roles_id']);
    }
  }
?>
