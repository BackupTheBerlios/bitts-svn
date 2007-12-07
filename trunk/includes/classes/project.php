<?php
/****************************************************************************
 * CLASS FILE  : project.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class project {
    private $project_id, $customer, $name, $description, $start_date, $end_date, $calculated_hours, $roles, $role_count;

    function project($project_id = '', $role_object = '') {
      $this->project_id = $project_id;
      $this->roles = array();
      $this->role_count = 0;

      if (tep_not_null($project_id)) {
        $project_id = tep_db_prepare_input($project_id);

        $project_query = tep_db_query("select customer_id, name, description, start_date, end_date, calculated_hours from " . TABLE_PROJECTS . " where project_id = '" . (int)$project_id . "'");
        $project_result = tep_db_fetch_array($project_query);

        $this->$customer = new customer($project_result['customer_id']);
        $this->$name = $project_result['name'];
        $this->$description = $project_result['description'];
        $this->$start_date = $project_result['start_date'];
        $this->$end_date = $project_result['end_date'];
        $this->$calculated_hours = $project_result['calculated_hours'];

        // Retrieve specific role or all available roles for this project
        if (tep_not_null($role_object)) {
          add_role($role_object);
        } else {
          $this->roles = role::get_array($this->project_id);
          // Determine size of array $this->roles (#nodes) and put this value into $child_count
        }
      }
    }

    public function get_role_count() {
      return $role_count;
    }

    public function add_role($role_object = '') {
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

    // !!!!!!!!! UNDER CONSTRUCTION !!!!!!!!!!
    public static function get_selectable_projects($employee_id = '', $min_date = '', $max_date = '') {
      $project_array = array();
      $role_array = role::get_selectable_roles($employee_id, $min_date, $max_date);
      $index = 0;
      while (true) {
        $project_array[$index] = '';
        $index++;
      }
      return $project_array;
    }
  }
?>
