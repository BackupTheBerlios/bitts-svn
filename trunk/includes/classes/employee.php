<?php
/****************************************************************************
 * CLASS FILE  : employee.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 14 june 2009
 * Description : Employee class
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  class employee {
    private $id, $login, $fullname, $is_user, $is_analyst, $is_administrator, $listing;

    public function __construct($selection = '', $select_by = 'employees_id') {
      $this->listing = array();

      if (tep_not_null($selection)) {
        $database = $_SESSION['database'];
        $selection = $database->prepare_input($selection);

        $employee_query = $database->query("select employees_id, employees_login, employees_fullname, employees_password, employees_is_user, employees_is_analyst, employees_is_administrator from " . TABLE_EMPLOYEES . " where " . $select_by . " = '" . $selection . "'");
        $employee_result = $database->fetch_array($employee_query);

        $this->fill($employee_result['employees_id'],
                    $employee_result['employees_login'],
                    $employee_result['employees_fullname'],
                    ($employee_result['employees_is_user']==1),
                    ($employee_result['employees_is_analyst']==1),
                    ($employee_result['employees_is_administrator']==1));
      } else {
        // We probably created an empty Employee object to retrieve the entire employee listing
        $this->id = 0;
        $this->listing = $this->get_array(true);
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
      	case 'login':
          return $this->login;
        case 'fullname':
          return $this->fullname;
        case 'is_user':
          return $this->is_user;
        case 'is_analyst':
          return $this->is_analyst;
        case 'is_administrator':
          return $this->is_administrator;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($id = 0, $login = '', $fullname = '', $is_user = false, $is_analyst = false, $is_administrator = false) {
      $this->id = $id;
      $this->login = $login;
      $this->fullname = $fullname;
      $this->is_user = $is_user;
      $this->is_analyst = $is_analyst;
      $this->is_administrator = $is_administrator;
    }

    public static function verify_login($login = '', $password = '') {
      if (tep_not_null($login) && tep_not_null($password)) {
        $database = $_SESSION['database'];
        $employee_query = 'select 1 from ' . TABLE_EMPLOYEES;
        $employee_query .= " where employees_login = '" . $login . "'";
        $employee_query .= " and employees_password = password('" . $password . "')";
        $employee_query .= ' and (employees_is_user or employees_is_analyst or employees_is_administrator)';
        $employee_query = $database->query($employee_query);
        if ($employee_result = $database->fetch_array($employee_query)) {
          return TRUE;
        }
      }
      return FALSE;
    }

    public static function password_is_empty($login = '') {
      if (tep_not_null($login)) {
        $database = $_SESSION['database'];
        $employee_query = 'select employees_password from ' . TABLE_EMPLOYEES;
        $employee_query .= " where employees_login = '" . $login . "'";
        $employee_query .= " and employees_password = ''";
        $employee_query .= ' and (employees_is_user or employees_is_analyst or employees_is_administrator)';
        $employee_query = $database->query($employee_query);
        if ($employee_result = $database->fetch_array($employee_query)) {
          return TRUE;
        }
      }
      return FALSE;
    }  

    public static function set_password($login = '', $password = '') {
      $database = $_SESSION['database'];
      $employee_query = 'update ' . TABLE_EMPLOYEES;
      if ($password == '') {
        $employee_query .= " set employees_password = ''";
      } else {
        $employee_query .= " set employees_password = password('" . $password . "')";
      }
      $employee_query .= " where employees_login = '" . $login . "'";
      $database->query($employee_query);
    }

    public function reset_password($id = 0) {
      if ($id != 0) {
        // Create employee and reset the password
        $employee = new employee($id);
        $employee->reset_password();
      } else {
        $database = $_SESSION['database'];
        $employee_query = 'update ' . TABLE_EMPLOYEES . " set employees_password = '' where employees_id = '" . $this->id . "'";
        $database->query($employee_query);
      }
    }

    public static function get_array($show_all_employees = false) {
      $database = $_SESSION['database'];
      $employee_array = array();

      $index = 0;
      $employee_query_string = "select employees_id from " . TABLE_EMPLOYEES;
      if (!$show_all_employees) {
        $employee_query_string .= " where employees_is_user = 1 or employees_is_analyst = 1 or employees_is_administrator = 1";
      }
      $employee_query_string .= " order by employees_id";
      $employee_query = $database->query($employee_query_string);
      while ($employee_result = $database->fetch_array($employee_query)) {
        $employee_array[$index] = new employee($employee_result['employees_id']);
        $index++;
      }
      return $employee_array;
    }

    public function save($id = 0, $login = '', $fullname = '', $is_user = false, $is_analyst = false, $is_administrator = false) {
      // Check if this operation is requested to the default object
      if ($id != 0) {
        // Create, fill and save the employee
        $employee = new employee($id);
        $employee->fill($id, $login, $fullname, $is_user, $is_analyst, $is_administrator);
        $employee->save();
      } else {
        $database = $_SESSION['database'];
        // Insert a new entry if one does not exist or update the existing one
        if (!$this->id_exists($this->id)) {
          // The entry does not exist
          $database->query("insert into " . TABLE_EMPLOYEES . " (employees_id, employees_login, employees_fullname, employees_is_user, employees_is_analyst, employees_is_administrator) values ('" . $this->id . "', '" . $this->login . "', '" . $this->fullname . "', '" . ($this->is_user?1:0) . "', '" . ($this->is_analyst?1:0) . "', '" . ($this->is_administrator?1:0) . "')");
        } else {
          // The entry exists, update the contents
          $activity_query = $database->query("update " . TABLE_EMPLOYEES . " set employees_id='" . $this->id . "', employees_login='" . $this->login . "', employees_fullname='" . $this->fullname . "', employees_is_user='" . ($this->is_user?1:0) . "', employees_is_analyst='" . ($this->is_analyst?1:0) . "', employees_is_administrator='" . ($this->is_administrator?1:0) . "' where employees_id = '" . (int)$this->id . "'");
        }
      }
    }

    public function delete($id = 0) {
      if ($id != 0) {
        // Create and delete employee
        $employee = new employee($id);
        $employee->delete();
      } else {
        $database = $_SESSION['database'];
        $id = $database->prepare_input($id);
        $employee_query = $database->query("delete from " . TABLE_EMPLOYEES . " where employees_id = '" . (int)$this->id . "'");
        // Reset id, otherwise one might think this employee (still) exists in db
        $this->id = 0;
      }
    }

    public function has_dependencies($id = 0) {
      if ($id != 0) {
        // Create and investigate employee
        $employee = new employee($id);
        return $employee->has_dependencies();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $employee_role_query = $database->query("select 1 from " . TABLE_EMPLOYEES_ROLES . " where employees_id = '" . (int)$this->id . "'");
        $employee_role_result = $database->fetch_array($employee_role_query);
        return tep_not_null($employee_role_result);
      }
    }

    public function validate_id($value) {
      $value = str_replace(",", ".", $value);
      $value = '0' . $value;
      return (substr_count($value, ".") == 0 &&
              is_numeric($value) &&
              (int)$value > 0);
      return false;
    }

    public function id_exists($id) {
      $database = $_SESSION['database'];
      $id = $database->prepare_input($id);
      $employee_query = $database->query("select 1 from " . TABLE_EMPLOYEES . " where employees_id = '" . (int)$id . "'");
      $employee_result = $database->fetch_array($employee_query);
      return tep_not_null($employee_result);
    }
  }
?>