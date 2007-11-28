<?php
/****************************************************************************
 * CLASS FILE  : hours_type.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
 * Description : .....
 *               .....
 */

  class hours_type {
    var $hours_type_id, $name, $description;

    function hours_type($hours_type_id = '') {

      if (tep_not_null($hours_type_id)) {
        $hours_type_id = tep_db_prepare_input($hours_type_id);

        $hours_type_query = tep_db_query("select name, description from " . TABLE_HOURS_TYPES . " where hours_type_id = '" . (int)$hours_type_id . "'");
        $hours_type_result = tep_db_fetch_array($hours_type_query);

        $this->$name = $hours_type_result['name'];
        $this->$description = $hours_type_result['description'];
      }
    }
  }
?>
