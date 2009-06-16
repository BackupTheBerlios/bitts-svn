<?php
/****************************************************************************
 * CODE FILE   : administration_business_units.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 june 2009
 * Description : Unit administration form
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
  if (!$_SESSION['employee']->is_administrator)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['units_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No units_name
      } else {
        // OK, entry can be saved
        $administration_unit = new unit($_POST['units_id']);
        $administration_unit->fill($_POST['units_name'],
                                   $_POST['units_description']);
        $administration_unit->save();

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
      $administration_unit = new unit($_POST['units_id']);
      if ($administration_unit->has_dependencies()) {
        $error_level = 2; // Related tariff(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_unit = new unit($_POST['units_id']);
      $administration_unit->delete();
      unset($_POST['units_id']);
      $_POST['action'] = '';
      break;
  }

  // Create a new unit object with id == 0 (default)
  $_SESSION['unit'] = new unit();

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_UNITS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'time_date-64x64.png', HEADER_TEXT_ADMINISTRATION_UNITS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'unit_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo TEXT_UNITS_NAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_UNITS_DESCRIPTION; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['unit']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['unit']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data"><?php echo $_SESSION['unit']->listing[$index]->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['unit']->listing[$index]->description; ?></td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_UNITS)) . tep_create_parameters(array('action'=>'enter_data', 'units_id'=>$_SESSION['unit']->listing[$index]->id, 'units_name'=>$_SESSION['unit']->listing[$index]->name, 'units_description'=>$_SESSION['unit']->listing[$index]->description), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_UNITS)) . tep_create_parameters(array('action'=>'delete_entry', 'units_id'=>$_SESSION['unit']->listing[$index]->id, 'units_name'=>$_SESSION['unit']->listing[$index]->name, 'units_description'=>$_SESSION['unit']->listing[$index]->description), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="5"  style="text-align:center">
                      <?php echo TEXT_UNITS_LISTING_IS_EMPTY; ?>
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