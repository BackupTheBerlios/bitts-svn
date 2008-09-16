<?php
/****************************************************************************
 * CODE FILE   : main_menu.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 september 2008
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
  $info_box_contents[] = array('text' => tep_draw_form('menu_11', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('mPath'=>'11'), null, 'hidden_field') . tep_href_submit(BOX_MAINMENU_HOME, ($_POST['mPath']=='11'||$_POST['mPath']==''?'style="font-weight:bold;cursor:default" disabled':'')) . '</form><br>' .
                                         '<br>' .
                                         tep_draw_form('menu_21', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('mPath'=>'21'), array('period', 'selected_date', 'action'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_TIMEREGISTRATION, ($_SESSION['employee']->is_user?($_POST['mPath']=='21'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         tep_draw_form('menu_31', tep_href_link(FILENAME_ANALYSIS)) . tep_create_parameters(array('mPath'=>'31'), null, 'hidden_field') . tep_href_submit(BOX_MAINMENU_ANALYSIS, ($_SESSION['employee']->is_analyst?($_POST['mPath']=='31'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         tep_draw_form('menu_41', tep_href_link(substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1))) . tep_create_parameters(array('mPath'=>'41'), array('period', 'selected_date', 'action'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_ADMINISTRATION, ($_SESSION['employee']->is_administrator?($_POST['mPath']=='41'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>' .
                                         ($_POST['mPath']=='41'?'<br>' .
                                           tep_draw_form('menu_42', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('mPath'=>'42'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_PROJECTS, ($_SESSION['employee']->is_administrator?($_POST['mPath']=='42'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_43', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('mPath'=>'43'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_CUSTOMERS, ($_SESSION['employee']->is_administrator?($_POST['mPath']=='43'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_44', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . tep_create_parameters(array('mPath'=>'44'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_EMPLOYEES, ($_SESSION['employee']->is_administrator?($_POST['mPath']=='44'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>':''));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- main_menu_eof //-->