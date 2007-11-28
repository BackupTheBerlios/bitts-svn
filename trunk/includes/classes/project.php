<?php
/****************************************************************************
 * CLASS FILE  : project.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 26 november 2007
 * Description : .....
 *               .....
 */

  class project {
    var $name, $customer_id, $start_date, $end_date, $calculated_hours, $roles;

    function project($project_id = '') {
      $this->roles = array();

      if (tep_not_null($project_id)) {
        $this->query($project_id);
      }
    }

    function query($project_id) {
      $project_id = tep_db_prepare_input($project_id);

      $project_query = tep_db_query("select name, customer_id, start_date, end_date, calculated_hours from " . TABLE_PROJECTS . " where project_id = '" . (int)$project_id . "'");
      $project = tep_db_fetch_array($project_query);

      $this->$name = $project['name'];
      $this->$customer_id = $project['customer_id'];
      $this->$start_date = $project['start_date'];
      $this->$end_date = $project['end_date'];
      $this->$calculated_hours = $project['calculated_hours'];

      $index = 0;
      $project_roles_query = tep_db_query("select role_id from " . TABLE_ROLES . " where project_id = '" . (int)$project_id . "'");
      while ($project_roles = tep_db_fetch_array($project_roles_query)) {
        $this->roles[$index] = new role($project_roles['role_id']);
        $index++;
      }
    }
  }
?>
