<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
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
  }
?>
