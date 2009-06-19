<?php
/****************************************************************************
 * CLASS FILE  : tariff.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 june 2009
 * Description : Tariff class
 */

  class tariff {
    private $id, $amount, $start_date, $end_date, $unit, $employees_roles_id;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;

      if ($id != 0) {
        $id = $database->prepare_input($id);

        $tariffs_query = $database->query("select tariffs_amount, tariffs_start_date, tariffs_end_date, units_id, employees_roles_id from " . TABLE_TARIFFS . " where tariffs_id = '" . (int)$id . "'");
        $tariffs_result = $database->fetch_array($tariffs_query);

        $this->fill($tariffs_result['tariffs_amount'],
                    tep_datetouts(DATE_FORMAT_DATABASE, $tariffs_result['tariffs_start_date']),
                    ($tariffs_result['tariffs_end_date']!='2099-12-31'?tep_datetouts(DATE_FORMAT_DATABASE, $tariffs_result['tariffs_end_date']):0),
                    $tariffs_result['units_id'],
                    $tariffs_result['employees_roles_id']);
      }
    }

    public static function get_array($employees_roles_id = 0) {
      $database = $_SESSION['database'];
      $tariffs_array = array();

      if ($employees_roles_id != 0) {
        $index = 0;
        $employees_roles_id = $database->prepare_input($employees_roles_id);
        $tariffs_query = $database->query("select tariffs_id from " . TABLE_TARIFFS . " where employees_roles_id = '" . (int)$employees_roles_id . "'");
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
        case 'employees_roles_id':
          return $this->employees_roles_id;
      	case 'projects_id':
          return employee_role::get_project_id($this->employees_roles_id);
      	case 'project_name':
          return employee_role::get_project_name($this->employees_roles_id);
      	case 'roles_id':
          return employee_role::get_role_id($this->employees_roles_id);
        case 'role_name':
          return employee_role::get_role_name($this->employees_roles_id);
       	case 'unit':
          return $this->unit;
      	case 'unit_name':
      	  return $this->unit->name;
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

    public function fill($amount = '', $start_date = 0, $end_date = 0, $units_id = 0, $employees_roles_id = 0) {
      $this->amount = $amount;
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->unit = new unit($units_id);
      $this->employees_roles_id = $employees_roles_id;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new tariff if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The tariff does not exist
        $database->query("insert into " . TABLE_TARIFFS . " (tariffs_amount, tariffs_start_date, tariffs_end_date, units_id, employees_roles_id) values ('" . $this->amount . "', '" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', '" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', '" . $this->unit->id . "', '" . $this->employees_roles_id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The tariff exists, update the contents
        $tariffs_query = $database->query("update " . TABLE_TARIFFS . " set tariffs_amount='" . $this->amount . "', tariffs_start_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', tariffs_end_date='" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', units_id='" . $this->unit->id . "', employees_roles_id='" . $this->employees_roles_id . "' where tariffs_id = '" . (int)$this->id . "'");
      }
    }

    public function get_parent_id() {
      return $this->employees_roles_id;
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