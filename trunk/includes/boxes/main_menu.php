<?php
/****************************************************************************
 * CODE FILE   : main_menu.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 june 2009
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
  $info_box_contents[] = array('text' => tep_draw_form('menu_11', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('mPath'=>'11'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_HOME, 'submitLinkInfoBoxContents', ($_POST['mPath']=='11'||$_POST['mPath']==''?'style="font-weight:bold;cursor:default" disabled':'')) . '</form><br>' .
                                         '<br>' .
                                         tep_draw_form('menu_21', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('mPath'=>'21'), array('period', 'selected_date', 'action'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_TIMEREGISTRATION, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_user?($_POST['mPath']=='21'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         ($_POST['mPath']=='21'||$_POST['mPath']=='22'?'' . 
                                           tep_draw_form('menu_22', tep_href_link(FILENAME_TIMEREGISTRATION_CALENDAR)) . tep_create_parameters(array('mPath'=>'22'), array('period'), 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_TIMEREGISTRATION_CALENDAR, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_user?($_POST['mPath']=='22'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>':'') .
                                         tep_draw_form('menu_31', tep_href_link(FILENAME_ANALYSIS)) . tep_create_parameters(array('mPath'=>'31'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_ANALYSIS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_analyst?($_POST['mPath']=='31'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         tep_draw_form('menu_41', tep_href_link(FILENAME_ADMINISTRATION)) . tep_create_parameters(array('mPath'=>'41'), array('period', 'selected_date', 'action'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_ADMINISTRATION, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='41'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>' .
                                         ($_POST['mPath']=='41'||$_POST['mPath']=='42'||$_POST['mPath']=='43'||$_POST['mPath']=='44'||$_POST['mPath']=='45'||$_POST['mPath']=='46'||$_POST['mPath']=='47'||$_POST['mPath']=='48'||$_POST['mPath']=='49'||$_POST['mPath']=='410'||$_POST['mPath']=='411'?'<br>' .
                                           tep_draw_form('menu_42', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('mPath'=>'42'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_CUSTOMERS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='42'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_43', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('mPath'=>'43'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_BUSINESS_UNITS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='43'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_44', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('mPath'=>'44'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_PROJECTS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='44'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_45', tep_href_link(FILENAME_ADMINISTRATION_CATEGORIES)) . tep_create_parameters(array('mPath'=>'45'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_CATEGORIES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='45'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_46', tep_href_link(FILENAME_ADMINISTRATION_ROLES)) . tep_create_parameters(array('mPath'=>'46'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_ROLES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='46'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_47', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . tep_create_parameters(array('mPath'=>'47'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_EMPLOYEES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='47'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_48', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('mPath'=>'48'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_EMPLOYEES_ROLES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='48'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_49', tep_href_link(FILENAME_ADMINISTRATION_UNITS)) . tep_create_parameters(array('mPath'=>'49'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_UNITS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='49'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_410', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('mPath'=>'410'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_TARIFFS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='410'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           tep_draw_form('menu_411', tep_href_link(FILENAME_ADMINISTRATION_TIMESHEETS)) . tep_create_parameters(array('mPath'=>'411'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_TIMESHEETS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='411'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>':''));

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- main_menu_eof //-->