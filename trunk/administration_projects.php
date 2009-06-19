<?php
/****************************************************************************
 * CODE FILE   : administration_projects.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 19 june 2009
 * Description : Project administration form
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

  // Create a new project object with id == 0 (default)
  $_SESSION['project'] = new project();

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'enter_data':
      // Format dates (from uts to display)
      if (isset($_POST['projects_start_date'])) {
        $_POST['projects_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['projects_start_date']);
      }
      if ($_POST['projects_end_date'] != 0) {
        $_POST['projects_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['projects_end_date']);
      } else {
        $_POST['projects_end_date_display'] = '';
      }
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // First format dates (from display to uts)
      if (tep_not_null($_POST['projects_start_date_display'])) {
        $_POST['projects_start_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['projects_start_date_display']);
      }
      if (tep_not_null($_POST['projects_end_date_display'])) {
        $_POST['projects_end_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['projects_end_date_display']);
      } else {
        $_POST['projects_end_date'] = 0;
      }
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['projects_name'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No projects_name
      } else if ($_POST['projects_start_date_display'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No projects_start_date
      } else if ($_POST['projects_business_units_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 3; // No business_units_id
      } else if ($_POST['projects_customers_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 4; // No customers_id
      } else if (!tep_testdate(DATE_FORMAT_SHORT, $_POST['projects_start_date_display']) || (tep_not_null($_POST['projects_end_date_display']) && !tep_testdate(DATE_FORMAT_SHORT, $_POST['projects_end_date_display']))) {
        $_POST['action'] = 'enter_data';
        $error_level = 5; // Incorrect format of entered date
      } else if (!$_SESSION['project']->validate_hours($_POST['projects_calculated_hours'])) {
        $_POST['action'] = 'enter_data';
        $error_level = 6; // Incorrect format or value of entered hours
      } else {
        // Create the project
        $administration_project = new project($_POST['projects_id']);
        // Do the checks
        if ($administration_project->id != 0) {
          // Existing project
          /********************************************/
          /*** First do the start date check series ***/
          /********************************************/
          if ($_POST['projects_start_date'] != $administration_project->start_date) {
            // Start date has changed
            if ($_POST['projects_start_date'] < $administration_project->start_date) {
              // Start date before original start date
              if ($_POST['question_er1'] == 'ASKED') {
                if ($_POST['question_er1_answer'] == true) {
                  // Change employee-roles OK
                  if ($_POST['question_t1'] == 'ASKED') {
                    // Change tariffs_start_date OK or not (doesn't matter in this stage)
                  } else if (tep_not_null($administration_project->has_tariffs('tariffs_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->start_date)))) {
                    $_POST['question_t1'] = 'ASK';
                    break;
                  }
                }
              } else if (tep_not_null($administration_project->has_employees_roles('employees_roles_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->start_date)))) {
                $_POST['question_er1'] = 'ASK';
                break;
              }
            } else {
              // Start date after original start date
              if (tep_not_null($administration_project->has_employees_roles('employees_roles_end_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date'])))) {
                $_POST['action'] = 'enter_data';
                $error_level = 7; // MR end date before new start date
                break;
              } else if (tep_not_null($administration_project->has_employees_roles('employees_roles_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']), 'AND', 'employees_roles_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date'])))) {
                $_POST['question_er1_answer'] = true; // Employees-Roles start date will change
                // Check tariffs
                if (tep_not_null($administration_project->has_tariffs('tariffs_end_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date'])))) {
                  $_POST['action'] = 'enter_data';
                  $error_level = 8; // Tariff end date before new start date
                  break;
                } else if (tep_not_null($administration_project->has_tariffs('tariffs_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']), 'AND', 'tariffs_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date'])))) {
                  $_POST['question_t1_answer'] = true; // Tariffs start date will change
                  // Check if activities exist before new start date
                  if (tep_not_null($administration_project->has_activities('activities_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date'])))) {
                    $_POST['action'] = 'enter_data';
                    $error_level = 9; // Activities exist before new start date (thus between old and new start date)
                    break;
                  }
                }
              }
            }
          }
          /*******************************************/
          /*** Second do the end date check series ***/
          /*******************************************/
          if ($_POST['projects_end_date'] != $administration_project->end_date) {
            // End date has changed
            if (($_POST['projects_end_date'] > $administration_project->end_date) && ($administration_project->end_date != 0)) {
              // End date after original end date
              if ($_POST['question_er2'] == 'ASKED') {
                if ($_POST['question_er2_answer'] == true) {
                  // Change employee-roles OK
                  if ($_POST['question_t2'] == 'ASKED') {
                    // Change tariffs_end_date OK or not (doesn't matter in this stage)
                  } else if (tep_not_null($administration_project->has_tariffs('tariffs_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->end_date)))) {
                    $_POST['question_t2'] = 'ASK';
                    break;
                  }
                }
              } else if (tep_not_null($administration_project->has_employees_roles('employees_roles_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->end_date)))) {
                $_POST['question_er2'] = 'ASK';
                break;
              }
            } else {
              // End date before original end date
              if (tep_not_null($administration_project->has_employees_roles('employees_roles_start_date', '>', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31')))) {
                $_POST['action'] = 'enter_data';
                $error_level = 10; // MR start date after new end date
                break;
              } else if (tep_not_null($administration_project->has_employees_roles('employees_roles_start_date', '<=', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'), 'AND', 'employees_roles_end_date', '>', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31')))) {
                $_POST['question_er2_answer'] = true; // Employees-Roles end date will change
                // Check tariffs
                if (tep_not_null($administration_project->has_tariffs('tariffs_start_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date'])))) {
                  $_POST['action'] = 'enter_data';
                  $error_level = 11; // Tariff start date after new end date
                  break;
                } else if (tep_not_null($administration_project->has_tariffs('tariffs_start_date', '<=', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'), 'AND', 'tariffs_end_date', '>', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31')))) {
                  $_POST['question_t2_answer'] = true; // Tariffs end date will change
                  // Check if activities exist before new end date
                  if (tep_not_null($administration_project->has_activities('activities_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date'])))) {
                    $_POST['action'] = 'enter_data';
                    $error_level = 12; // Activities exist after new end date (thus between old and new end date)
                    break;
                  }
                }
              }
            }
          }
        }

        /******************************/
        /*** OK, entry can be saved ***/
        /******************************/

        // Let's start with updating the employees_roles and tariffs (if applicable)
        if ($_POST['question_er1_answer']) {
          // Update employees_roles with new start date
          if ($_POST['projects_start_date'] < $administration_project->start_date) {
            $employees_roles_array = $administration_project->has_employees_roles('employees_roles_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->start_date));
          } else {
            $employees_roles_array = $administration_project->has_employees_roles('employees_roles_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']), 'AND', 'employees_roles_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']));
          }
          for ($index=0; $index<sizeof($employees_roles_array); $index++) {
            $employees_roles_array[$index]->start_date = $_POST['projects_start_date'];
            $employees_roles_array[$index]->save();
          }
        }
        if ($_POST['question_t1_answer']) {
          // Update tariffs with new start date
          if ($_POST['projects_start_date'] < $administration_project->start_date) {
            $tariffs_array = $administration_project->has_tariffs('tariffs_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->start_date));
          } else {
            $tariffs_array = $administration_project->has_tariffs('tariffs_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']), 'AND', 'tariffs_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_start_date']));
          }
          for ($index=0; $index<sizeof($tariffs_array); $index++) {
            $tariffs_array[$index]->start_date = $_POST['projects_start_date'];
            $tariffs_array[$index]->save();
          }
        }
        if ($_POST['question_er2_answer']) {
          // Update employees_roles with new end date
          if (($_POST['projects_end_date'] > $administration_project->end_date) && ($administration_project->end_date != 0)) {
            $employees_roles_array = $administration_project->has_employees_roles('employees_roles_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->end_date));
          } else {
            $employees_roles_array = $administration_project->has_employees_roles('employees_roles_start_date', '<=', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'), 'AND', 'employees_roles_end_date', '>', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'));
          }
          for ($index=0; $index<sizeof($employees_roles_array); $index++) {
            $employees_roles_array[$index]->end_date = $_POST['projects_end_date'];
            $employees_roles_array[$index]->save();
          }
        }
        if ($_POST['question_t2_answer']) {
          // Update tariffs with new end date
          if (($_POST['projects_end_date'] > $administration_project->end_date) && ($administration_project->end_date != 0)) {
            $tariffs_array = $administration_project->has_tariffs('tariffs_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_project->end_date));
          } else {
            $tariffs_array = $administration_project->has_tariffs('tariffs_start_date', '<=', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'), 'AND', 'tariffs_end_date', '>', ($_POST['projects_end_date']!=0?tep_strftime(DATE_FORMAT_DATABASE, $_POST['projects_end_date']):'2099-12-31'));
          }
          for ($index=0; $index<sizeof($tariffs_array); $index++) {
            $tariffs_array[$index]->start_date = $_POST['projects_end_date'];
            $tariffs_array[$index]->save();
          }
        }

        // Finally save the project
        $administration_project = new project($_POST['projects_id']);
        $administration_project->fill($_POST['projects_name'],
                                      $_POST['projects_description'],
                                      $_POST['projects_customers_contact_name'],
                                      $_POST['projects_customers_reference'],
                                      $_POST['projects_start_date'],
                                      $_POST['projects_end_date'],
                                      $_POST['projects_calculated_hours'],
                                      $_POST['projects_calculated_hours_period'],
                                      $_POST['projects_business_units_id'],
                                      $_POST['projects_customers_id']);
        $administration_project->save();

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
      $administration_project = new project($_POST['projects_id']);
      if ($administration_project->has_dependencies()) {
        $error_level = 13; // Related role(s) exist
        $_POST['action'] = '';
      }
      // Format dates (from uts to display)
      $_POST['projects_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['projects_start_date']);
      if ($_POST['projects_end_date'] != 0) {
        $_POST['projects_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['projects_end_date']);
      } else {
        $_POST['projects_end_date_display'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_project = new project($_POST['projects_id']);
      $administration_project->delete();
      unset($_POST['projects_id']);
      $_POST['action'] = '';
      break;
  }

  // Reload the project object in order to
  // update the project listing below
  $_SESSION['project'] = new project();

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_PROJECTS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'tree_in_an_inbox-64x64.png', HEADER_TEXT_ADMINISTRATION_PROJECTS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <?php require(DIR_WS_INCLUDES . 'project_entry.php'); ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr valign="top">
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_NAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_DESCRIPTION; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_CUSTOMERS_CONTACT_NAME.'<br>'.TEXT_PROJECTS_CUSTOMERS_REFERENCE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_START_DATE.'<br>'.TEXT_PROJECTS_END_DATE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PROJECTS_CALCULATED_HOURS.'<br>'.TEXT_PROJECTS_CALCULATED_HOURS_PERIOD; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_BUSINESS_UNITS; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_CUSTOMERS; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['project']->listing_empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['project']->listing); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>" valign="top">
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->description; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->customers_contact_name.'<br>'.$_SESSION['project']->listing[$index]->customers_reference; ?></td>
                      <td class="entryListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, $_SESSION['project']->listing[$index]->start_date).'<br>'.($_SESSION['project']->listing[$index]->end_date!=0?tep_strftime(DATE_FORMAT_SHORT, $_SESSION['project']->listing[$index]->end_date):'&#8734;'); ?></td>
                      <td class="entryListing-data"><?php echo ($_SESSION['project']->listing[$index]->calculated_hours>0?$_SESSION['project']->listing[$index]->calculated_hours.'<br>'.$PROJECTS_CALCULATED_HOURS_PERIOD[$_SESSION['project']->listing[$index]->calculated_hours_period]:BODY_TEXT_NOT_APPLICABLE); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->business_unit->name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['project']->listing[$index]->customer->name; ?></td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'enter_data', 'projects_id'=>$_SESSION['project']->listing[$index]->id, 'projects_name'=>$_SESSION['project']->listing[$index]->name, 'projects_description'=>$_SESSION['project']->listing[$index]->description, 'projects_customers_contact_name'=>$_SESSION['project']->listing[$index]->customers_contact_name, 'projects_customers_reference'=>$_SESSION['project']->listing[$index]->customers_reference, 'projects_start_date'=>$_SESSION['project']->listing[$index]->start_date, 'projects_end_date'=>$_SESSION['project']->listing[$index]->end_date, 'projects_calculated_hours'=>$_SESSION['project']->listing[$index]->calculated_hours, 'projects_calculated_hours_period'=>$_SESSION['project']->listing[$index]->calculated_hours_period, 'projects_business_units_id'=>$_SESSION['project']->listing[$index]->business_unit->id, 'projects_customers_id'=>$_SESSION['project']->listing[$index]->customer->id), array('mPath'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                      <td class="entryListing-data" style="width:20px;text-align:center">
                        <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_PROJECTS)) . tep_create_parameters(array('action'=>'delete_entry', 'projects_id'=>$_SESSION['project']->listing[$index]->id, 'projects_name'=>$_SESSION['project']->listing[$index]->name, 'projects_description'=>$_SESSION['project']->listing[$index]->description, 'projects_customers_contact_name'=>$_SESSION['project']->listing[$index]->customers_contact_name, 'projects_customers_reference'=>$_SESSION['project']->listing[$index]->customers_reference, 'projects_start_date'=>$_SESSION['project']->listing[$index]->start_date, 'projects_end_date'=>$_SESSION['project']->listing[$index]->end_date, 'projects_calculated_hours'=>$_SESSION['project']->listing[$index]->calculated_hours, 'projects_calculated_hours_period'=>$_SESSION['project']->listing[$index]->calculated_hours_period, 'projects_business_units_id'=>$_SESSION['project']->listing[$index]->business_unit->id, 'projects_customers_id'=>$_SESSION['project']->listing[$index]->customer->id), array('mPath'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>'; ?>
                      </td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="9"  style="text-align:center">
                      <?php echo TEXT_PROJECTS_LISTING_IS_EMPTY; ?>
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