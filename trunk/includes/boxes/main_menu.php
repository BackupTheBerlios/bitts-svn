<?php
/****************************************************************************
 * CODE FILE   : information.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 14 april 2008
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */
?>
<!-- information //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_MAINMENU);

  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . BOX_MAINMENU_HOME . '</a><br>' .
                                         '<br>' .
                                         tep_href_link_switched(FILENAME_TIMEREGISTRATION, BOX_MAINMENU_TIMEREGISTRATION, $_SESSION['employee']->is_user) . '<br>' .
                                         tep_href_link_switched(FILENAME_ANALYSIS, BOX_MAINMENU_ANALYSIS, $_SESSION['employee']->is_analyst) . '<br>' .
                                         tep_href_link_switched(FILENAME_ADMINISTRATION, BOX_MAINMENU_ADMINISTRATION, $_SESSION['employee']->is_administrator));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->