<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class tariff {
    var $tariff_id, $employee_role_id, $hours_type, $amount;

    function tariff($tariff_id = '') {
      $this->tariff_id = $tariff_id;

      if (tep_not_null($tariff_id)) {
        $tariff_id = tep_db_prepare_input($tariff_id);

        $tariff_query = tep_db_query("select employee_role_id, hours_type_id, amount from " . TABLE_TARIFFS . " where tariff_id = '" . (int)$tariff_id . "'");
        $tariff_result = tep_db_fetch_array($tariff_query);

        $this->$employee_role_id = $tariff_result['employee_role_id'];
        $this->$hours_type = new hours_type($tariff_result['hours_type_id']);
        $this->$amount = $tariff_result['amount'];
      }
    }

    public static function get_array($employee_role_id = '') {
      $tariff_array = array();

      if (tep_not_null($employee_role_id)) {
        $index = 0;
        $employee_role_id = tep_db_prepare_input($employee_role_id);
        $tariffs_query = tep_db_query("select tariff_id from " . TABLE_TARIFFS . " where employee_role_id = '" . (int)$employee_role_id . "'");
        while ($tariffs_result = tep_db_fetch_array($tariffs_query)) {
          $this->tariff_array[$index] = new tariff($tariffs_result['tariff_id']);
          $index++;
        }
      }
      return $tariff_array;
    }

    public function get_parent_id() {
      return $this->employee_role_id;
    }
  }
?>
