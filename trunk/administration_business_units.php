<?php
/****************************************************************************
 * CODE FILE   : administration_business_units.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 01 july 2009
 * Description : Business Unit administration form
 *               Data validation sequence
 *               Storing of entered data (via business_unit object)
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

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['business_units_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No business_units_name
      } else {
        // OK, entry can be saved
        $administration_business_unit = new business_unit($_POST['business_units_id']);
        $administration_business_unit->fill($_POST['business_units_name'],
                                            $_POST['business_units_image'],
                                            $_POST['business_units_image_position']);
        $administration_business_unit->save();

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
      $administration_business_unit = new business_unit($_POST['business_units_id']);
      if ($administration_business_unit->has_dependencies()) {
        $error_level = 2; // Related project(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_business_unit = new business_unit($_POST['business_units_id']);
      $administration_business_unit->delete();
      unset($_POST['business_units_id']);
      $_POST['action'] = '';
      break;
  }

  // Create a new business_unit object with id == 0 (default)
  $_SESSION['business_unit'] = new business_unit();

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_BUSINESS_UNITS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'networked_pueblo-64x64.png', HEADER_TEXT_ADMINISTRATION_BUSINESS_UNITS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'business_unit_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo TEXT_BUSINESS_UNITS_NAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_BUSINESS_UNITS_IMAGE; ?></td>
                  <td class="entryListing-heading" style="text-align:center;width:75px"><?php echo TEXT_BUSINESS_UNITS_IMAGE_POSITION; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['business_unit']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['business_unit']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data"><?php echo $_SESSION['business_unit']->listing[$index]->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['business_unit']->listing[$index]->image; ?></td>
                      <td class="entryListing-data" style="text-align:center;width:75px"><?php echo $BUSINESS_UNITS_IMAGE_POSITION[$_SESSION['business_unit']->listing[$index]->image_position]; ?></td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('action'=>'enter_data', 'business_units_id'=>$_SESSION['business_unit']->listing[$index]->id, 'business_units_name'=>$_SESSION['business_unit']->listing[$index]->name, 'business_units_image'=>$_SESSION['business_unit']->listing[$index]->image, 'business_units_image_position'=>$_SESSION['business_unit']->listing[$index]->image_position), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_BUSINESS_UNITS)) . tep_create_parameters(array('action'=>'delete_entry', 'business_units_id'=>$_SESSION['business_unit']->listing[$index]->id, 'business_units_name'=>$_SESSION['business_unit']->listing[$index]->name, 'business_units_image'=>$_SESSION['business_unit']->listing[$index]->image, 'business_units_image_position'=>$_SESSION['business_unit']->listing[$index]->image_position), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="5"  style="text-align:center">
                      <?php echo TEXT_BUSINESS_UNITS_LISTING_IS_EMPTY; ?>
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