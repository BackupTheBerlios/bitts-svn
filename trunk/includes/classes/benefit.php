<?php
/****************************************************************************
 * CLASS FILE  : benefit.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 25 january 2011
 * Description : Benefit class
 *
 */

  class benefit {
    private $id, $start_date, $end_date, $credit, $granted, $comment, $employee, $role, $listing;

    public function __construct($id = 0, $employees_id = null) {
      $database = $_SESSION['database'];
      $this->id = $id;
      $this->listing = array();

      if ($id != 0) {
        // Get existing benefit
        $id = $database->prepare_input($id);
      	$benefits_query = $database->query("select benefits_start_date, benefits_end_date, benefits_credit, benefits_granted, benefits_comment, employees_id, roles_id from " . TABLE_BENEFITS . " where benefits_id = '" . (int)$id . "'");

      	$benefits_result = $database->fetch_array($benefits_query);

        $this->fill(tep_datetouts(DATE_FORMAT_DATABASE, $benefits_result['benefits_start_date']),
                    ($benefits_result['benefits_end_date']!='2099-12-31'?tep_datetouts(DATE_FORMAT_DATABASE, $benefits_result['benefits_end_date']):0),
                    $benefits_result['benefits_credit'],
                    $benefits_result['benefits_granted'],
                    $benefits_result['benefits_comment'],
                    $benefits_result['employees_id'],
                    $benefits_result['roles_id']);
      } else {
        // We probably created an empty benefit object to retrieve the entire benefit listing
        $this->listing = $this->get_array($employees_id);
      }
    }

    public static function get_array($employees_id = 0) {
      $database = $_SESSION['database'];
      $benefits_array = array();

      if ($employees_id != 0) {
        $index = 0;
        $employees_id = $database->prepare_input($employees_id);
      	$benefits_query = $database->query("select benefits_id from " . TABLE_BENEFITS . " where employees_id in (" . $employees_id . ") order by benefits_start_date");
        while ($benefits_result = $database->fetch_array($benefits_query)) {
          $benefits_array[$index] = new benefit($benefits_result['benefits_id']);
          $index++;
        }
      }
      return $benefits_array;
    }

    public function __get($varname) {
      switch ($varname) {
        case 'id':
          return $this->id;
        case 'start_date':
          return $this->start_date;
        case 'end_date':
          return $this->end_date;
        case 'credit':
          return $this->credit;
        case 'granted':
          return $this->granted;
        case 'comment':
          return $this->comment;
        case 'employee':
          return $this->employee;
        case 'role':
          return $this->role;
        case 'listing':
          return $this->listing;
        case 'listing_empty':
          return sizeof($this->listing) == 0;
      }
      return null;
    }

    public function fill($start_date = '0000-00-00 00:00:00', $end_date = '0000-00-00 00:00:00', $credit = 0, $granted = 0, $comment = '', $employees_id = 0, $roles_id = 0) {
      $this->start_date = $start_date;
      $this->end_date = $end_date;
      $this->credit = $credit;
      $this->granted = $granted;
      $this->comment = $comment;
      $this->employee = new employee($employees_id);
      $this->role = new role($roles_id);
    }

    public function save() {
      $database = $_SESSION['database'];
      // Insert a new benefit if one does not exist and retrieve the id
      if ($this->id == 0) {
        // The benefit does not exist
        $database->query("insert into " . TABLE_BENEFITS . " (benefits_start_date, benefits_end_date, benefits_credit, benefits_granted, benefits_comment, employees_id, roles_id) values ('" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', '" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', '" . $this->credit . "', '" . $this->granted . "', '" . $this->comment . "', '" . $this->employee->id . "', '" . $this->role->id . "')");
        $this->id = $database->insert_id(); // The proper id is now known
      } else {
        // The benefit exists, update the contents
        $this->id = $database->prepare_input($this->id);
        $benefit_query = $database->query("update " . TABLE_BENEFITS . " set benefits_start_date='" . tep_strftime(DATE_FORMAT_DATABASE, $this->start_date) . "', benefits_end_date='" . ($this->end_date!=0?tep_strftime(DATE_FORMAT_DATABASE, $this->end_date):'2099-12-31') . "', benefits_credit='" . $this->credit . "', benefits_granted='" . $this->granted . "', benefits_comment='" . $this->comment . "', employees_id='" . $this->employee->id . "', roles_id='" . $this->role->id . "' where benefits_id = '" . (int)$this->id . "'");
      }
    }

    public function delete() {
      $database = $_SESSION['database'];
      $benefits_query = $database->query("delete from " . TABLE_BENEFITS . " where benefits_id = '" . (int)$this->id . "'");
      // Reset id, otherwise one might think this benefit (still) exists in db
      $this->id = 0;
    }
  }
?>