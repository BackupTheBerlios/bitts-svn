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

    function employee_role($employee_role_id = '') {
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

        // Retrieve all tariffs for this employee_role
        $this->tariffs = tariff::get_array($this->employee_role_id);
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
  }
?>
