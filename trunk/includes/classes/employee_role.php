<?php
/****************************************************************************
 * CLASS FILE  : employee_role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 04 september 2008
 * Description : Employee_role class
 *               .....
 */

  class employee_role {
    var $employee_role_id, $role_id, $employee, $start_date, $end_date, $tariffs;

    public function __construct($employee_role_id = '', $child_object = '') {
      $database = $_SESSION['database'];
      $this->employee_role_id = $employee_role_id;
      $this->tariffs = array();

      if (tep_not_null($this->employee_role_id)) {
        $this->employee_role_id = $database->prepare_input($this->employee_role_id);

        $employee_role_query = $database->query("select roles_id, employees_id, employees_roles_start_date, employees_roles_end_date from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . (int)$employee_role_id . "'");
        $employee_role_result = $database->fetch_array($employee_role_query);

        $this->role_id = $employee_role_result['roles_id'];
        $this->employee = new employee($employee_role_result['employees_id']);
        $this->start_date = $employee_role_result['employees_roles_start_date'];
        $this->end_date = $employee_role_result['employees_roles_end_date'];

        // Retrieve specific tariff or all tariffs for this employee_role
        if (tep_not_null($child_object)) {
          $this->tariffs[0] = $child_object;
        } else {
          $this->tariffs = tariff::get_array($this->employee_role_id);
        }
      }
    }

    public function __get($varname) {
      switch ($varname) {
      	case 'employee_role_id':
          return $this->employee_role_id;
      	case 'role_id':
          return $this->role_id;
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

    public static function get_array($role_id = '') {
      $employee_role_array = array();

      if (tep_not_null($role_id)) {
        $index = 0;
        $role_id = tep_db_prepare_input($role_id);
        $employees_roles_query = tep_db_query("select employee_role_id from " . TABLE_EMPLOYEES_ROLES . " where role_id = '" . (int)$role_id . "'");
        while ($employees_roles_result = tep_db_fetch_array($employees_roles_query)) {
          $this->employee_role_array[$index] = new employee_role($employees_roles_result['employee_role_id']);
          $index++;
        }
      }
      return $employee_role_array;
    }

    public static function get_selectable_tree($employee_id = '') {
      $employee_role_array = array();

      if (tep_not_null($employee_id)) {
        $index = 0;
        $employee_id = tep_db_prepare_input($employee_id);
        $employees_roles_query = tep_db_query("select employee_role_id from " . TABLE_EMPLOYEES_ROLES . " where employee_id = '" . (int)$employee_id . "'");
        while ($employees_roles_result = tep_db_fetch_array($employees_roles_query)) {
          $this->employee_role_array[$index] = new employee_role($employees_roles_result['employee_role_id']);
          $index++;
        }
      }
      return $employee_role_array;
    }

    public static function get_selected_tree($tariff_id = '') {
      $tariff = new tariff($tariff_id);
      $employee_role = new employee_role($tariff->get_parent_id(), $tariff);
      return $employee_role;
    }

    public static function get_selectable_employees_roles($employee_id = 0, $date = '0000-00-00', $roles_id = 0) {
      $employee_role_array = array();
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select employees_roles_id, employees_roles_start_date, employees_roles_end_date, roles_id, employees_id from " . TABLE_EMPLOYEES_ROLES . " where employees_id = '" . $employee_id . "' and employees_roles_start_date <= '" . $date . "' and employees_roles_end_date >= '" . $date . "' order by employees_roles_id");        
      while ($employee_role_result = $database->fetch_array($employee_role_query)) {
        array_push($employee_role_array, $employee_role_result);
      }
      return $employee_role_array;
    }

    public function get_parent_id() {
      return $this->role_id;
    }

    public static function get_role_id($employee_role_id = '') {
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . $employee_role_id . "'");
      $employee_role_result = $database->fetch_array($employee_role_query);
      return $employee_role_result['roles_id'];
    }

    public static function get_role_name($employee_role_id = '') {
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . $employee_role_id . "'");
      $employee_role_result = $database->fetch_array($employee_role_query);
      return role::get_role_name($employee_role_result['roles_id']);
    }

    public static function get_project_id($employee_role_id = '') {
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . $employee_role_id . "'");
      $employee_role_result = $database->fetch_array($employee_role_query);
      return role::get_project_id($employee_role_result['roles_id']);
    }

    public static function get_project_name($employee_role_id = '') {
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . $employee_role_id . "'");
      $employee_role_result = $database->fetch_array($employee_role_query);
      return role::get_project_name($employee_role_result['roles_id']);
    }

    public static function ticket_entry_is_required($employee_role_id = '') {
      $database = $_SESSION['database'];
      $employee_role_query = $database->query("select roles_id from " . TABLE_EMPLOYEES_ROLES . " where employees_roles_id = '" . $employee_role_id . "'");
      $employee_role_result = $database->fetch_array($employee_role_query);
      return role::ticket_entry_is_required($employee_role_result['roles_id']);
    }
  }
?>
