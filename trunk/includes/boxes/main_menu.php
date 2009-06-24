<?php
/****************************************************************************
 * CODE FILE   : main_menu.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 24 june 2009
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
  $info_box_contents[] = array('text' => tep_draw_form('menu_100', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('mPath'=>'100'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_HOME, 'submitLinkInfoBoxContents', ($_POST['mPath']=='100'||$_POST['mPath']==''?'style="font-weight:bold;cursor:default" disabled':'')) . '</form><br>' .
                                         '<br>' .
                                         tep_draw_form('menu_200', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('mPath'=>'200'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_TIMEREGISTRATION, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_user?($_POST['mPath']=='200'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         (substr($_POST['mPath'], 0, 1) == '2'?'' . 
                                           tep_draw_form('menu_210', tep_href_link(FILENAME_TIMEREGISTRATION_CALENDAR)) . tep_create_parameters(array('mPath'=>'210'), array('period'), 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_TIMEREGISTRATION_CALENDAR, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_user?($_POST['mPath']=='210'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>':'') .
                                         tep_draw_form('menu_300', tep_href_link(FILENAME_ANALYSIS)) . tep_create_parameters(array('mPath'=>'300'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_ANALYSIS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_analyst?($_POST['mPath']=='300'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                         tep_draw_form('menu_400', tep_href_link(FILENAME_ADMINISTRATION)) . tep_create_parameters(array('mPath'=>'400'), array('period'), 'hidden_field') . tep_href_submit(BOX_MAINMENU_ADMINISTRATION, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='400'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>' .
                                         (substr($_POST['mPath'], 0, 1) == '4'?'<br>' .
                                           tep_draw_form('menu_410', tep_href_link(FILENAME_ADMINISTRATION)) . tep_create_parameters(array('mPath'=>'410'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_GENERAL, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='410'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           (substr($_POST['mPath'], 0, 2) == '41'?'' .
                                             tep_draw_form('menu_411', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . tep_create_parameters(array('mPath'=>'411'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_EMPLOYEES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='411'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_412', tep_href_link(FILENAME_ADMINISTRATION_TIMESHEETS)) . tep_create_parameters(array('mPath'=>'412'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_TIMESHEETS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='412'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>':'') .
                                           tep_draw_form('menu_420', tep_href_link(FILENAME_ADMINISTRATION)) . tep_create_parameters(array('mPath'=>'420'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_SYSTEM, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='420'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                           (substr($_POST['mPath'], 0, 2) == '42'?'' .
                                             tep_draw_form('menu_421', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('mPath'=>'421'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_CUSTOMERS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='421'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_422', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('mPath'=>'422'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_BUSINESS_UNITS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='422'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_423', tep_href_link(FILENAME_ADMINISTRATION_CATEGORIES)) . tep_create_parameters(array('mPath'=>'423'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_CATEGORIES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='423'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_424', tep_href_link(FILENAME_ADMINISTRATION_UNITS)) . tep_create_parameters(array('mPath'=>'424'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_UNITS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='424'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>':'') .
                                           tep_draw_form('menu_430', tep_href_link(FILENAME_ADMINISTRATION)) . tep_create_parameters(array('mPath'=>'430'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_PROJECTS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='430'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>' .
                                           (substr($_POST['mPath'], 0, 2) == '43'?'<br>' .
                                             tep_draw_form('menu_431', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('mPath'=>'431'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_PROJECTS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='431'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_432', tep_href_link(FILENAME_ADMINISTRATION_ROLES)) . tep_create_parameters(array('mPath'=>'432'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_ROLES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='432'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_433', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('mPath'=>'433'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_EMPLOYEES_ROLES, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='433'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form><br>' .
                                             tep_draw_form('menu_434', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('mPath'=>'434'), null, 'hidden_field') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_href_submit(BOX_MAINMENU_ADMINISTRATION_TARIFFS, 'submitLinkInfoBoxContents', ($_SESSION['employee']->is_administrator?($_POST['mPath']=='434'?'style="font-weight:bold;cursor:default" disabled':''):'style="cursor:default" disabled')) . '</form>':''):''));
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- main_menu_eof //-->