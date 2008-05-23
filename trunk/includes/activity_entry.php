<?php
/****************************************************************************
 * CODE FILE   : activity_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 may 2008
 * Description : Activity entry fields
 *
 */
?>
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
          <tr>
            <td align="center" class="activity_entry" colspan="2">
              <?php echo ($_GET['selected_date']!=''?TEXT_ACTIVITY_ENTRY_SELECTED_DATE . tep_strftime(DATE_FORMAT_SHORT, $_GET['selected_date']):TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_PROJECTNAME; ?>
            </td>
            <td class="activity_entry">
              <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_TIMEREGISTRATION), 'get') . tep_create_parameters(array('action'=>'select_role'), array('mPath','period','selected_date'), 'hidden_field');
              if ($_GET['action']=='select_project'||$_GET['action']=='select_role'||$_GET['action']=='enter_data') {
                echo tep_html_select('projects_id', tep_get_partial_array(project::get_selectable_projects($_SESSION['employee']->employee_id, tep_strftime('%Y-%m-%d', $_GET['selected_date'])), 'projects_id', 'projects_name'), TRUE, $_GET['projects_id']);
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
              <?php echo tep_draw_form('role_selection', tep_href_link(FILENAME_TIMEREGISTRATION), 'get') . tep_create_parameters(array('action'=>'enter_data'), array('mPath','period','selected_date', 'projects_id'), 'hidden_field');
              if ($_GET['action']=='select_role'||$_GET['action']=='enter_data') {
                echo tep_html_select('roles_id', tep_get_partial_array(role::get_selectable_roles($_SESSION['employee']->employee_id, tep_strftime('%Y-%m-%d', $_GET['selected_date']),$_GET['projects_id']), 'roles_id', 'roles_name'), TRUE, $_GET['roles_id']);
              } else {
                echo tep_html_select('roles_id', array(), FALSE);
              }
              ?></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_AMOUNT . ' &amp; ' . TEXT_ACTIVITY_UNIT; ?>
            </td>
            <td class="activity_entry">
              <input type="text" name="amount" size="1" maxlength="5" style="width: 20%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>');
              if ($_GET['action']=='enter_data') {
                echo tep_html_select('units_id', tep_get_partial_array(tariff::get_selectable_tariffs($_SESSION['employee']->employee_id, $_GET['roles_id']), 'tariffs_id', 'units_name'), TRUE, $_GET['tariffs_id'], 'onChange="this.form.submit();" size="1" style="width: 80%"');
              } else {
                echo tep_html_select('units_id', array(), FALSE, 0, 'onChange="this.form.submit();" size="1" style="width: 80%"');
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
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_TRAVELDISTANCE; ?></td><td width="50%" class="activity_entry"><input type="text" name="travel_distance" size="1" maxlength="5" style="width: 100%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_EXPENSES; ?></td><td width="50%" class="activity_entry"><input type="text" name="expenses" size="1" maxlength="5" style="width: 100%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?></td>
                </tr>
                <tr>
                  <td width="50%" class="activity_entry"><?php echo TEXT_ACTIVITY_TICKETNUMBER; ?></td><td width="50%" class="activity_entry"><input type="text" name="ticket_number" size="1" maxlength="5" style="width: 100%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo TEXT_ACTIVITY_COMMENT; ?>
            </td>
            <td class="activity_entry">
              <input type="text" name="comment" size="1" maxlength="50" style="width: 100%" value="<?php echo $_GET['project_selection'] . '"' . ($_GET['action']=='enter_data'?'>':' disabled>'); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry" colspan="2">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="right" class="activity_entry" colspan="2">
              <?php echo tep_image_button('button_save.gif', TEXT_ACTIVITY_ENTRY_SAVE) . '&nbsp;' . tep_image_button('button_cancel.gif', TEXT_ACTIVITY_ENTRY_CANCEL); ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- activity_entry_eof //-->