<?php
/****************************************************************************
 * CODE FILE   : benefits.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 march 2011
 * Description : Information box listing granted benefits
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  $_SESSION['benefit'] = new benefit(0, $_SESSION['employee']->id, $_POST['period']);

  if (!$_SESSION['benefit']->isempty) {
?>
<!-- benefits //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_BENEFITS);

  new infoBoxHeading($info_box_contents, false, false);

  $benefits_total = $_SESSION['benefit']->credit + $_SESSION['benefit']->granted;
  $benefits_remaining = $benefits_total - $_SESSION['benefit']->used;
  
  $info_box_contents = array();
  $info_box_contents[] = array('text' => "<table width='100%'><tr><td class='infoBoxContents'>" . BOX_BENEFITS_CREDIT . "</td><td class='infoBoxContentsRight'>" . tep_number_db_to_user($_SESSION['benefit']->credit, 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_GRANTED . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($_SESSION['benefit']->granted, 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContentsRight'>+</td><td class='infoBoxContentsRight'><hr></td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_TOTAL . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($benefits_total, 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
                                         "<tr><td class='infoBoxContents'>" . BOX_BENEFITS_USED . "</td><td class='infoBoxContentsRight'>". tep_number_db_to_user($_SESSION['benefit']->used, 2) . BOX_BENEFITS_HOURS . "</td></tr>" .
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