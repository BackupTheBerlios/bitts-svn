<?php
/****************************************************************************
 * CLASS FILE  : profile.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 01 july 2009
 * Description : Profile class
 *
 */

  class profile {
    private $id, $name, $right, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->right = array();
      $this->listing = array();

      if ($id != 0) {
        // Retrieve profile by id
        $id = $database->prepare_input($id);
        $profiles_query = $database->query("select profiles_name, profiles_rights_login, profiles_rights_projectlisting, profiles_rights_timeregistration, profiles_rights_analysis, profiles_rights_administration from " . TABLE_PROFILES . " where profiles_id = '" . (int)$id . "'");
        $profiles_result = $database->fetch_array($profiles_query);

        if (tep_not_null($profiles_result)) {
          // Profile exists
          $this->fill($profiles_result['profiles_name'],
                      $profiles_result['profiles_rights_login']==1,
                      $profiles_result['profiles_rights_projectlisting']==1,
                      $profiles_result['profiles_rights_timeregistration']==1,
                      $profiles_result['profiles_rights_analysis']==1,
                      $profiles_result['profiles_rights_administration']==1);
        }
      } else {
        // We probably created an empty profile object to retrieve the entire profile listing
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
      $profiles_array = array();

      $index = 0;
      $profiles_query = $database->query("select profiles_id from " . TABLE_PROFILES . " order by profiles_name");
      while ($profiles_result = $database->fetch_array($profiles_query)) {
        $profiles_array[$index] = new profile($profiles_result['profiles_id']);
        $index++;
      }

      return $profiles_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new profile if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The profile does not exist
        $database->query("insert into " . TABLE_PROFILES . " (profiles_name, profiles_rights_login , profiles_rights_projectlisting, profiles_rights_timeregistration, profiles_rights_analysis, profiles_rights_administration) values ('" . $this->name . "', '" . ($this->right['login']?1:0) . "', '" . ($this->right['projectlisting']?1:0) . "', '" . ($this->right['timeregistration']?1:0) . "', '" . ($this->right['analysis']?1:0) . "', '" . ($this->right['administration']?1:0) . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The profile exists, update the contents
        $profiles_query = $database->query("update " . TABLE_PROFILES . " set profiles_name='" . $this->name . "', profiles_rights_login='" . ($this->right['login']?1:0) . "', profiles_rights_projectlisting='" . ($this->right['projectlisting']?1:0) . "', profiles_rights_timeregistration='" . ($this->right['timeregistration']?1:0) . "', profiles_rights_analysis='" . ($this->right['analysis']?1:0) . "', profiles_rights_administration='" . ($this->right['administration']?1:0) . "' where profiles_id = '" . (int)$this->id . "'");
      }
    }

    public function delete($id = 0) {
      if ($id != 0) {
        // Create and delete profile
        $profile = new profile($id);
        $profile->delete();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $profiles_query = $database->query("delete from " . TABLE_PROFILES . " where profiles_id = '" . (int)$this->id . "'");
        // Reset id, otherwise one might think this profile (still) exists in db
        $this->id = 0;
      }
    }

    public function has_dependencies($id = 0) {
      if ($id != 0) {
        // Create and investigate profile
        $profile = new profile($id);
        return $profile->has_dependencies();
      } else {
        $database = $_SESSION['database'];
        $this->id = $database->prepare_input($this->id);
        $employees_query = $database->query("select 1 from " . TABLE_EMPLOYEES . " where profiles_id = '" . (int)$this->id . "'");
        $employees_result = $database->fetch_array($employees_query);
        return tep_not_null($employees_result);
      }
    }
  }
?>
