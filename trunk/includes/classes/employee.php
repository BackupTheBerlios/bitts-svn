<?php
/****************************************************************************
 * CLASS FILE  : employee.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 15 september 2008
 * Description : Employee class
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  class employee {
    private $employee_id, $login, $fullname, $password, $is_user, $is_analyst, $is_administrator;

    public function __construct($selection = '', $select_by = 'employees_id') {
      $this->select($selection, $select_by);
    }

    public function __get($varname) {
      switch ($varname) {
        case 'employee_id':
          return $this->employee_id;
      	case 'login':
          return $this->login;
        case 'fullname':
          return $this->fullname;
        case 'is_user':
          return ($this->is_user == 1);
        case 'is_analyst':
          return ($this->is_analyst == 1);
        case 'is_administrator':
          return ($this->is_administrator == 1);
      }
      return null;
    }

    public function __set($varname, $value) {
      switch ($varname) {
      	case 'login': {
      	  $this->login = $value;
      	  break;
      	}
      	case 'fullname': {
      	  $this->fullname = $value;
      	  break;
      	}
      	case 'password': {
      	  $this->password = $value;
      	  break;
      	}
      	case 'is_user': {
      	  $this->is_user = ($value ? 1 : 0);
      	  break;
      	}
        case 'is_analyst': {
      	  $this->is_analyst = ($value ? 1 : 0);
          break;
      	}
        case 'is_administrator': {
      	  $this->is_administrator = ($value ? 1 : 0);
          break;
      	}
      }
    }

    public function select($selection = '', $select_by = 'employees_id') {
      if (tep_not_null($selection)) {
      	$database = $_SESSION['database'];
        $selection = $database->prepare_input($selection);

        $employee_query = $database->query("select employees_id, employees_login, employees_fullname, employees_password, employees_is_user, employees_is_analyst, employees_is_administrator from " . TABLE_EMPLOYEES . " where " . $select_by . " = '" . $selection . "'");
        $employee_result = $database->fetch_array($employee_query);

        $this->fill($employee_result['employees_id'],
                    $employee_result['employees_login'],
                    $employee_result['employees_fullname'],
                    $employee_result['employees_password'],
                    $employee_result['employees_is_user'],
                    $employee_result['employees_is_analyst'],
                    $employee_result['employees_is_administrator']);
      }
    }

    private function fill($employee_id = '', $login = '', $fullname = '', $password = '', $is_user = 0, $is_analyst = 0, $is_administrator = 0) {
      $this->employee_id = $employee_id;
      $this->login = $login;
      $this->fullname = $fullname;
      $this->password = $password;
      $this->is_user = $is_user;
      $this->is_analyst = $is_analyst;
      $this->is_administrator = $is_administrator;
    }

    public static function verify_login($login = '', $password = '') {
      if (tep_not_null($login) && tep_not_null($password)) {
        $database = $_SESSION['database'];
        $employee_query = 'select employees_login, employees_password from ' . TABLE_EMPLOYEES;
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

    public static function get_array($only_enabled_employees = false) {
      $database = $_SESSION['database'];
      $employee_array = array();

      $employee_query_string = "select employees_id from " . TABLE_EMPLOYEES;
      if ($only_enabled_employees) {
        $employee_query_string .= " where employees_is_user = 1 or employees_is_analyst = 1 or employees_is_administrator = 1";
      }
      $employee_query = $database->query($employee_query_string);
      while ($employee_result = $database->fetch_array($employee_query)) {
        $employee_array[$index] = new employee($employee_result['employees_id']);
        $index++;
      }
      return $employee_array;
    }
  }
?>