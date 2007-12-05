<?php
/****************************************************************************
 * CLASS FILE  : project.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 05 december 2007
 * Description : .....
 *               .....
 */

  class project {
    private $project_id, $customer, $name, $description, $start_date, $end_date, $calculated_hours, $roles;

    function project($project_id = '') {
      $this->project_id = $project_id;
      $this->roles = array();

      if (tep_not_null($project_id)) {
        $project_id = tep_db_prepare_input($project_id);

        $project_query = tep_db_query("select customer_id, name, description, start_date, end_date, calculated_hours from " . TABLE_PROJECTS . " where project_id = '" . (int)$project_id . "'");
        $project_result = tep_db_fetch_array($project_query);

        $this->$customer = new customer($project_result['customer_id']);
        $this->$name = $project_result['name'];
        $this->$description = $project_result['description'];
        $this->$start_date = $project_result['start_date'];
        $this->$end_date = $project_result['end_date'];
        $this->$calculated_hours = $project_result['calculated_hours'];

        // Retrieve all roles for this project
        $this->roles = role::get_array($this->project_id);
      }
    }
  }
?>
