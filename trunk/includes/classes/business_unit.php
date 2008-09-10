<?php
/****************************************************************************
 * CLASS FILE  : business_unit.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 10 september 2008
 * Description : Business unit class file
 */

  class business_unit {
    private $business_unit_id, $name;

    public function __construct($business_unit_id=0) {
      $database = $_SESSION['database'];
      $this->business_unit_id = $business_unit_id;

      if ($this->business_unit_id!=0) {
        $this->business_unit_id = $database->prepare_input($this->business_unit_id);

        $business_unit_query = $database->query("select business_units_name from " . TABLE_BUSINESS_UNITS . " where business_units_id = '" . (int)$business_unit_id . "'");
        $business_unit_result = $database->fetch_array($business_unit_query);

        $this->name = $business_unit_result['business_units_name'];
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->business_unit_id;
      	case 'name':
          return $this->name;
      }
      return null;
    }
  }
?>