<?php
/****************************************************************************
 * CLASS FILE  : category.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 june 2009
 * Description : Category class file
 */

  class category {
    private $id, $name, $listing;

    public function __construct($id = 0) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($this->id != 0) {
        $this->id = $database->prepare_input($this->id);

        $categories_query = $database->query("select categories_name from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$id . "'");
        $categories_result = $database->fetch_array($categories_query);

        if (tep_not_null($categories_result)) {
          // Category exists
          $this->fill($categories_result['categories_name']);
        }
      } else {
        // We probably created an empty category object to retrieve the entire category listing
        $this->listing = $this->get_array();
      }
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
      	case 'name':
          return $this->name;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($name = '') {
      $this->name = $name;
    }

    private function get_array() {
      $database = $_SESSION['database'];
      $categories_array = array();

      $index = 0;
      $categories_query = $database->query("select categories_id from " . TABLE_CATEGORIES . " order by categories_name");
      while ($categories_result = $database->fetch_array($categories_query)) {
        $categories_array[$index] = new category($categories_result['categories_id']);
        $index++;
      }

      return $categories_array;
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new category if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The category does not exist
        $database->query("insert into " . TABLE_CATEGORIES . " (categories_name) values ('" . $this->name . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The category exists, update the contents
        $categories_query = $database->query("update " . TABLE_CATEGORIES . " set categories_name='" . $this->name . "' where categories_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $categories_query = $database->query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this category (still) exists in db
      $this->id = 0;
    }

    public function has_dependencies() {
      $database = $_SESSION['database'];
      $this->id = $database->prepare_input($this->id);
      $roles_query = $database->query("select 1 from " . TABLE_ROLES . " where categories_id = '" . (int)$this->id . "'");
      $roles_result = $database->fetch_array($roles_query);
      return tep_not_null($roles_result);
    }
  }
?>