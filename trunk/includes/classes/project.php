<?php
/****************************************************************************
 * CLASS FILE  : project.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 june 2009
 * Description : Project class
 */

  class project {
    private $id, $business_unit, $customer, $name, $description, $customers_contact_name, $customers_reference, $start_date, $end_date, $calculated_hours, $calculated_hours_period, $roles, $listing;

    public function __construct($id = 0, $role_object = null) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->roles = array();
      $this->listing = array();

      if ($id != 0) {
        $id = $database->prepare_input($id);

        $projects_query = $database->query("select projects_name, projects_description, projects_customers_contact_name, projects_customers_reference, projects_start_date, projects_end_date, projects_calculated_hours, projects_calculated_hours_period, business_units_id, customers_id from " . TABLE_PROJECTS . " where projects_id = '" . (int)$id . "'");
        $projects_result = $database->fetch_array($projects_query);

        if (tep_not_null($projects_result)) {
          // Project exists
          $this->fill($projects_result['projects_name'],
                      $projects_result['projects_description'],
                      $projects_result['projects_customers_contact_name'],
                      $projects_result['projects_customers_reference'],
                      tep_datetouts(DATE_FORMAT_DATABASE, $projects_result['projects_start_date']),
                      ($projects_result['projects_end_date']!='2099-12-31'?tep_datetouts(DATE_FORMAT_DATABASE, $projects_result['projects_end_date']):0),
                      $projects_result['projects_calculated_hours'],
                      $projects_result['projects_calculated_hours_period'],
                      $projects_result['business_units_id'],
                      $projects_result['customers_id']);
          // Retrieve specific role or all available roles for this project
          if (tep_not_null($role_object)) {
            $this->roles[sizeof($this->roles)] = $role_object;
          } else {
            $temp_role = new role(); // Create a default role (id==0)
            $this->roles = $temp_role->get_array($this->id);
          }
        }
      } else {
        // We probably created an empty project object to retrieve the entire project listing
        $this->listing = $this->get_array();
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
        case 'customers_contact_name':
          return $this->customers_contact_name;
        case 'customers_reference':
          return $this->customers_reference;
        case 'start_date': // In uts
          return $this->start_date;
        case 'end_date': // In uts
          return $this->end_date;
        case 'calculated_hours':
          return $this->calculated_hours;
        case 'calculated_hours_period':
          return $this->calculated_hours_period;
        case 'business_unit':
          return $this->business_unit;
        case 'customer':
          return $this->customer;
        case 'roles':
          return $this->roles;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $description = '', $customers_contact_name = '', $customers_reference = '', $start_date = 0, $end_date = 0, $calculated_hours = 0, $calculated_hours_period = 'E', $business_units_id = 0, $customers_id = 0) {
        $this->name = $name;
        $this->description = $description;
        $this->customers_contact_name = $customers_contact_name;
        $this->customers_reference = $customers_reference;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->calculated_hours = $calculated_hours;
        $this->calculated_hours_period = $calculated_hours_period;
        $this->business_unit = new business_unit($business_units_id);
        $this->customer = new customer($customers_id);
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $projects_array = array();

      $index = 0;
      $projects_query = $database->query("select projects_id from " . TABLE_PROJECTS . " order by projects_name");
      while ($projects_result = $database->fetch_array($projects_query)) {
        $projects_array[$index] = new project($projects_result['projects_id']);
        $index++;
      }

      return $projects_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new project if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The project does not exist
        $database->query("insert into " . TABLE_PROJECTS . " (projects_name, projects_description, projects_customers_contact_name, projects_customers_reference, projects_start_date, projects_end_date, projects_calculated_hours, projects_calculated_hours_period, business_units_id, customers_id) values ('" . $this->name . "', '" . $this->description . "', '" . $this->customers_contact_name . "', '" . $this->customers_reference . "', '" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', '" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', '" . $this->calculated_hours . "', '" . $this->calculated_hours_period . "', '" . $this->business_unit->id . "', '" . $this->customer->id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The project exists, update the contents
        $units_query = $database->query("update " . TABLE_PROJECTS . " set projects_name='" . $this->name . "', projects_description='" . $this->description . "', projects_customers_contact_name='" . $this->customers_contact_name . "', projects_customers_reference='" . $this->customers_reference . "', projects_start_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', projects_end_date='" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', projects_calculated_hours='" . $this->calculated_hours . "', projects_calculated_hours_period='" . $this->calculated_hours_period . "', business_units_id='" . $this->business_unit->id . "', customers_id='" . $this->customer->id . "' where projects_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $projects_query = $database->query("delete from " . TABLE_PROJECTS . " where projects_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this project (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $this->id = $database->prepare_input($this->id);
      $roles_query = $database->query("select 1 from " . TABLE_ROLES . " where projects_id = '" . (int)$this->id . "'");
      $roles_result = $database->fetch_array($roles_query);
      return tep_not_null($roles_result);
    }

    public function validate_hours($value) {
      $value = str_replace(",", ".", $value);
      $value = '0' . $value;
      return (substr_count($value, ".") == 0 &&
              is_numeric($value) &&
              (int)$value >= 0);
    }








    /******************************************************************************************/
    /***** The following static functions have to be reviewed to minimize the use of them *****/
    /***** TODO: Check if they can be replaced by non-static version                      *****/
    /******************************************************************************************/

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
        $project_query .= ') order by projects_name';
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

    public static function get_project_listing($date = 0) {
      $project_array = array();
      if ($date == 0) {
        $date = mktime();
      }
      $database = $_SESSION['database'];
      $project_query = $database->query("SELECT projects_id,projects_name,projects_start_date,projects_end_date,projects_calculated_hours,projects_calculated_hours_period," .
          "(SELECT IFNULL(SUM(activities_amount),0) " .
          "FROM " . TABLE_ACTIVITIES . " " .
          "LEFT JOIN (" . TABLE_TIMESHEETS . "," . TABLE_TARIFFS . "," . TABLE_EMPLOYEES_ROLES . "," . TABLE_ROLES . ") " .
          "ON (" . TABLE_ACTIVITIES . ".timesheets_id=" . TABLE_TIMESHEETS . ".timesheets_id " .
            "AND " . TABLE_ACTIVITIES . ".tariffs_id=" . TABLE_TARIFFS . ".tariffs_id " .
            "AND " . TABLE_TARIFFS . ".employees_roles_id=" . TABLE_EMPLOYEES_ROLES . ".employees_roles_id " .
            "AND " . TABLE_EMPLOYEES_ROLES . ".roles_id=" . TABLE_ROLES . ".roles_id) " .
          "WHERE " . TABLE_TIMESHEETS . ".timesheets_start_date<='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
            "AND " . TABLE_TIMESHEETS . ".timesheets_end_date>='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
            "AND " . TABLE_ROLES . ".projects_id=" . TABLE_PROJECTS . ".projects_id) AS projects_calculated_hours_used " .
        "FROM " . TABLE_PROJECTS . " " .
        "WHERE projects_start_date<='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
          "AND projects_end_date>='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
          "AND (projects_calculated_hours=0 " .
          "OR (projects_calculated_hours>0 " .
          "AND projects_calculated_hours_period='B')) " .
        "UNION " .
        "SELECT projects_id,projects_name,projects_start_date,projects_end_date,projects_calculated_hours,projects_calculated_hours_period," .
          "(SELECT IFNULL(SUM(activities_amount),0) " .
          "FROM " . TABLE_ACTIVITIES . " " .
          "LEFT JOIN (" . TABLE_TARIFFS . "," . TABLE_EMPLOYEES_ROLES . "," . TABLE_ROLES . ") " .
          "ON (" . TABLE_ACTIVITIES . ".tariffs_id=" . TABLE_TARIFFS . ".tariffs_id " .
            "AND " . TABLE_TARIFFS . ".employees_roles_id=" . TABLE_EMPLOYEES_ROLES . ".employees_roles_id " .
            "AND " . TABLE_EMPLOYEES_ROLES . ".roles_id=" . TABLE_ROLES . ".roles_id) " .
          "WHERE " . TABLE_ROLES . ".projects_id=" . TABLE_PROJECTS . ".projects_id) " .
        "FROM " . TABLE_PROJECTS . " " .
        "WHERE projects_start_date<='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
          "AND projects_end_date>='" . tep_strftime(DATE_FORMAT_DATABASE, $date) . "' " .
          "AND projects_calculated_hours>0 " .
          "AND projects_calculated_hours_period='E' " .
        "ORDER BY ((projects_calculated_hours_used*projects_calculated_hours)/((projects_calculated_hours*projects_calculated_hours)+0.001)) DESC,projects_name ASC");
      while ($project_result = $database->fetch_array($project_query)) {
        array_push($project_array, $project_result);
      }
      return $project_array;
    }

    public static function hours_fit_in_calculated_amount($projects_id, $activity_amount, $selected_date) {
      $database = $_SESSION['database'];
      $project_query = $database->query("SELECT projects_calculated_hours," .
          "(SELECT IFNULL(SUM(activities_amount),0) " .
          "FROM " . TABLE_ACTIVITIES . " " .
          "LEFT JOIN (" . TABLE_TIMESHEETS . "," . TABLE_TARIFFS . "," . TABLE_EMPLOYEES_ROLES . "," . TABLE_ROLES . ") " .
          "ON (" . TABLE_ACTIVITIES . ".timesheets_id=" . TABLE_TIMESHEETS . ".timesheets_id " .
            "AND " . TABLE_ACTIVITIES . ".tariffs_id=" . TABLE_TARIFFS . ".tariffs_id " .
            "AND " . TABLE_TARIFFS . ".employees_roles_id=" . TABLE_EMPLOYEES_ROLES . ".employees_roles_id " .
            "AND " . TABLE_EMPLOYEES_ROLES . ".roles_id=" . TABLE_ROLES . ".roles_id) " .
          "WHERE " . TABLE_TIMESHEETS . ".timesheets_start_date<='" . tep_strftime(DATE_FORMAT_DATABASE, $selected_date) . "' " .
            "AND " . TABLE_TIMESHEETS . ".timesheets_end_date>='" . tep_strftime(DATE_FORMAT_DATABASE, $selected_date) . "' " .
            "AND " . TABLE_ROLES . ".projects_id=" . TABLE_PROJECTS . ".projects_id) AS projects_calculated_hours_used " .
        "FROM " . TABLE_PROJECTS . " " .
        "WHERE projects_id=" . $projects_id . " " .
          "AND (projects_calculated_hours=0 " .
          "OR (projects_calculated_hours>0 " .
          "AND projects_calculated_hours_period='B')) " .
        "UNION " .
        "SELECT projects_calculated_hours," .
          "(SELECT IFNULL(SUM(activities_amount),0) " .
          "FROM " . TABLE_ACTIVITIES . " " .
          "LEFT JOIN (" . TABLE_TARIFFS . "," . TABLE_EMPLOYEES_ROLES . "," . TABLE_ROLES . ") " .
          "ON (" . TABLE_ACTIVITIES . ".tariffs_id=" . TABLE_TARIFFS . ".tariffs_id " .
            "AND " . TABLE_TARIFFS . ".employees_roles_id=" . TABLE_EMPLOYEES_ROLES . ".employees_roles_id " .
            "AND " . TABLE_EMPLOYEES_ROLES . ".roles_id=" . TABLE_ROLES . ".roles_id) " .
          "WHERE " . TABLE_ROLES . ".projects_id=" . TABLE_PROJECTS . ".projects_id) " .
        "FROM " . TABLE_PROJECTS . " " .
        "WHERE projects_id=" . $projects_id . " " .
          "AND projects_calculated_hours>0 " .
          "AND projects_calculated_hours_period='E' ");
      $project_result = $database->fetch_array($project_query);
      if (tep_not_null($project_result) && ($project_result['projects_calculated_hours']==0 || (round($project_result['projects_calculated_hours']-$project_result['projects_calculated_hours_used']-$activity_amount,2)>=0))) {
        return true;
      } else {
        return false;
      }
    }
  }
?>
