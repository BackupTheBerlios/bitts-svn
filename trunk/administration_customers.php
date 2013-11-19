<?php
/****************************************************************************
 * CODE FILE   : administration_customers.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 november 2013
 * Description : Customer administration form
 *               Data validation sequence
 *               Storing of entered data (via customer object)
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
  if (!$_SESSION['employee']->profile->right['administration'])
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Create a new customer object with id == 0 (default)
  $_SESSION['customer'] = new customer();

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'enter_data':
      //if ($_POST['customers_status'] == 'new') {
      //  $_POST['customers_id'] = $_SESSION['customer']->get_next_id();
      //}
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['customers_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No customers_id
      } else if ($_POST['customers_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No customers_name
      } else if ($_POST['customers_status'] == 'new' && !$_SESSION['customer']->validate_id($_POST['customers_id'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 3; // Invalid customers_id
      } else if ($_POST['customers_status'] == 'new' && $_SESSION['customer']->id_exists($_POST['customers_id'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 4; // Duplicate customers_id
      } else {
        // OK, entry can be saved
        $_SESSION['customer']->save($_POST['customers_id'],
                                    $_POST['customers_name'],
                                    $_POST['customers_id_external'],
                                    $_POST['customers_billing_name1'],
                                    $_POST['customers_billing_name2'],
                                    $_POST['customers_billing_address'],
                                    $_POST['customers_billing_postcode'],
                                    $_POST['customers_billing_city'],
                                    $_POST['customers_billing_country'],
                                    $_POST['customers_billing_email_address'],
                                    $_POST['customers_billing_phone'],
                                    $_POST['customers_billing_fax'],
                                    $_POST['customers_billing_show_logo']);

        // Clear all values except mPath
        foreach($_POST as $key=>$value) {
          if ($key != 'mPath') {
            $_POST[$key] = '';
          }
        }
      }
      break;
    case 'delete_entry':
      // Check for dependencies
      if ($_SESSION['customer']->has_dependencies($_POST['customers_id'])) {
        $error_level = 5; // Related project(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $_SESSION['customer']->delete($_POST['customers_id']);
      $_POST['customers_id'] = null;
      $_POST['action'] = '';
      break;
  }

  // Reload the customer object in order to
  // update the customer listing below
  $_SESSION['customer'] = new customer();

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_CUSTOMERS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'address_book-64x64.png', HEADER_TEXT_ADMINISTRATION_CUSTOMERS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'customer_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading" style="width:100px"><?php echo TEXT_CUSTOMERS_ID; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS_NAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS_BILLING_ADDRESS; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS_BILLING_EMAIL_ADDRESS; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS_BILLING_PHONE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS_BILLING_FAX; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['customer']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['customer']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data" style="width:100px"><?php echo $_SESSION['customer']->listing[$index]->id; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['customer']->listing[$index]->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['customer']->listing[$index]->billing_name1.'<br>'.$_SESSION['customer']->listing[$index]->billing_name2.'<br>'.$_SESSION['customer']->listing[$index]->billing_address.'<br>'.$_SESSION['customer']->listing[$index]->billing_postcode.'&nbsp;&nbsp;'.$_SESSION['customer']->listing[$index]->billing_city.'<br>'.$_SESSION['customer']->listing[$index]->billing_country; ?></td>
                      <td class="entryListing-data"><?php echo tep_href_email_address($_SESSION['customer']->listing[$index]->billing_email_address); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['customer']->listing[$index]->billing_phone; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['customer']->listing[$index]->billing_fax; ?></td>
                      <td align="center" width="20" class="entryListing-data">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('action'=>'enter_data', 'customers_status'=>'existing', 'customers_id'=>$_SESSION['customer']->listing[$index]->id, 'customers_name'=>$_SESSION['customer']->listing[$index]->name, 'customers_billing_name1'=>$_SESSION['customer']->listing[$index]->billing_name1, 'customers_billing_name2'=>$_SESSION['customer']->listing[$index]->billing_name2, 'customers_billing_address'=>$_SESSION['customer']->listing[$index]->billing_address, 'customers_billing_postcode'=>$_SESSION['customer']->listing[$index]->billing_postcode, 'customers_billing_city'=>$_SESSION['customer']->listing[$index]->billing_city, 'customers_billing_country'=>$_SESSION['customer']->listing[$index]->billing_country, 'customers_billing_email_address'=>$_SESSION['customer']->listing[$index]->billing_email_address, 'customers_billing_phone'=>$_SESSION['customer']->listing[$index]->billing_phone, 'customers_billing_fax'=>$_SESSION['customer']->listing[$index]->billing_fax), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td align="center" width="20" class="entryListing-data">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_CUSTOMERS)) . tep_create_parameters(array('action'=>'delete_entry', 'customers_id'=>$_SESSION['customer']->listing[$index]->id, 'customers_name'=>$_SESSION['customer']->listing[$index]->name, 'customers_billing_name1'=>$_SESSION['customer']->listing[$index]->billing_name1, 'customers_billing_name2'=>$_SESSION['customer']->listing[$index]->billing_name2, 'customers_billing_address'=>$_SESSION['customer']->listing[$index]->billing_address, 'customers_billing_postcode'=>$_SESSION['customer']->listing[$index]->billing_postcode, 'customers_billing_city'=>$_SESSION['customer']->listing[$index]->billing_city, 'customers_billing_country'=>$_SESSION['customer']->listing[$index]->billing_country, 'customers_billing_email_address'=>$_SESSION['customer']->listing[$index]->billing_email_address, 'customers_billing_phone'=>$_SESSION['customer']->listing[$index]->billing_phone, 'customers_billing_fax'=>$_SESSION['customer']->listing[$index]->billing_fax), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="8" align="center">
                      <?php echo TEXT_CUSTOMERS_LISTING_IS_EMPTY; ?>
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