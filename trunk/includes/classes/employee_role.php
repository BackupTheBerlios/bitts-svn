<?php
/****************************************************************************
 * CLASS FILE  : employee_role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class employee_role {
    var $employee_role_id, $role_id, $employee, $start_date, $end_date, $tariffs;

    function employee_role($employee_role_id = '', $child_object = '') {
      $this->employee_role_id = $employee_role_id;
      $this->tariffs = array();

      if (tep_not_null($employee_role_id)) {
        $employee_role_id = tep_db_prepare_input($employee_role_id);

        $employee_role_query = tep_db_query("select role_id, employee_id, start_date, end_date from " . TABLE_EMPLOYEES_ROLES . " where employee_role_id = '" . (int)$employee_role_id . "'");
        $employee_role_result = tep_db_fetch_array($employee_role_query);

        $this->$role_id = $employee_role_result['role_id'];
        $this->$employee = new employee($employee_role_result['employee_id']);
        $this->$start_date = $employee_role_result['start_date'];
        $this->$end_date = $employee_role_result['end_date'];

        // Retrieve specific tariff or all tariffs for this employee_role
        if (tep_not_null($child_object)) {
          $this->tariffs[0] = $child_object;
        } else {
          $this->tariffs = tariff::get_array($this->employee_role_id);
        }
      }
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

    public function get_parent_id() {
      return $this->role_id;
    }
  }
?>
