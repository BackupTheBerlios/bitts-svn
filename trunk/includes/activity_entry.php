<?php
/****************************************************************************
 * CODE FILE   : activity_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 september 2008
 * Description : Activity entry fields
 *               Data validation sequence
 *               Storing of entered data (via timesheet object)
 */

// When action==save_data: verify entered data and save if OK / set errorlevel when NOK
$error_level = 0;
if (tep_post_or_get('action') == '') {
  $_POST['activity_id'] = 0;
} else if (tep_post_or_get('action') == 'save_data') {
  // Check for data format and required fields
  // change action when not everything is filled-in
  if (tep_post_or_get('selected_date') == '') {
    $_POST['action'] = '';
  } else if (tep_post_or_get('projects_id') == '') {
    $_POST['action'] = 'select_project';
    $error_level = 1;
  } else if (tep_post_or_get('roles_id') == '') {
    $_POST['action'] = 'select_role';
    $error_level = 2;
  } else if (!activity::validate('amount', tep_post_or_get('activity_amount'))) {
    $error_level = 3;
  } else if (tep_post_or_get('tariffs_id') == '') {
    $error_level = 4;
  } else if (!activity::validate('travel_distance', tep_post_or_get('activity_travel_distance'))) {
    $error_level = 5;
  } else if (!activity::validate('expenses', tep_post_or_get('activity_expenses'))) {
    $error_level = 6;
  } else if (activity::ticket_entry_is_required(tep_post_or_get('tariffs_id')) && !tep_not_null(tep_post_or_get('activity_ticket_number'))) { // no ticket number when required
    $error_level = 7;
  } else {
    // OK, entry can be saved
    $_SESSION['timesheet']->save_activity(tep_post_or_get('activity_id'),
                                          tep_strftime(DATE_FORMAT_DATABASE, tep_post_or_get('selected_date')),
                                          tep_post_or_get('activity_amount'),
                                          tep_post_or_get('tariffs_id'),
                                          tep_post_or_get('activity_travel_distance'),
                                          tep_post_or_get('activity_expenses'),
                                          tep_post_or_get('activity_ticket_number'),
                                          tep_post_or_get('activity_comment'));

    // Clear all values except mPath and period
    foreach($_POST as $key=>$value) {
      if ($key != 'mPath' && $key != 'period') {
        $_POST[$key] = '';
      }
    }

    // Reload the timesheet object in order to
    // update the activity listing below
    $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->employee_id, tep_post_or_get('period'));
  }
} ?>
  <!-- activity_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td valign="top">
        <?php require(DIR_WS_INCLUDES . 'calendar.php'); ?>
      </td>
      <td>
        <?php echo tep_draw_separator('pixel_trans.gif', '10'); ?>
      </td>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" width="250" class="activity_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="activity_error" colspan="2">
                <?php echo $ACTIVITY_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php } ?>
          <tr>
            <td align="center" class="activity_entry" colspan="2">
              <?php if (tep_post_or_get('selected_date')!='') {
                echo TEXT_ACTIVITY_ENTRY_SELECTED_DATE . tep_strftime(DATE_FORMAT_SHORT, tep_post_or_get('selected_date'));
              } else {
                echo TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED;
              } ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_PROJECTNAME; ?>
            </td>
            <td class="activity_entry">
              <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'select_role'), array('mPath','period','selected_date', 'activity_id'), 'hidden_field');
              if (tep_post_or_get('action')=='select_project'||tep_post_or_get('action')=='select_role'||tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data') {
                echo tep_html_select('projects_id', tep_get_partial_array(project::get_selectable_projects($_SESSION['employee']->employee_id, tep_strftime('%Y-%m-%d', tep_post_or_get('selected_date'))), 'projects_id', 'projects_name'), TRUE, tep_post_or_get('projects_id'));
              } else {
                echo tep_html_select('projects_id', array(), FALSE);
              }
              ?></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_ROLENAME; ?>
            </td>
            <td class="activity_entry">
              <?php echo tep_draw_form('role_selection', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'enter_data'), array('mPath','period','selected_date', 'projects_id', 'activity_id'), 'hidden_field');
              if (tep_post_or_get('action')=='select_role'||tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data') {
                echo tep_html_select('roles_id', tep_get_partial_array(role::get_selectable_roles($_SESSION['employee']->employee_id, tep_strftime('%Y-%m-%d', tep_post_or_get('selected_date')),tep_post_or_get('projects_id')), 'roles_id', 'roles_name'), TRUE, tep_post_or_get('roles_id'));
              } else {
                echo tep_html_select('roles_id', array(), FALSE);
              }
              ?></form>
            </td>
          </tr>
          <?php if (tep_post_or_get('action') == 'enter_data' || tep_post_or_get('action') == 'save_data') {
            echo tep_draw_form('activity_entry', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'save_data'), array('mPath','period','selected_date','projects_id','roles_id', 'activity_id'), 'hidden_field');
          } ?>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_AMOUNT . ' &amp; ' . TEXT_ACTIVITY_UNIT; ?>
            </td>
            <td class="activity_entry">
              <?php echo tep_draw_input_field('activity_amount', '', 'size="1" maxlength="6" style="width: 20%"' . (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data'?'':' disabled'));
              if (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data') {
                echo tep_html_select('tariffs_id', tep_get_partial_array(tariff::get_selectable_tariffs($_SESSION['employee']->employee_id, tep_post_or_get('roles_id')), 'tariffs_id', 'units_name'), TRUE, tep_post_or_get('tariffs_id'), 'size="1" style="width: 80%"');
              } else {
                echo tep_html_select('tariffs_id', array(), FALSE, 0, 'size="1" style="width: 80%"');
              }
              ?></select>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              &nbsp;
            </td>
            <td class="activity_entry">
              <table border="0" cellspacing="0" cellpadding="2" width="250" class="activity_entry">
                <tr>
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_TRAVELDISTANCE; ?></td><td width="50%" class="activity_entry"><?php echo tep_draw_input_field('activity_travel_distance', '', 'size="1" maxlength="5" style="width: 100%"' . (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data'?'':' disabled')); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_EXPENSES; ?></td><td width="50%" class="activity_entry"><?php echo tep_draw_input_field('activity_expenses', '', 'size="1" maxlength="7" style="width: 100%"' . (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data'?'':' disabled')); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_TICKETNUMBER; ?></td><td width="50%" class="activity_entry"><?php echo tep_draw_input_field('activity_ticket_number', '', 'size="1" maxlength="16" style="width: 100%"' . (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data'?'':' disabled')); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_COMMENT; ?>
            </td>
            <td class="activity_entry">
              <?php echo tep_draw_input_field('activity_comment', '', 'size="1" maxlength="64" style="width: 100%"' . (tep_post_or_get('action')=='enter_data'||tep_post_or_get('action')=='save_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry" colspan="2">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="right" class="activity_entry" colspan="2">
              <?php if (tep_post_or_get('action') == 'enter_data' || tep_post_or_get('action') == 'save_data') {
                echo tep_image_submit('button_save.gif', TEXT_ACTIVITY_ENTRY_SAVE);
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif');
              }
              echo '&nbsp;';
              if (tep_not_null(tep_post_or_get('action'))) {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array(), array('mPath','period'), 'hidden_field');
                echo tep_image_submit('button_cancel.gif', TEXT_ACTIVITY_ENTRY_CANCEL);
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_cancel_disabled.gif');
              } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- activity_entry_eof //-->