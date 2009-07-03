<?php
/****************************************************************************
 * CODE FILE   : administration_profiles.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 03 july 2009
 * Description : Profile administration form
 *               Data validation sequence
 *               Storing of entered data (via profile object)
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
      if ($_POST['profiles_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No profiles_name
      } else {
        // OK, entry can be saved
        $administration_profile = new profile($_POST['profiles_id']);
        $administration_profile->fill($_POST['profiles_name'], $_POST['profiles_rights_login'], $_POST['profiles_rights_projectlisting'], $_POST['profiles_rights_timeregistration'], $_POST['profiles_rights_analysis'], $_POST['profiles_rights_administration']);
        $administration_profile->save();

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
      $administration_profile = new profile($_POST['profiles_id']);
      if ($administration_profile->has_dependencies()) {
        $error_level = 2; // Related employee(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_profile = new profile($_POST['profiles_id']);
      $administration_profile->delete();
      unset($_POST['profiles_id']);
      $_POST['action'] = '';
      break;
  }

  // Create a new profile object with id == 0 (default)
  $_SESSION['profile'] = new profile();

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_PROFILES; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'firewall-64x64.png', HEADER_TEXT_ADMINISTRATION_CATEGORIES, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'profile_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo TEXT_PROFILES_NAME; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_PROFILES_RIGHTS_LOGIN; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_PROFILES_RIGHTS_PROJECTLISTING; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_PROFILES_RIGHTS_TIMEREGISTRATION; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_PROFILES_RIGHTS_ANALYSIS; ?></td>
                  <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_PROFILES_RIGHTS_ADMINISTRATION; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['profile']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['profile']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data"><?php echo $_SESSION['profile']->listing[$index]->name; ?></td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['profile']->listing[$index]->right['login']) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_PROFILES_RIGHTS_LOGIN, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['profile']->listing[$index]->right['projectlisting']) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_PROFILES_RIGHTS_PROJECTLISTING, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['profile']->listing[$index]->right['timeregistration']) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_PROFILES_RIGHTS_TIMEREGISTRATION, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['profile']->listing[$index]->right['analysis']) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_PROFILES_RIGHTS_ANALYSIS, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:75px;text-align:center">
                        <?php if($_SESSION['profile']->listing[$index]->right['administration']) {
                          echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_PROFILES_RIGHTS_ADMINISTRATION, null, null, 'style="vertical-align:middle"');
                        } else {
                          echo tep_draw_separator('pixel_trans.gif', '16', '16');
                        } ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array('action'=>'enter_data', 'profiles_id'=>$_SESSION['profile']->listing[$index]->id, 'profiles_name'=>$_SESSION['profile']->listing[$index]->name, 'profiles_rights_login'=>$_SESSION['profile']->listing[$index]->right['login'], 'profiles_rights_projectlisting'=>$_SESSION['profile']->listing[$index]->right['projectlisting'], 'profiles_rights_timeregistration'=>$_SESSION['profile']->listing[$index]->right['timeregistration'], 'profiles_rights_analysis'=>$_SESSION['profile']->listing[$index]->right['analysis'], 'profiles_rights_administration'=>$_SESSION['profile']->listing[$index]->right['administration']), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_PROFILES)) . tep_create_parameters(array('action'=>'delete_entry', 'profiles_id'=>$_SESSION['profile']->listing[$index]->id, 'profiles_name'=>$_SESSION['profile']->listing[$index]->name, 'profiles_rights_login'=>$_SESSION['profile']->listing[$index]->right['login'], 'profiles_rights_projectlisting'=>$_SESSION['profile']->listing[$index]->right['projectlisting'], 'profiles_rights_timeregistration'=>$_SESSION['profile']->listing[$index]->right['timeregistration'], 'profiles_rights_analysis'=>$_SESSION['profile']->listing[$index]->right['analysis'], 'profiles_rights_administration'=>$_SESSION['profile']->listing[$index]->right['administration']), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="8"  style="text-align:center">
                      <?php echo TEXT_PROFILES_LISTING_IS_EMPTY; ?>
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