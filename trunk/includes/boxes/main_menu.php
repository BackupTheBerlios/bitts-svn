<?php
/****************************************************************************
 * CODE FILE   : main_menu.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 26 may 2008
 * Description : Main navigation menu
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */
?>
<!-- main_menu //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_MAINMENU);

  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => (tep_post_or_get('mPath')=='11'||tep_post_or_get('mPath')==''?'<b>':'<a href="' . tep_href_link(FILENAME_DEFAULT, 'mPath=11') . '">') . BOX_MAINMENU_HOME . (tep_post_or_get('mPath')=='11'||tep_post_or_get('mPath')==''?'</b>':'</a>') . '<br>' .
                                         '<br>' .
                                         (tep_post_or_get('mPath')=='21'?'<b>' . BOX_MAINMENU_TIMEREGISTRATION . '</b>':tep_href_link_switched(FILENAME_TIMEREGISTRATION, tep_create_parameters(array('mPath'=>'21'), array('period', 'selected_date', 'action')), BOX_MAINMENU_TIMEREGISTRATION, $_SESSION['employee']->is_user)) . '<br>' .
                                         (tep_post_or_get('mPath')=='31'?'<b>' . BOX_MAINMENU_ANALYSIS . '</b>':tep_href_link_switched(FILENAME_ANALYSIS, 'mPath=31', BOX_MAINMENU_ANALYSIS, $_SESSION['employee']->is_analyst)) . '<br>' .
                                         (tep_post_or_get('mPath')=='41'?'<b>' . BOX_MAINMENU_ADMINISTRATION . '</b><br>' .
                                           '&nbsp;&nbsp;&nbsp;' . tep_href_link_switched(FILENAME_ADMINISTRATION_PROJECTS, '', BOX_MAINMENU_ADMINISTRATION_PROJECTS, $_SESSION['employee']->is_administrator) . '<br>' .
                                           '&nbsp;&nbsp;&nbsp;' . tep_href_link_switched(FILENAME_ADMINISTRATION_CUSTOMERS, '', BOX_MAINMENU_ADMINISTRATION_CUSTOMERS, $_SESSION['employee']->is_administrator) . '<br>' .
                                           '&nbsp;&nbsp;&nbsp;' . tep_href_link_switched(FILENAME_ADMINISTRATION_EMPLOYEES, '', BOX_MAINMENU_ADMINISTRATION_EMPLOYEES, $_SESSION['employee']->is_administrator) :
                                           tep_href_link_switched(substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1), tep_create_parameters(array('mPath'=>'41'), array('period', 'selected_date', 'action')), BOX_MAINMENU_ADMINISTRATION, $_SESSION['employee']->is_administrator)));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- main_menu_eof //-->