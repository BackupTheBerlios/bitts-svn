<?php
/****************************************************************************
 * CODE FILE   : project_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 aug 2011
 * Description : Project entry fields
 */
?>
  <!-- project_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $PROJECT_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data' || $_POST['action']=='save_data') {
            echo tep_draw_form('project_entry', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'projects_id', 'projects_name', 'projects_description', 'projects_customers_contact_name', 'projects_customers_reference', 'projects_start_date', 'projects_start_date_display', 'projects_end_date', 'projects_end_date_display', 'projects_calculated_hours', 'projects_calculated_hours_period', 'projects_business_units_id', 'projects_customers_id', 'question_er1_answer', 'question_t1_answer', 'question_er2_answer', 'question_t2_answer'), 'hidden_field');
            echo tep_draw_hidden_field('show_history', $_POST['show_history']);
          } ?>
          <tr>
            <td class="item_entry">
            <?php echo TEXT_PROJECTS_NAME; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_name', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_DESCRIPTION; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_description', '', 'size="1" maxlength="255" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_customers_contact_name', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CUSTOMERS_REFERENCE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_customers_reference', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_START_DATE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_start_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_END_DATE; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_end_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CALCULATED_HOURS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('projects_calculated_hours', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_PROJECTS_CALCULATED_HOURS_PERIOD; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_radio_field('projects_calculated_hours_period', 'E', false, ($_POST['action']=='enter_data'?'':' disabled')).'&nbsp;'.$PROJECTS_CALCULATED_HOURS_PERIOD['E']; ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="4">&nbsp;</td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_radio_field('projects_calculated_hours_period', 'B', false, ($_POST['action']=='enter_data'?'':' disabled')).'&nbsp;'.$PROJECTS_CALCULATED_HOURS_PERIOD['B']; ?>
            </td>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_BUSINESS_UNITS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='save_data' || $_POST['action']=='delete_entry') {
                $temp_business_unit = new business_unit();
                echo tep_html_select('projects_business_units_id', tep_get_partial_array($temp_business_unit->listing, 'id', 'name'), $_POST['action']=='enter_data', $_POST['projects_business_units_id'], 'size="1" maxlength="64" style="width: 100%"');
              } else {
                echo tep_html_select('projects_business_units_id', array(), false);
              } ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS; ?>
            </td>
            <td class="item_entry" width="150">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='save_data' || $_POST['action']=='delete_entry') {
                $temp_customer = new customer();
                echo tep_html_select('projects_customers_id', tep_get_partial_array($temp_customer->listing, 'id', 'name'), $_POST['action']=='enter_data', $_POST['projects_customers_id'], 'size="1" maxlength="64" style="width: 100%"');
              } else {
                echo tep_html_select('projects_customers_id', array(), false);
              } ?>
            </td>
          </tr>
          <?php if (isset($_POST['question_er1']) || isset($_POST['question_t1']) || isset($_POST['question_er2']) || isset($_POST['question_t2'])) { ?>
            <tr>
              <td class="item_entry" colspan="5">
                <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
              </td>
            </tr>
            <?php if (isset($_POST['question_er1'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_ER1 . tep_draw_checkbox_field('question_er1_answer', true, false, ($_POST['question_er1']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_er1', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_t1'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_T1 . tep_draw_checkbox_field('question_t1_answer', true, false, ($_POST['question_t1']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_t1', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_er2'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_ER2 . tep_draw_checkbox_field('question_er2_answer', true, false, ($_POST['question_er2']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_er2', 'ASKED'); ?>
                </td>
              </tr>
            <?php }
            if (isset($_POST['question_t2'])) { ?>
              <tr>
                <td class="item_entry" colspan="5" style="text-align:right">
                  <?php echo TEXT_PROJECTS_QUESTION_T2 . tep_draw_checkbox_field('question_t2_answer', true, false, ($_POST['question_t2']=='ASK'?'':' disabled')) . tep_draw_hidden_field('question_t2', 'ASKED'); ?>
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
            <td align="left" class="item_entry">
              <?php if ($_POST['action']!='enter_data'&&$_POST['action']!='save_data'&&$_POST['action']!='delete_entry') {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'enter_data', 'projects_calculated_hours_period'=>'E'), array('mPath'), 'hidden_field');
                echo tep_draw_hidden_field('show_history', $_POST['show_history']);
                echo tep_image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td align="right" class="item_entry" colspan="4">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='save_data') {
                echo tep_image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if ($_POST['action']=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'projects_id'), 'hidden_field');
                echo tep_draw_hidden_field('show_history', $_POST['show_history']);
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action']=='enter_data' || $_POST['action']=='save_data' || $_POST['action']=='delete_entry') {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
                echo tep_draw_hidden_field('show_history', $_POST['show_history']);
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
  <!-- project_entry_eof //-->