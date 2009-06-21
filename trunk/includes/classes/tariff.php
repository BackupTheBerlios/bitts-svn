<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 21 june 2009
 * Description : Tariff class
 */

  class tariff {
    private $id, $amount, $start_date, $end_date, $unit, $employee_role, $listing;

    public function __construct($id = 0, $employees_roles_id = null) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($id != 0) {
        $id = $database->prepare_input($id);

        $tariffs_query = $database->query("select tariffs_amount, tariffs_start_date, tariffs_end_date, units_id, employees_roles_id from " . TABLE_TARIFFS . " where tariffs_id = '" . (int)$id . "'");
        $tariffs_result = $database->fetch_array($tariffs_query);

        $this->fill($tariffs_result['tariffs_amount'],
                    tep_datetouts(DATE_FORMAT_DATABASE, $tariffs_result['tariffs_start_date']),
                    ($tariffs_result['tariffs_end_date']!='2099-12-31'?tep_datetouts(DATE_FORMAT_DATABASE, $tariffs_result['tariffs_end_date']):0),
                    $tariffs_result['units_id'],
                    $tariffs_result['employees_roles_id']);
      } else {
        // We probably created an empty tariff object to retrieve the entire tariff listing
        $this->listing = $this->get_array($employees_roles_id);
      }
    }

    public static function get_array($employees_roles_id = 0) {
      $database = $_SESSION['database'];
      $tariffs_array = array();

      if ($employees_roles_id != 0) {
        $index = 0;
        $employees_roles_id = $database->prepare_input($employees_roles_id);
        $tariffs_query = $database->query("select t.tariffs_id from " . TABLE_UNITS . " u, " . TABLE_TARIFFS . " t where t.units_id = u.units_id and t.employees_roles_id in (" . $employees_roles_id . ") order by t.tariffs_start_date, u.units_name");
        while ($tariffs_result = $database->fetch_array($tariffs_query)) {
          $tariffs_array[$index] = new tariff($tariffs_result['tariffs_id']);
          $index++;
        }
      }
      return $tariffs_array;
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
        case 'amount':
          return $this->amount;
        case 'start_date':
          return $this->start_date;
        case 'end_date':
          return $this->end_date;
       	case 'unit':
          return $this->unit;
        case 'employee_role':
          return $this->employee_role;
        case 'projects_id':
          return employee_role::get_project_id($this->employee_role->id);
      	case 'project_name':
          return employee_role::get_project_name($this->employee_role->id);
      	case 'roles_id':
          return employee_role::get_role_id($this->employee_role->id);
        case 'role_name':
          return employee_role::get_role_name($this->employee_role->id);
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function __set($varname, $value) {
      switch ($varname) {
      	case 'start_date':
          $this->start_date = $value;
          break;
      	case 'end_date':
          $this->end_date = $value;
          break;
      }
    }

    public function fill($amount = 0, $start_date = 0, $end_date = 0, $units_id = 0, $employees_roles_id = 0) {
      $this->amount = $amount;
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->unit = new unit($units_id);
      $this->employee_role = new employee_role($employees_roles_id, 'dummy');
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new tariff if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The tariff does not exist
        $database->query("insert into " . TABLE_TARIFFS . " (tariffs_amount, tariffs_start_date, tariffs_end_date, units_id, employees_roles_id) values ('" . $this->amount . "', '" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', '" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', '" . $this->unit->id . "', '" . $this->employee_role->id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The tariff exists, update the contents
        $tariffs_query = $database->query("update " . TABLE_TARIFFS . " set tariffs_amount='" . $this->amount . "', tariffs_start_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', tariffs_end_date='" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', units_id='" . $this->unit->id . "', employees_roles_id='" . $this->employee_role->id . "' where tariffs_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $tariffs_query = $database->query("delete from " . TABLE_TARIFFS . " where tariffs_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this tariff (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $id = $database->prepare_input($this->id);
      $activities_query = $database->query("select 1 from " . TABLE_ACTIVITIES . " where tariffs_id = '" . (int)$id . "'");
      $activities_result = $database->fetch_array($activities_query);
      return tep_not_null($activities_result);
    }

    public function has_duplicates($start_date, $end_date, $units_id, $employees_roles_id) {
      $database = $_SESSION['database'];
      $duplicates_query = $database->query("select 1 from " . TABLE_TARIFFS .
                                           " where tariffs_id != " . (tep_not_null($this->id)?$this->id:0) .
                                           " and units_id = " . $units_id .
                                           " and employees_roles_id = " . $employees_roles_id .
                                           " and tariffs_start_date <= '" . ($end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $end_date):'2099-12-31') . "'" .
                                           " and tariffs_end_date >= '" . tep_strftime(DATE_FORMAT_DATABASE, $start_date) . "'");
      $duplicates_result = $database->fetch_array($duplicates_query);
      return tep_not_null($duplicates_result);
    }

    public function validate_amount($value) {
      $value = str_replace(",", ".", $value);
      $value = '0' . $value;
      return (substr_count($value, ".") <= 1 &&
              is_numeric($value) &&
              (float)$value >= 0);
    }

    public static function format_amount($value) {
      // Format the string
      $value = str_replace(",", ".", $value);
      $value = number_format(floatval($value), 2);          
      if ($value > 9999.99) {
        $value = 9999.99;
      } else if ($value < 0) {
        $value = 0.00;
      }
      return $value;
    }

    public function get_parent_id() {
      return $this->employee_role->id;
    }

    public function get_tariffs($parents_id, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2) {
      $database = $_SESSION['database'];
      $tariffs_array = array();

      if (tep_not_null(parents_id)) {
        $index = 0;
        $tariffs_query = $database->query("select tariffs_id from " . TABLE_TARIFFS . " where employees_roles_id in (" . $parents_id . ") and " . $column_name1 . " " . $comparison1 . " '" . $value1 . "'" . (tep_not_null($delimiter)?' ' . $delimiter . " " . $column_name2 . " " . $comparison2 . " '" . $value2 . "'":''));
        while ($tariffs_result = $database->fetch_array($tariffs_query)) {
          $tariffs_array[$index] = new tariff($tariffs_result['tariffs_id']);
          $index++;
        }
      }

      return $tariffs_array;
    }

    public function has_activities($parents_id, $column_name1, $comparison1, $value1, $delimiter = '', $column_name2 = '', $comparison2 = '', $value2 = '') {
      $database = $_SESSION['database'];
      $tariffs_listing = '';

      if (tep_not_null($parents_id)) {
        $tariffs_query = $database->query("select tariffs_id from " . TABLE_TARIFFS . " where employees_roles_id in (" . $parents_id . ")");
        while ($tariffs_result = $database->fetch_array($tariffs_query)) {
          if (tep_not_null($tariffs_listing)) {
            $tariffs_listing .= ',';
          }
          $tariffs_listing .= ''.$tariffs_result['tariffs_id'];
        }
      } else {
        $tariffs_listing = $this->id;
      }

      $activity = new activity();
      return $activity->get_activities($tariffs_listing, $column_name1, $comparison1, $value1, $delimiter, $column_name2, $comparison2, $value2);
    }

    public static function get_selectable_tariffs($employees_id = 0, $date = '0000-00-00', $roles_id = 0) {
      $tariffs_array = array();
      $database = $_SESSION['database'];
      // Create query
      $tariffs_query = "select tariffs_id, tariffs_amount, units_name, units_description from " . TABLE_TARIFFS . ", " . TABLE_UNITS . ", " . TABLE_EMPLOYEES_ROLES;
      $tariffs_query .= " where " . TABLE_TARIFFS . ".units_id = " . TABLE_UNITS . ".units_id";
      $tariffs_query .= " and " . TABLE_TARIFFS . ".employees_roles_id = " . TABLE_EMPLOYEES_ROLES . ".employees_roles_id";
      $tariffs_query .= " and " . TABLE_EMPLOYEES_ROLES . ".employees_id = " . $employees_id;
      $tariffs_query .= " and " . TABLE_EMPLOYEES_ROLES . ".roles_id = " . $roles_id;
      $tariffs_query .= " and " . TABLE_TARIFFS . ".tariffs_start_date <= '" . $date . "'";
      $tariffs_query .= " and " . TABLE_TARIFFS . ".tariffs_end_date >= '" . $date . "'";
      $tariffs_query .= " order by units_name";
      // Prepare query
      $tariffs_query = $database->query($tariffs_query);        
      // Execute query and read the contents
      while ($tariffs_result = $database->fetch_array($tariffs_query)) {
        array_push($tariffs_array, $tariffs_result);
      }
      return $tariffs_array;
    }

    public static function ticket_entry_is_required($id) {
      $database = $_SESSION['database'];
      $tariffs_query = $database->query("select employees_roles_id from " . TABLE_TARIFFS . " where tariffs_id = '" . $id . "'");
      $tariffs_result = $database->fetch_array($tariffs_query);
      return employee_role::ticket_entry_is_required($tariffs_result['employees_roles_id']);
    }
  }
?>