<?php
/****************************************************************************
 * CODE FILE   : administration_roles.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 21 june 2009
 * Description : Role administration form
 *               Data validation sequence
 *               Storing of entered data (via role object)
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
    case 'enter_data':
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['roles_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No roles_name
      } else if ($_POST['categories_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No categories_id
      } else {
        /******************************/
        /*** OK, entry can be saved ***/
        /******************************/
        $administration_role = new role($_POST['roles_id']);
        $administration_role->fill($_POST['roles_name'],
                                   $_POST['roles_description'],
                                   $_POST['roles_mandatory_ticket_entry'],
                                   $_POST['projects_id'],
                                   $_POST['categories_id']);
        $administration_role->save();

        // Clear all values except mPath
        foreach($_POST as $key=>$value) {
          if ($key != 'mPath' && $key != 'projects_id') {
            unset($_POST[$key]);
          }
        }
      }
      break;
    case 'delete_entry':
      // Check for dependencies
      $administration_role = new role($_POST['roles_id']);
      if ($administration_role->has_dependencies()) {
        $error_level = 3; // Related employee-role(s) exist
        $_POST['action'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_role = new role($_POST['roles_id']);
      $administration_role->delete();
      unset($_POST['roles_id']);
      $_POST['action'] = '';
      break;
  }

  // Create a new role object with id == 0 (default)
  $_SESSION['role'] = new role(0, $_POST['projects_id']);

  // header //
  require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- body //-->
  <table style="border-width:0px;width:100%;border-spacing:3">
    <tr>
      <td style="width:<?php echo BOX_WIDTH; ?>px;vertical-align:top;padding:3">
        <table style="border-width:0px;width:<?php echo BOX_WIDTH; ?>px;border-spacing:0" cellpadding="2">
          <!-- left_navigation //-->
          <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
          <!-- left_navigation_eof //-->
        </table>
      </td>
      <!-- body_text //-->
      <td style="width:100%;vertical-align:top;padding:3">
        <table style="border-width:0px;width:100%;border-spacing:0">
          <tr>
            <td style="padding:0">
              <table style="border-width:0px;width:100%;border-spacing:0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_ROLES; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'gear-64x64.png', HEADER_TEXT_ADMINISTRATION_ROLES, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="width:100%;vertical-align:top;padding:0">
              <table style="border-width:0px;width:100%;border-spacing:0">
                <tr>
                  <td style="vertical-align:top;padding:0">
                    <table border="0" width="300pt" cellspacing="0" cellpadding="2" class="item_entry">
                      <tr>
                        <td class="item_entry" style="text-align:center"><?php echo TEXT_PROJECTS; ?></td>
                      </tr>
                      <tr>
                        <td class="item_entry">
                          <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_ADMINISTRATION_ROLES)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
                          $temp_project = new project();
                          echo tep_html_select('projects_id', tep_get_partial_array($temp_project->listing, 'id', 'name'), true, (tep_not_null($_POST['projects_id'])?$_POST['projects_id']:'select_none'), 'onChange="this.form.submit();" size="'.(sizeof($temp_project->listing)>1?(sizeof($temp_project->listing)<25?sizeof($temp_project->listing):25):2).'" style="width: 100%"');
                          ?>
                          </form>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td style="padding:0"><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
                  <td style="width:100%;vertical-align:top;padding:0">
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                          <?php require(DIR_WS_INCLUDES . 'role_entry.php'); ?>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                            <tr style="vertical-align:top">
                              <td class="entryListing-heading"><?php echo TEXT_ROLES_NAME; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_ROLES_DESCRIPTION; ?></td>
                              <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_ROLES_MANDATORY_TICKET_ENTRY; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_ROLES_CATEGORY; ?></td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                            </tr>
                            <?php if (tep_not_null($_POST['projects_id']) && !$_SESSION['role']->listing_empty) {
                              $odd_or_even = "odd";
                              for ($index = 0; $index < sizeof($_SESSION['role']->listing); $index++) { ?>
                                <tr class="entryListing-<?php echo $odd_or_even; ?>" style="text-align:top">
                                  <td class="entryListing-data"><?php echo $_SESSION['role']->listing[$index]->name; ?></td>
                                  <td class="entryListing-data"><?php echo $_SESSION['role']->listing[$index]->description; ?></td>
                                  <td class="entryListing-data" style="width:75px;text-align:center">
                                    <?php if($_SESSION['role']->listing[$index]->mandatory_ticket_entry) {
                                      echo tep_image(DIR_WS_IMAGES . 'plus.gif', TEXT_ROLES_MANDATORY_TICKET_ENTRY, null, null, 'style="vertical-align:middle"');
                                    } else {
                                      echo tep_draw_separator('pixel_trans.gif', '16', '16');
                                    } ?>
                                  </td>
                                  <td class="entryListing-data"><?php echo $_SESSION['role']->listing[$index]->category->name; ?></td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_ROLES)) . tep_create_parameters(array('action'=>'enter_data', 'roles_id'=>$_SESSION['role']->listing[$index]->id, 'roles_name'=>$_SESSION['role']->listing[$index]->name, 'roles_description'=>$_SESSION['role']->listing[$index]->description, 'roles_mandatory_ticket_entry'=>$_SESSION['role']->listing[$index]->mandatory_ticket_entry, 'categories_id'=>$_SESSION['role']->listing[$index]->category->id), array('mPath', 'projects_id'), 'hidden_field');
                                    echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_ROLES)) . tep_create_parameters(array('action'=>'delete_entry', 'roles_id'=>$_SESSION['role']->listing[$index]->id, 'roles_name'=>$_SESSION['role']->listing[$index]->name, 'roles_description'=>$_SESSION['role']->listing[$index]->description, 'roles_mandatory_ticket_entry'=>$_SESSION['role']->listing[$index]->mandatory_ticket_entry, 'categories_id'=>$_SESSION['role']->listing[$index]->category->id), array('mPath', 'projects_id'), 'hidden_field');
                                    echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                </tr>
                                <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                              }
                            } else { ?>
                              <tr class="entryListing-odd">
                                <td class="entryListing-data" colspan="6"  style="text-align:center">
                                  <?php echo TEXT_ROLES_LISTING_IS_EMPTY; ?>
                                </td>
                              </tr>
                            <?php } ?>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
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