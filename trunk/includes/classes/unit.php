<?php
/****************************************************************************
 * CLASS FILE  : unit.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 june 2009
 * Description : Unit class file
 */

  class unit {
    private $id, $name, $description, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($this->id != 0) {
        $this->id = $database->prepare_input($this->id);

        $units_query = $database->query("select units_name, units_description from " . TABLE_UNITS . " where units_id = '" . (int)$id . "'");
        $units_result = $database->fetch_array($units_query);

        if (tep_not_null($units_result)) {
          // Unit exists
          $this->fill($units_result['units_name'],
                      $units_result['units_description']);
        }
      } else {
        // We probably created an empty unit object to retrieve the entire unit listing
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
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $description = '') {
      $this->name = $name;
      $this->description = $description;
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $units_array = array();

      $index = 0;
      $units_query = $database->query("select units_id from " . TABLE_UNITS . " order by units_name");
      while ($units_result = $database->fetch_array($units_query)) {
        $units_array[$index] = new unit($units_result['units_id']);
        $index++;
      }

      return $units_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new unit if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The unit does not exist
        $database->query("insert into " . TABLE_UNITS . " (units_name, units_description) values ('" . $this->name . "', '" . $this->description . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The unit exists, update the contents
        $units_query = $database->query("update " . TABLE_UNITS . " set units_name='" . $this->name . "', units_description='" . $this->description . "' where units_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $units_query = $database->query("delete from " . TABLE_UNITS . " where units_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this unit (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $this->id = $database->prepare_input($this->id);
      $tariffs_query = $database->query("select 1 from " . TABLE_TARIFFS . " where units_id = '" . (int)$this->id . "'");
      $tariffs_result = $database->fetch_array($tariffs_query);
      return tep_not_null($tariffs_result);
    }
  }
?>