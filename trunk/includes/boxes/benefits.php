<?php
/****************************************************************************
 * CODE FILE   : benefits.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 january 2011
 * Description : Information box listing granted benefits
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  $database = $_SESSION['database'];
  $benefits_query = $database->query("SELECT * FROM " . VIEW_BENEFITS .
                  " WHERE benefits_start_date <= '" . tep_periodenddate($_POST['period']) . "'" .
                  " AND benefits_end_date >= '" . tep_periodstartdate($_POST['period']) . "'" .
                  " AND employees_id = " . $_SESSION['employee']->id .
                  " AND roles_id = " . BENEFITS_LEAVE_ROLE . ";");
  $benefits_result = $database->fetch_array($benefits_query);
  if (tep_not_null($benefits_result)) {
?>
<!-- benefits //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_BENEFITS);

  new infoBoxHeading($info_box_contents, false, false);

  $benefits_total = $benefits_result['benefits_credit'] + $benefits_result['benefits_granted'];
  $benefits_remaining = $benefits_total - $benefits_result['benefits_used'];
  
  $info_box_contents = array();
  $info_box_contents[] = array('text' => "<table width='100%'><tr><td class='infoBoxContents'>" . BOX_BENEFITS_CREDIT . "</td><td class='infoBoxContentsRight'>" . tep_number_db_to_user($benefits_result['benefits_credit'], 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_GRANTED . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($benefits_result['benefits_granted'], 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContentsRight'>+</td><td class='infoBoxContentsRight'><hr></td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_TOTAL . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($benefits_total, 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_USED . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($benefits_result['benefits_used'], 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContentsRight'>-</td><td class='infoBoxContentsRight'><hr></td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_REMAINING . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($benefits_remaining, 2) . BOX_BENEFITS_HOURS . "<br>(" . tep_number_db_to_user($benefits_remaining / MINIMUM_HOURS_PER_DAY, 2) . BOX_BENEFITS_DAYS . ")</td></tr></table>");

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- benefits_eof //-->
<?php
  }
?>