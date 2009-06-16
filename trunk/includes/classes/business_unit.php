<?php
/****************************************************************************
 * CLASS FILE  : business_unit.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 june 2009
 * Description : Business Unit class file
 */

  class business_unit {
    private $id, $name, $image, $image_position, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($this->id != 0) {
        $this->id = $database->prepare_input($this->id);

        $business_units_query = $database->query("select business_units_name, business_units_image, business_units_image_position from " . TABLE_BUSINESS_UNITS . " where business_units_id = '" . (int)$id . "'");
        $business_units_result = $database->fetch_array($business_units_query);

        if (tep_not_null($business_units_result)) {
          // Business Unit exists
          $this->fill($business_units_result['business_units_name'],
                      $business_units_result['business_units_image'],
                      $business_units_result['business_units_image_position']);
        }
      } else {
        // We probably created an empty business_unit object to retrieve the entire business_unit listing
        $this->listing = $this->get_array();
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
      	case 'name':
          return $this->name;
        case 'image':
          return $this->image;
        case 'image_position':
          return $this->image_position;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '', $image = '', $image_position = '') {
      $this->name = $name;
      $this->image = $image;
      $this->image_position = $image_position;
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $business_units_array = array();

      $index = 0;
      $business_units_query = $database->query("select business_units_id from " . TABLE_BUSINESS_UNITS . " order by business_units_name");
      while ($business_units_result = $database->fetch_array($business_units_query)) {
        $business_units_array[$index] = new business_unit($business_units_result['business_units_id']);
        $index++;
      }

      return $business_units_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new business_unit if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The business_unit does not exist
        $database->query("insert into " . TABLE_BUSINESS_UNITS . " (business_units_name, business_units_image, business_units_image_position) values ('" . $this->name . "', '" . $this->image . "', '" . $this->image_position . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The business_unit exists, update the contents
        $business_units_query = $database->query("update " . TABLE_BUSINESS_UNITS . " set business_units_name='" . $this->name . "', business_units_image='" . $this->image . "', business_units_image_position='" . $this->image_position . "' where business_units_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $business_units_query = $database->query("delete from " . TABLE_BUSINESS_UNITS . " where business_units_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this business_unit (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $this->id = $database->prepare_input($this->id);
      $projects_query = $database->query("select 1 from " . TABLE_PROJECTS . " where business_units_id = '" . (int)$this->id . "'");
      $projects_result = $database->fetch_array($projects_query);
      return tep_not_null($projects_result);
    }
  }
?>