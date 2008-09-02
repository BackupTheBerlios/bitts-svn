<?php
/****************************************************************************
 * CLASS FILE  : unit.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 01 september 2008
 * Description : .....
 *               .....
 */

  class unit {
    private $unit_id, $name, $description;

    public function __construct($unit_id = '') {
      $database = $_SESSION['database'];
      $this->unit_id = $unit_id;

      if (tep_not_null($this->unit_id)) {
        $this->unit_id = $database->prepare_input($this->unit_id);

        $unit_query = $database->query("select units_name, units_description from " . TABLE_UNITS . " where units_id = '" . (int)$unit_id . "'");
        $unit_result = $database->fetch_array($unit_query);

        $this->name = $unit_result['units_name'];
        $this->description = $unit_result['units_description'];
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'unit_id':
          return $this->unit_id;
      	case 'name':
          return $this->name;
      	case 'description':
          return $this->description;
      }
      return null;
    }
  }
?>