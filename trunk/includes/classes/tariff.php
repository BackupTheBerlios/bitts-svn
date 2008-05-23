<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 may 2008
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
  }
?>