<?php
/****************************************************************************
 * CLASS FILE  : role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class role {
    private $role_id, $project_id, $name, $description, $mandatory_ticket_entry, $employees_roles;

    function role($role_id = '') {
        $this->role_id = $role_id;
        $this->employees_roles = array();

      if (tep_not_null($this->role_id)) {
        $this->role_id = tep_db_prepare_input($this->role_id);

        $role_query = tep_db_query("select project_id, name, description, mandatory_ticket_entry from " . TABLE_ROLES . " where role_id = '" . (int)$this->role_id . "'");
        $role_result = tep_db_fetch_array($role_query);

        $this->$project_id = $role_result['project_id'];
        $this->$name = $role_result['name'];
        $this->$description = $role_result['description'];
        $this->$mandatory_ticket_entry = $role_result['mandatory_ticket_entry'];

        // Retrieve all employees_roles for this role
        $this->employees_roles = employee_role::get_array($this->role_id);
      }
    }

    public static function get_array($project_id = '') {
      $role_array = array();

      if (tep_not_null($project_id)) {
        $index = 0;
        $project_id = tep_db_prepare_input($project_id);
        $roles_query = tep_db_query("select role_id from " . TABLE_ROLES . " where project_id = '" . (int)$project_id . "'");
        while ($roles_result = tep_db_fetch_array($roles_query)) {
          $role_array[$index] = new role($roles_result['role_id']);
          $index++;
        }
      }

      return $role_array;
    }
  }
?>
