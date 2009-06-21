<?php
/****************************************************************************
 * CODE FILE   : employee_role_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 20 june 2009
 * Description : Employee-Role entry fields
 */
?>
  <!-- employee-role_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $EMPLOYEE_ROLE_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data') {
            echo tep_draw_form('employee_role_entry', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'employees_roles_id', 'employees_roles_start_date', 'employees_roles_start_date_display', 'employees_roles_end_date', 'employees_roles_end_date_display', 'roles_id', 'employees_id', 'projects_id', 'question_t1_answer', 'question_t2_answer'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_ROLES; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='delete_entry') {
                $temp_role = new role(0, $_POST['projects_id']);
                echo tep_html_select('roles_id', tep_get_partial_array($temp_role->listing, 'id', 'name'), $_POST['action']=='enter_data' && !tep_not_null($_POST['employees_roles_id']), $_POST['roles_id'], 'size="1" maxlength="20" style="width: 100%"');
              } else {
                echo tep_html_select('roles_id', array(), false);
              } ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='delete_entry') {
                $temp_employee = new employee();
                echo tep_html_select('employees_id', tep_get_partial_array($temp_employee->listing, 'id', 'fullname'), $_POST['action']=='enter_data' && !tep_not_null($_POST['employees_roles_id']), $_POST['employees_id'], 'size="1" maxlength="20" style="width: 100%"');
              } else {
                echo tep_html_select('employees_id', array(), false);
              } ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_ROLES_START_DATE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('employees_roles_start_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_EMPLOYEES_ROLES_END_DATE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('employees_roles_end_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <?php if (isset($_POST['question_t1']) || isset($_POST['question_t2'])) { ?>
            <tr>
              <td class="item_entry" colspan="5">
                <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
              </td>
            </tr>
            <?php if (isset($_POST['question_t1'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_EMPLOYEES_ROLES_QUESTION_T1 . tep_draw_checkbox_field('question_t1_answer', true, false, ($_POST['question_t1']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_t1', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_t2'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_EMPLOYEES_ROLES_QUESTION_T2 . tep_draw_checkbox_field('question_t2_answer', true, false, ($_POST['question_t2']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_t2', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
          } ?>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if (tep_not_null($_POST['projects_id']) && $_POST['action'] != 'enter_data' && $_POST['action'] != 'delete_entry') {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('action'=>'enter_data'), array('mPath', 'projects_id'), 'hidden_field');
                echo tep_image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td class="item_entry" colspan="4" style="text-align:right">
              <?php if ($_POST['action']=='enter_data') {
                echo tep_image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if ($_POST['action']=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'employees_roles_id', 'projects_id'), 'hidden_field');
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action'] == 'enter_data' || $_POST['action'] == 'delete_entry') {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array(), array('mPath', 'projects_id'), 'hidden_field');
                echo tep_image_submit('button_cancel.gif', TEXT_ENTRY_CANCEL, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_cancel_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!-- employee-role_entry_eof //-->