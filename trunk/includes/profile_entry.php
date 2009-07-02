<?php
/****************************************************************************
 * CODE FILE   : profile_entry.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 02 july 2009
 * Description : Profile entry fields
 */
?>
  <!-- profile_entry //-->
  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
          <?php if ($error_level > 0) { ?>
            <tr>
              <td class="entry_error_<?php echo ($error_level<64?($error_level<32?'high':'middle'):'low'); ?>" colspan="7">
                <?php echo $PROFILE_ERROR_LEVEL[$error_level]; ?>
              </td>
            </tr>
          <?php }
          if ($_POST['action']=='enter_data') {
            echo tep_draw_form('profile_entry', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array('action'=>'save_data'), array('mPath', 'profiles_id'), 'hidden_field');
          } ?>
          <tr>
            <td class="item_entry">
              <?php echo TEXT_PROFILES_NAME; ?>
            </td>
            <td class="item_entry" style="width:150px">
              <?php echo tep_draw_input_field('profiles_name', '', 'size="1" maxlength="32" style="width: 100%"' . ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
            <td class="item_entry">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_PROFILES_RIGHTS_LOGIN; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo tep_draw_checkbox_field('profiles_rights_login', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_PROFILES_RIGHTS_PROJECTLISTING; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo tep_draw_checkbox_field('profiles_rights_projectlisting', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_PROFILES_RIGHTS_TIMEREGISTRATION; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo tep_draw_checkbox_field('profiles_rights_timeregistration', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_PROFILES_RIGHTS_ANALYSIS; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo tep_draw_checkbox_field('profiles_rights_analysis', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="3">&nbsp;</td>
            <td class="item_entry" style="width:65px">
              <?php echo TEXT_PROFILES_RIGHTS_ADMINISTRATION; ?>
            </td>
            <td class="item_entry" style="text-align:right;width:25px">
              <?php echo tep_draw_checkbox_field('profiles_rights_administration', true, false, ($_POST['action']=='enter_data'?'':' disabled')); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" colspan="5">
              <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
            </td>
          </tr>
          <tr>
            <td class="item_entry" style="text-align:left">
              <?php if ($_POST['action']!='enter_data'&&$_POST['action']!='delete_entry') {
                echo tep_draw_form('fnew', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array('action'=>'enter_data'), array('mPath'), 'hidden_field');
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
                echo tep_draw_form('delete_entry_confirm', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array('action'=>'delete_entry_confirmed'), array('mPath', 'profiles_id'), 'hidden_field');
                echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"');
                echo '</form>';
              } else {
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_save_disabled.gif', null, null, null, 'style="vertical-align:middle"');
              }
              echo '&nbsp;';
              if ($_POST['action']=='enter_data'||$_POST['action']=='delete_entry') {
                echo tep_draw_form('fcancel', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
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
  <!-- profile_entry_eof //-->