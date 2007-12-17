<?php
/****************************************************************************
 * CLASS FILE  : employee.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  class employee {
    private $employee_id, $login, $fullname, $password, $is_user, $is_, $is_administrator;

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
          return ($this->is_user = 1);
              case 'is_analyst':
          return ($this->is_analyst = 1);
              case 'is_administrator':
          return ($this->is_administrator = 1);
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
      	  $this->is_user = ($value = true ? 1 : 0);
      	  break;
      	}
        case 'is_analyst': {
      	  $this->is_analyst = ($value = true ? 1 : 0);
          break;
      	}
        case 'is_administrator': {
      	  $this->is_administrator = ($value = true ? 1 : 0);
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
      $this->fullname = $login;
      $this->fullname = $fullname;
      $this->password = $password;
      $this->is_user = $is_user;
      $this->is_analyst = $is_analyst;
      $this->is_administrator = $is_administrator;
    }
  }
?>