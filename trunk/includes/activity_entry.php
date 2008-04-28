<?php
/****************************************************************************
 * CODE FILE   : activity_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 april 2008
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
              <?php echo ($_GET['selected_date']!=''?TEXT_ACTIVITY_ENTRY_SELECTED_DATE . 'xxx':TEXT_ACTIVITY_ENTRY_NO_DATE_SELECTED); ?>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <form name="projects" action="http://localhost/bitts/timeregistration.php" method="get"><select name="projects_id" onChange="this.form.submit();" size="1" style="width: 100%"<?php echo ($_GET['action']=='select_project'||$_GET['action']=='select_role'||$_GET['action']=='enter_data'?'>':' disabled>'); ?><option value="" SELECTED><?php echo TEXT_ACTIVITY_ENTRY_SELECT; ?></option><option value="1">Project 1</option><option value="2">Project 2</option></select></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <form name="projects" action="http://localhost/bitts/timeregistration.php" method="get"><select name="roles_id" onChange="this.form.submit();" size="1" style="width: 100%"<?php echo ($_GET['action']=='select_role'||$_GET['action']=='enter_data'?'>':' disabled>'); ?><option value="" SELECTED><?php echo TEXT_ACTIVITY_ENTRY_SELECT; ?></option><option value="1">Role 1</option><option value="2">Role 2</option></select></form>
            </td>
          </tr>
          <tr>
            <td class="activity_entry">
              <input type="text" name="amount" size="1" maxlength="5" style="width: 20%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?><select name="units_id" size="1" style="width: 80%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?><option value="" SELECTED><?php echo TEXT_ACTIVITY_ENTRY_SELECT; ?></option><option value="1">Unit 1</option><option value="2">Unit 2</option></select>
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
              <input type="text" name="comment" size="1" maxlength="50" style="width: 100%"<?php echo ($_GET['action']=='enter_data'?'>':' disabled>'); ?>
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