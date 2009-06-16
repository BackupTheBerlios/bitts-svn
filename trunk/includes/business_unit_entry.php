<?php
/****************************************************************************
 * CODE FILE   : business_unit_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 june 2009
 * Description : Business Unit entry fields
 */
?>
  <!-- business_units_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="7">
                <?php echo $BUSINESS_UNIT_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data') {
            echo tep_draw_form('business_unit_entry', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'business_units_id'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_BUSINESS_UNITS_NAME; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php echo tep_draw_input_field('business_units_name', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_BUSINESS_UNITS_IMAGE; ?>
            </td>
            <td class="item_entry" colspan="3" style="width:150px">
              <?php echo tep_draw_input_field('business_units_image', '', 'size="1" maxlength="32" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_BUSINESS_UNITS_IMAGE_POSITION; ?>
            </td>
            <td class="item_entry" style="text-align:left">
              <?php echo tep_draw_radio_field('business_units_image_position', 'L', false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry" style="text-align:center">
              <?php echo tep_draw_radio_field('business_units_image_position', 'C', false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry" style="text-align:right">
              <?php echo tep_draw_radio_field('business_units_image_position', 'R', false, ($_POST['action']=='enter_data'?'':' disabled')); ?>&nbsp;
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="4">&nbsp;</td>
            <td class="item_entry" style="text-align:left"><?php echo $BUSINESS_UNITS_IMAGE_POSITION['L']; ?></td>
            <td class="item_entry" style="text-align:center"><?php echo $BUSINESS_UNITS_IMAGE_POSITION['C']; ?></td>
            <td class="item_entry" style="text-align:right"><?php echo $BUSINESS_UNITS_IMAGE_POSITION['R']; ?></td>
          </tr>
          <tr>
            <td class="item_entry" colspan="7">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if ($_POST['action']!='enter_data'&&$_POST['action']!='delete_entry') {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('action'=>'enter_data', 'business_units_image_position'=>'L'), array('mPath'), 'hidden_field');
                echo tep_image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td class="item_entry" colspan="6" style="text-align:right">
              <?php if ($_POST['action']=='enter_data') {
                echo tep_image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if ($_POST['action']=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'business_units_id'), 'hidden_field');
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action']=='enter_data'||$_POST['action']=='delete_entry') {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
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
  <!-- business_units_entry_eof //-->