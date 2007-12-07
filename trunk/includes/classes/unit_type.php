<?php
/****************************************************************************
 * CLASS FILE  : unit_type.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class unit_type {
    private $unit_type_id, $name, $description;

    function unit_type($unit_type_id = '') {
      $this->unit_type_id = $unit_type_id;

      if (tep_not_null($this->unit_type_id)) {
        $this->unit_type_id = tep_db_prepare_input($this->unit_type_id);

        $unit_type_query = tep_db_query("select name, description from " . TABLE_HOURS_TYPES . " where hours_type_id = '" . (int)$hours_type_id . "'");
        $unit_type_result = tep_db_fetch_array($unit_type_query);

        $this->$name = $unit_type_result['name'];
        $this->$description = $unit_type_result['description'];
      }
    }
  }
?>