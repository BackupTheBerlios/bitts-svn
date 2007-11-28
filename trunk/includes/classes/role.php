<?php
/****************************************************************************
 * CLASS FILE  : role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
 * Description : .....
 *               .....
 */

  class role {
    var $role_id, $name, $employees_roles;

    function role($role_id = '') {
        $this->role_id = $role_id;
    	$this->employee_role = array();

      if (tep_not_null($role_id)) {
        $this->query($role_id);
      }
    }

    function query($role_id) {
      $role_id = tep_db_prepare_input($role_id);

      $role_query = tep_db_query("select name from " . TABLE_ROLES . " where role_id = '" . (int)$role_id . "'");
      $role = tep_db_fetch_array($role_query);

      $this->$name = $role['name'];

      $index = 0;
      $employees_roles_query = tep_db_query("select employee_role_id from " . TABLE_EMPLOYEES_ROLES . " where role_id = '" . (int)$role_id . "'");
      while ($employees_roles = tep_db_fetch_array($employees_roles_query)) {
        $this->employees_roles[$index] = new employee_role($employees_roles['employee_role_id']);
        $index++;
      }
    }
  }
?>
