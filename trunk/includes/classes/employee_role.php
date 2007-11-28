<?php
/****************************************************************************
 * CLASS FILE  : employee_role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
 * Description : .....
 *               .....
 */

  class employee_role {
    var $employee_role_id, $role_id, $employee_id, $start_date, $end_date, $tariffs;

    function role($employee_role_id = '') {
        $this->employee_role_id = $employee_role_id;
        $this->tariffs = array();

      if (tep_not_null($employee_role_id)) {
        $employee_role_id = tep_db_prepare_input($employee_role_id);

        $employee_role_query = tep_db_query("select role_id, employee_id, start_date, end_date from " . TABLE_EMPLOYEES_ROLES . " where employee_role_id = '" . (int)$employee_role_id . "'");
        $employee_role_result = tep_db_fetch_array($employee_role_query);

        $this->$role_id = $employee_role_result['role_id'];
        $this->$employee_id = $employee_role_result['employee_id'];
        $this->$start_date = $employee_role_result['start_date'];
        $this->$end_date = $employee_role_result['end_date'];

        $index = 0;
        $tariffs_query = tep_db_query("select tariff_id from " . TABLE_TARIFFS . " where employee_role_id = '" . (int)$employee_role_id . "'");
        while ($tariffs_result = tep_db_fetch_array($tariffs_query)) {
          $this->tariffs[$index] = new tariff($tariffs_result['tariff_id']);
          $index++;
        }
      }
    }
  }
?>
