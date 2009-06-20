<?php
/****************************************************************************
 * CODE FILE   : administration_employees.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 20 june 2009
 * Description : Employee administration form
 *               Data validation sequence
 *               Storing of entered data (via employee object)
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // application_top //
  require('includes/application_top.php');
  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));
  // Check if the user is allowed to view this page
  if (!$_SESSION['employee']->is_administrator)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Create a new employee object with id == 0 (default)
  $_SESSION['administration_employee'] = new employee();

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'enter_data':
      //if ($_POST['employees_status'] == 'new') {
      //  $_POST['employees_id'] = $_SESSION['administration_employee']->get_next_id();
      //}
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['employees_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No employees_id
      } else if ($_POST['employees_login'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No employees_login
      } else if ($_POST['employees_fullname'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 3; // No employees_fullname
      } else if (!$_SESSION['administration_employee']->validate_id($_POST['employees_id'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 4; // Invalid employees_id
      } else if ($_POST['employees_status'] == 'new' && $_SESSION['administration_employee']->id_exists($_POST['employees_id'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 5; // Duplicate employees_id
      } else {
        // OK, entry can be saved
        // If password has to be resetted, do so
        if ($_POST['employees_reset_password']) {
          $_SESSION['administration_employee']->reset_password($_POST['employees_id']);
        }
        // Then save the entry
        $_SESSION['administration_employee']->save($_POST['employees_id'],
                                                   $_POST['employees_login'],
                                                   $_POST['employees_fullname'],
                                                   $_POST['employees_is_user'],
                                                   $_POST['employees_is_analyst'],
                                                   $_POST['employees_is_administrator']);

        // Clear all values except mPath
        foreach($_POST as $key=>$value) {
          if ($key != 'mPath') {
            unset($_POST[$key]);
          }
        }
      }
      break;
    case 'delete_entry':
      // Check for dependencies
      if ($_SESSION['administration_employee']->has_dependencies($_POST['employees_id'])) {
        $error_level = 6; // Related employees_role(s) and/or timesheet(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $_SESSION['administration_employee']->delete($_POST['employees_id']);
      $_POST['employees_id'] = null;
      $_POST['action'] = '';
      break;
  }

  // Reload the employee object in order to
  // update the employee listing below
  $_SESSION['administration_employee'] = new employee();

  // header //
  require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- body //-->
  <table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
      <td width="<?php echo BOX_WIDTH; ?>" valign="top">
        <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
          <!-- left_navigation //-->
          <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
          <!-- left_navigation_eof //-->
        </table>
      </td>
      <!-- body_text //-->
      <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_EMPLOYEES; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'user_info-64x64.png', HEADER_TEXT_ADMINISTRATION_EMPLOYEES, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'employee_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading" style="width:100px"><?php echo TEXT_EMPLOYEES_ID; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_EMPLOYEES_LOGIN; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_EMPLOYEES_FULLNAME; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_EMPLOYEES_IS_USER; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_EMPLOYEES_IS_ANALYST; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_EMPLOYEES_IS_ADMINISTRATOR; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['administration_employee']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['administration_employee']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data" style="width:100px"><?php echo $_SESSION['administration_employee']->listing[$index]->id; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['administration_employee']->listing[$index]->login; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['administration_employee']->listing[$index]->fullname; ?></td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['administration_employee']->listing[$index]->is_user) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_EMPLOYEES_IS_USER, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['administration_employee']->listing[$index]->is_analyst) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_EMPLOYEES_IS_ANALYST, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['administration_employee']->listing[$index]->is_administrator) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_EMPLOYEES_IS_ADMINISTRATOR, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . tep_create_parameters(array('action'=>'enter_data', 'employees_status'=>'existing', 'employees_id'=>$_SESSION['administration_employee']->listing[$index]->id, 'employees_login'=>$_SESSION['administration_employee']->listing[$index]->login, 'employees_fullname'=>$_SESSION['administration_employee']->listing[$index]->fullname, 'employees_is_user'=>$_SESSION['administration_employee']->listing[$index]->is_user, 'employees_is_analyst'=>$_SESSION['administration_employee']->listing[$index]->is_analyst, 'employees_is_administrator'=>$_SESSION['administration_employee']->listing[$index]->is_administrator), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES)) . tep_create_parameters(array('action'=>'delete_entry', 'employees_id'=>$_SESSION['administration_employee']->listing[$index]->id, 'employees_login'=>$_SESSION['administration_employee']->listing[$index]->login, 'employees_fullname'=>$_SESSION['administration_employee']->listing[$index]->fullname, 'employees_is_user'=>$_SESSION['administration_employee']->listing[$index]->is_user, 'employees_is_analyst'=>$_SESSION['administration_employee']->listing[$index]->is_analyst, 'employees_is_administrator'=>$_SESSION['administration_employee']->listing[$index]->is_administrator), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="8"  style="text-align:center">
                      <?php echo TEXT_EMPLOYEES_LISTING_IS_EMPTY; ?>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
          </tr>
        </table>
      </td>
      <!-- body_text_eof //-->
    </tr>
  </table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<!-- application_bottom //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!-- application_bottom_eof //-->