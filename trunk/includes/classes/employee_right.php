<?php
/****************************************************************************
 * CLASS FILE  : employee_right.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 29 june 2009
 * Description : Employee_right class
 *
 */

  class employee_right {
    private $id, $name, $right, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->right = array();
      $this->listing = array();

      if ($id != 0) {
        // Retrieve employee_right by id
        $id = $database->prepare_input($id);
        $employees_rights_query = $database->query("select employees_rights_name, employees_rights_login, employees_rights_projectlisting, employees_rights_timeregistration, employees_rights_analysis, employees_rights_administration from " . TABLE_EMPLOYEES_RIGHTS . " where employees_rights_id = '" . (int)$id . "'");
        $employees_rights_result = $database->fetch_array($employees_rights_query);

        if (tep_not_null($employees_rights_result)) {
          // Employee_right exists
          $this->fill($employees_rights_result['employees_rights_name'],
                      $employees_rights_result['employees_rights_login']==1,
                      $employees_rights_result['employees_rights_projectlisting']==1,
                      $employees_rights_result['employees_rights_timeregistration']==1,
                      $employees_rights_result['employees_rights_analysis']==1,
                      $employees_rights_result['employees_rights_administration']==1);
        }
      } else {
        // We probably created an empty employee_right object to retrieve the entire employee_right listing
        $this->listing = $this->get_array();
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
      	case 'name':
          return $this->name;
        case 'right':
          return $this->right;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $login = false, $projectlisting = false, $timeregistration = false, $analysis = false, $administration = false) {
      $this->name = $name;
      $this->right = array('login'=>$login,
                           'projectlisting'=>$projectlisting,
                           'timeregistration'=>$timeregistration,
                           'analysis'=>$analysis,
                           'administration'=>$administration);
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $employees_rights_array = array();

      $index = 0;
      $employees_rights_query = $database->query("select employees_rights_id from " . TABLE_EMPLOYEES_RIGHTS . " order by employees_rights_name");
      while ($employees_rights_result = $database->fetch_array($employees_rights_query)) {
        $employees_rights_array[$index] = new employee_right($employees_rights_result['employees_rights_id']);
        $index++;
      }

      return $employees_rights_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new employee_role if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The employee_role does not exist
        $database->query("insert into " . TABLE_EMPLOYEES_RIGHTS . " (employees_rights_name, employees_rights_login , employees_rights_projectlisting, employees_rights_timeregistration, employees_rights_analysis, employees_rights_administration) values ('" . $this->name . "', '" . ($this->right['login']?1:0) . "', '" . ($this->right['projectlisting']?1:0) . "', '" . ($this->right['timeregistration']?1:0) . "', '" . ($this->right['analysis']?1:0) . "', '" . ($this->right['administration']?1:0) . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The employee_role exists, update the contents
        $employees_roles_query = $database->query("update " . TABLE_EMPLOYEES_RIGHTS . " set employees_rights_name='" . $this->name . "', employees_rights_login='" . ($this->right['login']?1:0) . "', employees_rights_projectlisting='" . ($this->right['projectlisting']?1:0) . "', employees_rights_timeregistration='" . ($this->right['timeregistration']?1:0) . "', employees_rights_analysis='" . ($this->right['analysis']?1:0) . "', employees_rights_administration='" . ($this->right['administration']?1:0) . "' where employees_rights_id = '" . (int)$this->id . "'");
      }
    }

    public function delete($id = 0) {
      if ($id != 0) {
        // Create and delete employee_right
        $employee_right = new employee_right($id);
        $employee_right->delete();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $employees_rights_query = $database->query("delete from " . TABLE_EMPLOYEES_RIGHTS . " where employees_rights_id = '" . (int)$this->id . "'");
        // Reset id, otherwise one might think this employee_right (still) exists in db
        $this->id = 0;
      }
    }

    public function has_dependencies($id = 0) {
      if ($id != 0) {
        // Create and investigate employee_right
        $employee_right = new employee_right($id);
        return $employee_right->has_dependencies();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $employees_query = $database->query("select 1 from " . TABLE_EMPLOYEES . " where employees_rights_id = '" . (int)$this->id . "'");
        $employees_result = $database->fetch_array($employees_query);
        return tep_not_null($employees_result);
      }
    }
  }
?>
