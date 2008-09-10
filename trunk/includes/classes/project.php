<?php
/****************************************************************************
 * CLASS FILE  : project.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 10 september 2008
 * Description : Project class
 */

  class project {
    private $project_id, $business_unit, $customer, $name, $description, $customers_contact_name, $customers_reference, $start_date, $end_date, $calculated_hours, $roles, $role_count;

    public function __construct($project_id = '', $role_object = '') {
      $database = $_SESSION['database'];
      $this->project_id = $project_id;
      $this->roles = array();
      $this->role_count = 0;

      if (tep_not_null($project_id)) {
        $this->project_id = $database->prepare_input($this->project_id);

        $project_query = $database->query("select business_units_id, customers_id, projects_name, projects_description, projects_customers_contact_name, projects_customers_reference, projects_start_date, projects_end_date, projects_calculated_hours from " . TABLE_PROJECTS . " where projects_id = '" . (int)$project_id . "'");
        $project_result = $database->fetch_array($project_query);

        $this->business_unit = new business_unit($project_result['business_units_id']);
        $this->customer = new customer($project_result['customers_id']);
        $this->name = $project_result['projects_name'];
        $this->description = $project_result['projects_description'];
        $this->customers_contact_name = $project_result['projects_customers_contact_name'];
        $this->customers_reference = $project_result['projects_customers_reference'];
        $this->start_date = $project_result['projects_start_date'];
        $this->end_date = $project_result['projects_end_date'];
        $this->calculated_hours = $project_result['projects_calculated_hours'];

        // Retrieve specific role or all available roles for this project
        if (tep_not_null($role_object)) {
          $this->add_role($role_object);
        } else {
          $this->roles = role::get_array($this->project_id);
          // Determine size of array $this->roles (#nodes) and put this value into $child_count
        }
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'name':
          return $this->name;
      	case 'role_count':
          return $this->role_count;
      }
      return null;
    }

    private function add_role($role_object = '') {
      if (tep_not_null($role_object)) {
        $this->roles[$this->role_count] = $role_object;
        $this->role_count++;
      }
    }

    public static function get_selected_tree($tariff_id = '') {
      $role = role::get_selected_tree($tariff_id);
      $project = new project($role->get_parent_id(), $role);
      return $project;
    }

    public static function get_selectable_projects($employee_id = 0, $date = '0000-00-00') {
      $project_array = array();
      $role_array = role::get_selectable_roles($employee_id, $date);
      // Receive array of available roles
      if (tep_not_null($role_array)) {
        $database = $_SESSION['database'];
        $project_query = 'select projects_id, projects_name, projects_description, projects_customers_contact_name, projects_customers_reference, projects_start_date, projects_end_date, projects_calculated_hours, customers_id from ' . TABLE_PROJECTS . ' where projects_id in (';
        // Walk through the array
        foreach ($role_array as $role) {
          if (substr($project_query, -1) != '(')
            $project_query .= ',';
          $project_query .= $role['projects_id'];
        }
        $project_query .= ') order by projects_id';
        $project_query = $database->query($project_query);
        while ($project_result = $database->fetch_array($project_query)) {
          array_push($project_array, $project_result);
        }
      }
      return $project_array;
    }

    public static function get_project_name($project_id = '') {
      $database = $_SESSION['database'];
      $project_query = $database->query("select projects_name from " . TABLE_PROJECTS . " where projects_id = '" . $project_id . "'");
      $project_result = $database->fetch_array($project_query);
      return $project_result['projects_name'];
    }
  }
?>
