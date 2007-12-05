<?php
/****************************************************************************
 * CLASS FILE  : employee.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  class employee {
    var $employee_id, $name;

    function employee($employee_id = '') {
      $this->employee_id = $employee_id;

      if (tep_not_null($this->employee_id)) {
        $this->employee_id = tep_db_prepare_input($this->employee_id);

        $employee_query = tep_db_query("select name from " . TABLE_EMPLOYEES . " where employee_id = '" . (int)$this->employee_id . "'");
        $employee_result = tep_db_fetch_array($employee_query);

        $this->$name = $employee_result['name'];
      }
    }
  }
?>