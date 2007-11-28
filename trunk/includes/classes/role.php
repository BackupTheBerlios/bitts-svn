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
    var $role_id, $project_id, $name, $description, $mandatory_ticket_entry, $employees_roles;

    function role($role_id = '') {
        $this->role_id = $role_id;
        $this->employees_roles = array();

      if (tep_not_null($role_id)) {
        $role_id = tep_db_prepare_input($role_id);

        $role_query = tep_db_query("select project_id, name, description, mandatory_ticket_entry from " . TABLE_ROLES . " where role_id = '" . (int)$role_id . "'");
        $role_result = tep_db_fetch_array($role_query);

        $this->$project_id = $role_result['project_id'];
        $this->$name = $role_result['name'];
        $this->$description = $role_result['description'];
        $this->$mandatory_ticket_entry = $role_result['mandatory_ticket_entry'];

        $index = 0;
        $employees_roles_query = tep_db_query("select employee_role_id from " . TABLE_EMPLOYEES_ROLES . " where role_id = '" . (int)$role_id . "'");
        while ($employees_roles_result = tep_db_fetch_array($employees_roles_query)) {
          $this->employees_roles[$index] = new employee_role($employees_roles_result['employee_role_id']);
          $index++;
        }
      }
    }
  }
?>
