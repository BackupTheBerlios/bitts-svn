<?php
/****************************************************************************
 * CODE FILE   : customer_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 november 2013
 * Description : Customer entry fields
 */
?>
  <!-- customer_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="5">
                <?php echo $CUSTOMER_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data') {
            echo tep_draw_form('customer_entry', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'customers_id', 'customers_status'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_ID; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('customers_id', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'&&$_POST['customers_status']=='new'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_NAME1; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('customers_billing_name1', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_ID_EXTERNAL; ?>
            </td>
            <td class="item_entry" width="150">
              <?php echo tep_draw_input_field('customers_id_external', '', 'size="1" maxlength="10" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_NAME2; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_name2', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_NAME; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_name', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_ADDRESS; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_address', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_POSTCODE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_postcode', '', 'size="1" maxlength="8" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_CITY; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_city', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_COUNTRY; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_country', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_EMAIL_ADDRESS; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_email_address', '', 'size="1" maxlength="64" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_PHONE; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_phone', '', 'size="1" maxlength="32" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_FAX; ?>
            </td>
            <td class="item_entry">
              <?php echo tep_draw_input_field('customers_billing_fax', '', 'size="1" maxlength="32" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry">
              <?php echo TEXT_CUSTOMERS_BILLING_SHOW_LOGO; ?>
            </td>
            <td class="item_entry" style="text-align:left">
              <?php echo tep_draw_checkbox_field('customers_billing_show_logo', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td align="left" class="item_entry">
              <?php if ($_POST['action']!='enter_data'&&$_POST['action']!='delete_entry') {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('action'=>'enter_data', 'customers_status'=>'new'), array('mPath'), 'hidden_field');
                echo tep_image_submit('button_new.gif', TEXT_ENTRY_NEW, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_new_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              } ?>
            </td>
            <td align="right" class="item_entry" colspan="4">
              <?php if ($_POST['action']=='enter_data') {
                echo tep_image_submit('button_save.gif', TEXT_ENTRY_SAVE, 'style="vertical-align:middle"');
                echo '</form>';
              } else if ($_POST['action']=='delete_entry') {
                echo TEXT_ENTRY_DELETE_QUESTION . '&nbsp;';
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'customers_id'), 'hidden_field');
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action']=='enter_data'||$_POST['action']=='delete_entry') {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
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
  <!-- customer_entry_eof //-->