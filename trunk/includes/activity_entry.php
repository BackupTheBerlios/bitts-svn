<?php
/****************************************************************************
 * CODE FILE   : activity_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 20 mei 2008
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
            <td class="activity_entry">
              <?php echo ($_GET['selected_date']!=''?TEXT_ACTIVITY_ENTRY_SELECTED_DATE . tep_strftime(DATE_FORMAT_SHORT, $_GET['selected_date']):TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_TIMEREGISTRATION), 'get') . tep_create_parameters(array('action'=>'select_role'), array('mPath','period','selected_date'), 'hidden_field') . tep_html_select('projects_id', tep_get_partial_array(project::get_selectable_projects(7, '2008-05-20'), 'projects_id', 'projects_name'), $_GET['action']=='select_project'||$_GET['action']=='select_role'||$_GET['action']=='enter_data', $_GET['projects_id']); ?></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo tep_draw_form('role_selection', tep_href_link(FILENAME_TIMEREGISTRATION), 'get') . tep_create_parameters(array('action'=>'enter_data'), array('mPath','period','selected_date', 'projects_id'), 'hidden_field') . tep_html_select('roles_id', array('1'=>'Role 1','2'=>'Role 2','3'=>'Role 3'), $_GET['action']=='select_role'||$_GET['action']=='enter_data', $_GET['roles_id']); ?></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <input type="text" name="amount" size="1" maxlength="5" style="width: 20%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>') . tep_html_select('units_id', array('1'=>'Unit 1','2'=>'Unit 2','3'=>'Unit 3'), $_GET['action']=='enter_data', $_GET['units_id'], 'onChange="this.form.submit();" size="1" style="width: 80%"'); ?></select>
            </td>
          </tr>
          <tr>
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
              <?php echo TEXT_ACTIVITY_COMMENT; ?><br>
              <input type="text" name="comment" size="1" maxlength="50" style="width: 100%" value="<?php echo $_GET['project_selection'] . '"' . ($_GET['action']=='enter_data'?'>':' disabled>'); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="right" class="activity_entry">
              <?php echo tep_image_button('button_save.gif', TEXT_ACTIVITY_ENTRY_SAVE) . '&nbsp;' . tep_image_button('button_cancel.gif', TEXT_ACTIVITY_ENTRY_CANCEL); ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- activity_entry_eof //-->