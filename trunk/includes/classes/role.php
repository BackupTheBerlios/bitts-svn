<?php
/****************************************************************************
 * CLASS FILE  : role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 mei 2008
 * Description : .....
 *               .....
 */

  class role {
    private $role_id, $project_id, $name, $description, $mandatory_ticket_entry, $employees_roles;

    function role($role_id = '', $child_object = '') {
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

        // Retrieve specific employee_role or all employees_roles for this role
        if (tep_not_null($child_object)) {
          $this->employees_roles[0] = $child_object;
        } else {
          $this->employees_roles = employee_role::get_array($this->role_id);
        }
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

    public static function get_selected_tree($tariff_id = '') {
      $employee_role = employee_role::get_select_tree($tariff_id);
      $role = new role($employee_role->get_parent_id(), $employee_role);
      return $role;
    }

    public static function get_selectable_roles($employee_id = 0, $date = '0000-00-00', $project_id = 0) {
      $role_array = array();
      $employees_roles_array = employee_role::get_selectable_employees_roles($employee_id, $date);
      // Receive array of available employees_roles
      if (tep_not_null($employees_roles_array)) {
        $database = $_SESSION['database'];
        $role_query = 'select roles_id, roles_name, roles_description, roles_mandatory_ticket_entry, projects_id from ' . TABLE_ROLES . ' where roles_id in (';
        // Walk through the array
        foreach ($employees_roles_array as $employee_role) {
          if (substr($role_query, -1) != '(')
            $role_query .= ',';
          $role_query .= $employee_role['roles_id'];
        }
        $role_query .= ')';
        if ($project_id != 0)
          $role_query .= ' and projects_id = ' . $project_id;
        $role_query .= ' order by roles_id';
        $role_query = $database->query($role_query);        
        while ($role_result = $database->fetch_array($role_query)) {
          array_push($role_array, $role_result);
        }
      }
      return $role_array;
    }

    public function get_parent_id() {
      return $this->project_id;
    }
  }
?>
