<?php
/****************************************************************************
 * CLASS FILE  : role.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 20 june 2009
 * Description : Role class
 */

  class role {
    private $id, $name, $description, $mandatory_ticket_entry, $projects_id, $category, $employees_roles, $listing;

    public function __construct($id = 0, $projects_id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->employees_roles = array();
      $this->listing = array();

      if ($id != 0) {
        $id = $database->prepare_input($id);

        $roles_query = $database->query("select roles_name, roles_description, roles_mandatory_ticket_entry, projects_id, categories_id from " . TABLE_ROLES . " where roles_id = '" . (int)$id . "'");
        $roles_result = $database->fetch_array($roles_query);

        $this->fill($roles_result['roles_name'],
                    $roles_result['roles_description'],
                    ($roles_result['roles_mandatory_ticket_entry'] == 1),
                    $roles_result['projects_id'],
                    $roles_result['categories_id']);
      } else {
        // We probably created an empty role object to retrieve the entire role listing
        $this->listing = $this->get_array($projects_id);
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
        case 'name':
          return $this->name;
        case 'description':
          return $this->description;
        case 'mandatory_ticket_entry':
          return $this->mandatory_ticket_entry;
        case 'projects_id':
          return $this->projects_id;
        case 'category':
          return $this->category;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $description = '', $mandatory_ticket_entry = false, $projects_id = 0, $categories_id = 0) {
      $this->name = $name;
      $this->description = $description;
      $this->mandatory_ticket_entry = $mandatory_ticket_entry;
      $this->projects_id = $projects_id;
      $this->category = new category($categories_id);
    }

    public function get_array($projects_id = 0) {
      $database = $_SESSION['database'];
      $roles_array = array();

      $selection_criterium = '';
      if ($projects_id != 0) {
        $projects_id = $database->prepare_input($projects_id);
        $selection_criterium = " where projects_id = '" . (int)$projects_id . "'";
      }
      $index = 0;
      $roles_query = $database->query("select roles_id from " . TABLE_ROLES . $selection_criterium . " order by roles_name");
      while ($roles_result = $database->fetch_array($roles_query)) {
        $roles_array[$index] = new role($roles_result['roles_id']);
        $index++;
      }

      return $roles_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new role if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The role does not exist
        $database->query("insert into " . TABLE_ROLES . " (roles_name, roles_description, roles_mandatory_ticket_entry, projects_id, categories_id) values ('" . $this->name . "', '" . $this->description . "', '" . ($this->mandatory_ticket_entry?1:0) . "', '" . $this->projects_id . "', '" . $this->category->id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The role exists, update the contents
        $roles_query = $database->query("update " . TABLE_ROLES . " set roles_name='" . $this->name . "', roles_description='" . $this->description . "', roles_mandatory_ticket_entry='" . ($this->mandatory_ticket_entry?1:0) . "', projects_id='" . $this->projects_id . "', categories_id='" . $this->category->id . "' where roles_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $roles_query = $database->query("delete from " . TABLE_ROLES . " where roles_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this role (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $this->id = $database->prepare_input($this->id);
      $employees_roles_query = $database->query("select 1 from " . TABLE_EMPLOYEES_ROLES . " where roles_id = '" . (int)$this->id . "'");
      $employees_roles_result = $database->fetch_array($employees_roles_query);
      return tep_not_null($employees_roles_result);
    }

    public static function get_selected_tree($tariffs_id = '') {
      $employee_role = employee_role::get_selected_tree($tariffs_id);
      $role = new role($employee_role->get_parent_id(), $employee_role);
      return $role;
    }

    public static function get_selectable_roles($employees_id = 0, $date = '0000-00-00', $projects_id = 0) {
      $roles_array = array();
      $employees_roles_array = employee_role::get_selectable_employees_roles($employees_id, $date);
      // Receive array of available employees_roles
      if (tep_not_null($employees_roles_array)) {
        $database = $_SESSION['database'];
        $roles_query = 'select roles_id, roles_name, roles_description, roles_mandatory_ticket_entry, projects_id from ' . TABLE_ROLES . ' where roles_id in (';
        // Walk through the array
        foreach ($employees_roles_array as $employees_roles) {
          if (substr($roles_query, -1) != '(')
            $roles_query .= ',';
          $roles_query .= $employees_roles['roles_id'];
        }
        $roles_query .= ')';
        if ($projects_id != 0)
          $roles_query .= ' and projects_id = ' . $projects_id;
        $roles_query .= ' order by roles_name';
        $roles_query = $database->query($roles_query);        
        while ($roles_result = $database->fetch_array($roles_query)) {
          array_push($roles_array, $roles_result);
        }
      }
      return $roles_array;
    }

    public function get_parent_id() {
      return $this->projects_id;
    }

    public function has_employees_roles($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $roles_listing = '';

      if (tep_not_null($parents_id)) {
        $roles_query = $database->query("select roles_id from " . TABLE_ROLES . " where projects_id in (" . $parents_id . ")");
        while ($roles_result = $database->fetch_array($roles_query)) {
          if (tep_not_null($roles_listing)) {
            $roles_listing .= ',';
          }
          $roles_listing .= ''.$roles_result['roles_id'];
        }
      }

      $employee_role = new employee_role();
      return $employee_role->get_employees_roles($roles_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public function has_tariffs($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $roles_listing = '';

      if (tep_not_null($parents_id)) {
        $roles_query = $database->query("select roles_id from " . TABLE_ROLES . " where projects_id in (" . $parents_id . ")");
        while ($roles_result = $database->fetch_array($roles_query)) {
          if (tep_not_null($roles_listing)) {
            $roles_listing .= ',';
          }
          $roles_listing .= ''.$roles_result['roles_id'];
        }
      }

      $employee_role = new employee_role();
      return $employee_role->has_tariffs($roles_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public function has_activities($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $roles_listing = '';

      if (tep_not_null($parents_id)) {
        $roles_query = $database->query("select roles_id from " . TABLE_ROLES . " where projects_id in (" . $parents_id . ")");
        while ($roles_result = $database->fetch_array($roles_query)) {
          if (tep_not_null($roles_listing)) {
            $roles_listing .= ',';
          }
          $roles_listing .= ''.$roles_result['roles_id'];
        }
      }

      $employee_role = new employee_role();
      return $employee_role->has_activities($roles_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public static function get_role_name($role_id = '') {
      $database = $_SESSION['database'];
      $role_query = $database->query("select roles_name from " . TABLE_ROLES . " where roles_id = '" . $role_id . "'");
      $role_result = $database->fetch_array($role_query);
      return $role_result['roles_name'];
    }

    public static function get_project_id($roles_id = '') {
      $database = $_SESSION['database'];
      $roles_query = $database->query("select projects_id from " . TABLE_ROLES . " where roles_id = '" . $roles_id . "'");
      $roles_result = $database->fetch_array($roles_query);
      return $roles_result['projects_id'];
    }

    public static function get_project_name($role_id = '') {
      $database = $_SESSION['database'];
      $role_query = $database->query("select projects_id from " . TABLE_ROLES . " where roles_id = '" . $role_id . "'");
      $role_result = $database->fetch_array($role_query);
      return project::get_project_name($role_result['projects_id']);
    }

    public static function ticket_entry_is_required($role_id = '') {
      $database = $_SESSION['database'];
      $role_query = $database->query("select roles_mandatory_ticket_entry from " . TABLE_ROLES . " where roles_id = '" . $role_id . "'");
      $role_result = $database->fetch_array($role_query);
      return ($role_result['roles_mandatory_ticket_entry'] == 1);
    }
  }
?>
