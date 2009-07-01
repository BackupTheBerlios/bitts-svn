<?php
/****************************************************************************
 * CODE FILE   : administration_tariffs.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 01 july 2009
 * Description : Tariff administration form
 *               Data validation sequence
 *               Storing of entered data
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

  // Create a new employee_role object with id == 0 (default)
  $_SESSION['tariff'] = new tariff(0, 'dummy');

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'enter_data':
      // Format tariff from database to display
      if (isset($_POST['tariffs_amount'])) {
        $_POST['tariffs_amount_display'] = tep_number_db_to_user($_POST['tariffs_amount'], 2);
      }
      // Format dates (from uts to display)
      if (isset($_POST['tariffs_start_date'])) {
        $_POST['tariffs_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['tariffs_start_date']);
      }
      if ($_POST['tariffs_end_date'] != 0) {
        $_POST['tariffs_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['tariffs_end_date']);
      } else {
        $_POST['tariffs_end_date_display'] = '';
      }
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // Format tariff from display to database
      if (tep_not_null($_POST['tariffs_amount_display']) && $_SESSION['tariff']->validate_amount($_POST['tariffs_amount_display'])) {
        $_POST['tariffs_amount'] = $_SESSION['tariff']->format_amount($_POST['tariffs_amount_display']);
      }
      // Format dates (from display to uts)
      if (tep_not_null($_POST['tariffs_start_date_display']) && tep_testdate(DATE_FORMAT_SHORT, $_POST['tariffs_start_date_display'])) {
        $_POST['tariffs_start_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['tariffs_start_date_display']);
      }
      if (tep_not_null($_POST['tariffs_end_date_display']) && tep_testdate(DATE_FORMAT_SHORT, $_POST['tariffs_end_date_display'])) {
        $_POST['tariffs_end_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['tariffs_end_date_display']);
      } else if (!tep_not_null($_POST['tariffs_end_date_display'])) {
        $_POST['tariffs_end_date'] = 0;
      }
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['tariffs_amount_display'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No tariffs_amount
      } else if ($_POST['units_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No units_id
      } else if ($_POST['tariffs_start_date_display'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 3; // No start_date
      } else if (!$_SESSION['tariff']->validate_amount($_POST['tariffs_amount_display'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 4; // Incorrect format of entered amount
      } else if (!tep_testdate(DATE_FORMAT_SHORT, $_POST['tariffs_start_date_display']) || (tep_not_null($_POST['tariffs_end_date_display']) && !tep_testdate(DATE_FORMAT_SHORT, $_POST['tariffs_end_date_display'])) || (($_POST['tariffs_start_date'] > $_POST['tariffs_end_date']) && ($_POST['tariffs_end_date'] != 0))) {
        $_POST['action'] = 'enter_data';
        $error_level = 5; // Incorrect format of entered date
      } else {
        // Check if dates exceed employee_role dates
        $temp_employee_role = new employee_role($_POST['employees_roles_id'], 'dummy');
        if ($_POST['tariffs_start_date'] < $temp_employee_role->start_date) {
          $_POST['action'] = 'enter_data';
          $error_level = 6; // Start date before employee_role start date
          break;
        } else if (($_POST['tariffs_end_date'] > $temp_employee_role->end_date && $temp_employee_role->end_date != 0) || ($_POST['tariffs_end_date'] == 0 && $temp_employee_role->end_date != 0)) {
          $_POST['action'] = 'enter_data';
          $error_level = 7; // End date after employee_role end date
          break;
        }

        // Create the tariff
        $administration_tariff = new tariff($_POST['tariffs_id'], 'dummy');

        // Check for duplicates
        if ($administration_tariff->has_duplicates($_POST['tariffs_start_date'], $_POST['tariffs_end_date'], $_POST['units_id'], $_POST['employees_roles_id'])) {
          $_POST['action'] = 'enter_data';
          $error_level = 8; // Duplicate entries found
          break;
        }

        // Do the checks
        if ($administration_tariff->id != 0) {
          // Existing tariff
          /********************************************/
          /*** First do the start date check series ***/
          /********************************************/
          if ($_POST['tariffs_start_date'] != $administration_tariff->start_date) {
            // Start date has changed
            if ($_POST['tariffs_start_date'] > $administration_tariff->start_date) {
              // Start date after original start date
              // Check if activities exist before new start date
              if (tep_not_null($administration_tariff->has_activities(null, 'activities_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['tariffs_start_date'])))) {
                $_POST['action'] = 'enter_data';
                $error_level = 9; // Activities exist before new start date (thus between old and new start date)
                break;
              }
            }
          }
          /*******************************************/
          /*** Second do the end date check series ***/
          /*******************************************/
          if ($_POST['tariffs_end_date'] != $administration_tariff->end_date) {
            // End date has changed
            if ((($_POST['tariffs_end_date'] < $administration_tariff->end_date) && $administration_tariff->end_date != 0) || $_POST['tariffs_end_date'] == 0) {
              // End date before original end date
              // Check if activities exist before new end date
              if (tep_not_null($administration_tariff->has_activities(null, 'activities_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['tariffs_end_date'])))) {
                $_POST['action'] = 'enter_data';
                $error_level = 10; // Activities exist after new end date (thus between old and new end date)
                break;
              }
            }
          }
        }

        /******************************/
        /*** OK, entry can be saved ***/
        /******************************/

        $administration_tariff->fill($_POST['tariffs_amount'],
                                     $_POST['tariffs_start_date'],
                                     $_POST['tariffs_end_date'],
                                     $_POST['units_id'],
                                     $_POST['employees_roles_id']);
        $administration_tariff->save();

        // Clear all values except mPath
        foreach($_POST as $key=>$value) {
          if ($key != 'mPath' && $key != 'projects_id' && $key != 'employees_roles_id') {
            unset($_POST[$key]);
          }
        }
      }
      break;
    case 'delete_entry':
      // Check for dependencies
      $administration_tariff = new tariff($_POST['tariffs_id']);
      if ($administration_tariff->has_dependencies()) {
        $error_level = 11; // Related activities exist
        $_POST['action'] = '';
      }
      // Format dates (from uts to display)
      $_POST['tariffs_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['tariffs_start_date']);
      if ($_POST['tariffs_end_date'] != 0) {
        $_POST['tariffs_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['tariffs_end_date']);
      } else {
        $_POST['tariffs_end_date_display'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_tariff = new tariff($_POST['tariffs_id']);
      $administration_tariff->delete();
      unset($_POST['tariffs_id']);
      $_POST['action'] = '';
      break;
  }

  // Reload the tariff object in order to
  // update the tariff listing below
  $_SESSION['tariff'] = new tariff(0, $_POST['employees_roles_id']);

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_TARIFFS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'dollar-64x64.png', HEADER_TEXT_ADMINISTRATION_TARIFFS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="width:100%;vertical-align:top;padding:0">
              <table style="border-width:0px;width:100%;border-spacing:0">
                <tr>
                  <td style="vertical-align:top;padding:0">
                    <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
                      <tr>
                        <td class="item_entry" style="text-align:center"><?php echo TEXT_PROJECTS; ?></td>
                      </tr>
                      <tr>
                        <td class="item_entry">
                          <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
                          $temp_project = new project();
                          echo tep_html_select('projects_id', tep_get_partial_array($temp_project->listing, 'id', 'name'), true, (tep_not_null($_POST['projects_id'])?$_POST['projects_id']:'select_none'), 'onChange="this.form.submit();" size="'.(sizeof($temp_project->listing)>1?(sizeof($temp_project->listing)<24?sizeof($temp_project->listing):25):2).'" style="width:175pt"');
                          ?>
                          </form>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td style="padding:0"><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
                  <td style="vertical-align:top;padding:0">
                    <table border="0" cellspacing="0" cellpadding="2" class="item_entry">
                      <tr>
                        <td class="item_entry" style="text-align:center"><?php echo TEXT_EMPLOYEES_ROLES; ?></td>
                      </tr>
                      <tr>
                        <td class="item_entry">
                          <?php echo tep_draw_form('employee_role_selection', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array(), array('mPath', 'projects_id'), 'hidden_field');
                          $temp_employee_role = new employee_role(0, $_POST['projects_id']);
                          echo tep_html_select('employees_roles_id', tep_get_partial_array($temp_employee_role->listing, 'id', 'name'), true, (tep_not_null($_POST['employees_roles_id'])?$_POST['employees_roles_id']:'select_none'), 'onChange="this.form.submit();" size="'.(sizeof($temp_employee_role->listing)>1?(sizeof($temp_employee_role->listing)<25?sizeof($temp_employee_role->listing):25):2).'" style="width:175pt"');
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
                          <?php require(DIR_WS_INCLUDES . 'tariff_entry.php'); ?>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                            <tr style="vertical-align:top">
                              <td class="entryListing-heading"><?php echo TEXT_TARIFFS_AMOUNT; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_UNITS; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_TARIFFS_START_DATE.'<br>'.TEXT_TARIFFS_END_DATE; ?></td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                            </tr>
                            <?php if (tep_not_null($_POST['projects_id']) && tep_not_null($_POST['employees_roles_id']) && !$_SESSION['tariff']->listing_empty) {
                              $odd_or_even = "odd";
                              for ($index = 0; $index < sizeof($_SESSION['tariff']->listing); $index++) { ?>
                                <tr class="entryListing-<?php echo $odd_or_even; ?>" style="vertical-align:top">
                                  <td class="entryListing-data"><?php echo tep_number_db_to_user($_SESSION['tariff']->listing[$index]->amount, 2); ?></td>
                                  <td class="entryListing-data"><?php echo $_SESSION['tariff']->listing[$index]->unit->name; ?></td>
                                  <td class="entryListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, $_SESSION['tariff']->listing[$index]->start_date).'<br>'.($_SESSION['tariff']->listing[$index]->end_date!=0?tep_strftime(DATE_FORMAT_SHORT, $_SESSION['tariff']->listing[$index]->end_date):'&#8734;'); ?></td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('action'=>'enter_data', 'tariffs_id'=>$_SESSION['tariff']->listing[$index]->id, 'tariffs_amount'=>$_SESSION['tariff']->listing[$index]->amount, 'units_id'=>$_SESSION['tariff']->listing[$index]->unit->id, 'tariffs_start_date'=>$_SESSION['tariff']->listing[$index]->start_date, 'tariffs_end_date'=>$_SESSION['tariff']->listing[$index]->end_date), array('mPath', 'projects_id', 'employees_roles_id'), 'hidden_field');
                                    echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_TARIFFS)) . tep_create_parameters(array('action'=>'delete_entry', 'tariffs_id'=>$_SESSION['tariff']->listing[$index]->id, 'tariffs_amount'=>$_SESSION['tariff']->listing[$index]->amount, 'units_id'=>$_SESSION['tariff']->listing[$index]->unit->id, 'tariffs_start_date'=>$_SESSION['tariff']->listing[$index]->start_date, 'tariffs_end_date'=>$_SESSION['tariff']->listing[$index]->end_date), array('mPath', 'projects_id', 'employees_roles_id'), 'hidden_field');
                                    echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                </tr>
                                <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                              }
                            } else { ?>
                              <tr class="entryListing-odd">
                                <td class="entryListing-data" colspan="5"  style="text-align:center">
                                  <?php echo TEXT_TARIFFS_LISTING_IS_EMPTY; ?>
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