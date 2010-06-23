<?php
/****************************************************************************
 * CODE FILE   : tariff_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 04 august 2009
 * Description : Tariff entry fields
 */
?>
  <!-- tariff_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $TARIFF_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data') {
            echo tep_draw_form('tariff_entry', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'projects_id', 'employees_roles_id', 'tariffs_id', 'units_id'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_TARIFFS_AMOUNT; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php echo tep_draw_input_field('tariffs_amount_display', '', 'size="1" maxlength="8" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_UNITS; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php if ($_POST['action']=='enter_data' || $_POST['action']=='delete_entry') {
                $temp_unit = new unit();
                echo tep_html_select('units_id', tep_get_partial_array($temp_unit->listing, 'id', 'name'), $_POST['action']=='enter_data' && !tep_not_null($_POST['tariffs_id']), $_POST['units_id'], 'size="1" maxlength="20" style="width: 100%"');
              } else {
                echo tep_html_select('units_id', array(), false);
              } ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_TARIFFS_START_DATE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('tariffs_start_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_TARIFFS_END_DATE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('tariffs_end_date_display', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if (tep_not_null($_POST['employees_roles_id']) && $_POST['action'] != 'enter_data' && !($_POST['action'] == 'delete_entry' && $error_level == 0)) {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('action'=>'enter_data'), array('mPath', 'projects_id', 'employees_roles_id'), 'hidden_field');
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
              } else if ($_POST['action']=='delete_entry' && $error_level==0) {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'projects_id', 'employees_roles_id', 'tariffs_id'), 'hidden_field');
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action'] == 'enter_data' || ($_POST['action'] == 'delete_entry' && $error_level == 0)) {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array(), array('mPath', 'projects_id', 'employees_roles_id'), 'hidden_field');
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
  <!-- tariff_entry_eof //-->