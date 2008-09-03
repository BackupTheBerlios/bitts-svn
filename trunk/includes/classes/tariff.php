<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 september 2008
 * Description : Tariff class
 *               .....
 */

  class tariff {
    var $tariff_id, $employee_role_id, $unit, $tariff_amount;

    public function __construct($tariff_id = '') {
      $database = $_SESSION['database'];
      $this->tariff_id = $tariff_id;

      if (tep_not_null($this->tariff_id)) {
        $this->$tariff_id = $database->prepare_input($this->tariff_id);

        $tariff_query = $database->query("select employees_roles_id, units_id, tariffs_amount from " . TABLE_TARIFFS . " where tariffs_id = '" . (int)$this->tariff_id . "'");
        $tariff_result = $database->fetch_array($tariff_query);

        $this->employee_role_id = $tariff_result['employees_roles_id'];
        $this->unit = new unit($tariff_result['units_id']);
        $this->tariff_amount = $tariff_result['tariffs_amount'];
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

    public function __get($varname) {
      switch ($varname) {
        case 'tariff_id':
          return $this->tariff_id;
      	case 'employee_role_id':
          return $this->employee_role_id;
      	case 'project_name':
          return employee_role::get_project_name($this->employee_role_id);
       	case 'role_name':
          return employee_role::get_role_name($this->employee_role_id);
       	case 'unit':
          return $this->unit;
      	case 'unit_name':
      	  return $this->unit->name;
        case 'tariff_amount':
          return $this->tariff_amount;
      }
      return null;
    }

    public function get_parent_id() {
      return $this->employee_role_id;
    }

    public static function get_selectable_tariffs($employee_id = 0, $role_id = 0) {
      $tariff_array = array();
      $database = $_SESSION['database'];
      // Create query
      $tariff_query = 'select tariffs_id, tariffs_amount, units_name, units_description from ' . TABLE_TARIFFS . ', ' . TABLE_UNITS . ', ' . TABLE_EMPLOYEES_ROLES;
      $tariff_query .= ' where ' . TABLE_TARIFFS . '.units_id = ' . TABLE_UNITS . '.units_id';
      $tariff_query .= ' and ' . TABLE_TARIFFS . '.employees_roles_id = ' . TABLE_EMPLOYEES_ROLES . '.employees_roles_id';
      $tariff_query .= ' and ' . TABLE_EMPLOYEES_ROLES . '.employees_id = ' . $employee_id;
      $tariff_query .= ' and ' . TABLE_EMPLOYEES_ROLES . '.roles_id = ' . $role_id;
      // Prepare query
      $tariff_query = $database->query($tariff_query);        
      // Execute query and read the contents
      while ($tariff_result = $database->fetch_array($tariff_query)) {
        array_push($tariff_array, $tariff_result);
      }
      return $tariff_array;
    }

    public static function ticket_entry_is_required($tariff_id) {
      $database = $_SESSION['database'];
      $tariff_query = $database->query("select employees_roles_id from " . TABLE_TARIFFS . " where tariffs_id = '" . $tariff_id . "'");
      $tariff_result = $database->fetch_array($tariff_query);
      return employee_role::ticket_entry_is_required($tariff_result['employees_roles_id']);
    }
  }
?>