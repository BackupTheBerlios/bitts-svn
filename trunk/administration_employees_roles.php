<?php
/****************************************************************************
 * CODE FILE   : administration_employees_roles.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 20 june 2009
 * Description : Employee-Role administration form
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
  if (!$_SESSION['employee']->is_administrator)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Reset error level
  $error_level = 0;

  switch ($_POST['action']) {
    case 'enter_data':
      // Format dates (from uts to display)
      if (isset($_POST['employees_roles_start_date'])) {
        $_POST['employees_roles_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['employees_roles_start_date']);
      }
      if ($_POST['employees_roles_end_date'] != 0) {
        $_POST['employees_roles_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['employees_roles_end_date']);
      } else {
        $_POST['employees_roles_end_date_display'] = '';
      }
      break;
    case 'save_data':
      // When action==save_data: verify entered data and save if OK / set errorlevel when NOK
      //
      // First format dates (from display to uts)
      if (tep_not_null($_POST['employees_roles_start_date_display'])) {
        $_POST['employees_roles_start_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['employees_roles_start_date_display']);
      }
      if (tep_not_null($_POST['employees_roles_end_date_display'])) {
        $_POST['employees_roles_end_date'] = tep_datetouts(DATE_FORMAT_SHORT, $_POST['employees_roles_end_date_display']);
      } else {
        $_POST['employees_roles_end_date'] = 0;
      }
      // Check for data format and required fields
      // change action when not everything is filled-in
      if ($_POST['roles_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 1; // No roles_id
      } if ($_POST['employees_id'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 2; // No employees_id
      } if ($_POST['employees_roles_start_date_display'] == '') {
        $_POST['action'] = 'enter_data';
        $error_level = 3; // No start_date
      } else if (!tep_testdate(DATE_FORMAT_SHORT, $_POST['employees_roles_start_date_display']) || (tep_not_null($_POST['employees_roles_end_date_display']) && !tep_testdate(DATE_FORMAT_SHORT, $_POST['employees_roles_end_date_display']))) {
        $_POST['action'] = 'enter_data';
        $error_level = 4; // Incorrect format of entered date
      } else {
        // Check if dates exceed project dates
        $temp_project = new project($_POST['projects_id'], 'dummy');
        if ($_POST['employees_roles_start_date'] < $temp_project->start_date) {
          $_POST['action'] = 'enter_data';
          $error_level = 5; // Start date before project start date
          break;
        } else if (($_POST['employees_roles_end_date'] > $temp_project->start_date && $temp_project->start_date != 0) || ($_POST['employees_roles_end_date'] == 0 && $temp_project->start_date != 0)) {
          $_POST['action'] = 'enter_data';
          $error_level = 6; // Start date after project end date
          break;
        }

        // Create and fill the employee_role
        $administration_employee_role = new employee_role($_POST['employees_roles_id'], 'dummy');

        // Check for duplicates
        if ($administration_employee_role->has_duplicates($_POST['employees_roles_start_date'], $_POST['employees_roles_end_date'])) {
          $_POST['action'] = 'enter_data';
          $error_level = 7; // Duplicate entries found
          break;
        }

        // Do the checks
        if ($administration_employee_role->id != 0) {
          // Existing employee_role
          /********************************************/
          /*** First do the start date check series ***/
          /********************************************/
          if ($_POST['employees_roles_start_date'] != $administration_employee_role->start_date) {
            // Start date has changed
            if ($_POST['employees_roles_start_date'] < $administration_employee_role->start_date) {
              // Start date before original start date
              if ($_POST['question_t1'] == 'ASKED') {
                // Change tariffs_start_date OK or not (doesn't matter in this stage)
              } else if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_employee_role->start_date)))) {
                $_POST['question_t1'] = 'ASK';
                break;
              }
            } else {
              // Start date after original start date
              // Check tariffs
              if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_end_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date'])))) {
                $_POST['action'] = 'enter_data';
                $error_level = 8; // Tariff end date before new start date
                break;
              } else if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date']), 'AND', 'tariffs_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date'])))) {
                $_POST['question_t1_answer'] = true; // Tariffs start date will change
                // Check if activities exist before new start date
                if (tep_not_null($administration_employee_role->has_activities(null, 'activities_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date'])))) {
                  $_POST['action'] = 'enter_data';
                  $error_level = 9; // Activities exist before new start date (thus between old and new start date)
                  break;
                }
              }
            }
          }
          /*******************************************/
          /*** Second do the end date check series ***/
          /*******************************************/
          if ($_POST['employees_roles_end_date'] != $administration_employee_role->end_date) {
            // End date has changed
            if ((($_POST['employees_roles_end_date'] > $administration_employee_role->end_date) && $administration_employee_role->end_date != 0) || $_POST['employees_roles_end_date'] == 0) {
              // End date after original end date
              if ($_POST['question_t2'] == 'ASKED') {
                // Change tariffs_end_date OK or not (doesn't matter in this stage)
              } else if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_employee_role->end_date)))) {
                $_POST['question_t2'] = 'ASK';
                break;
              }
            } else {
              // End date before original end date
              if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_start_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date'])))) {
                $_POST['action'] = 'enter_data';
                $error_level = 10; // Tariff start date after new end date
                break;
              } else if (tep_not_null($administration_employee_role->has_tariffs(null, 'tariffs_start_date', '<=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date']), 'AND', 'tariffs_end_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date'])))) {
                $_POST['question_t2_answer'] = true; // Tariffs end date will change
                // Check if activities exist before new end date
                if (tep_not_null($administration_employee_role->has_activities(null, 'activities_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date'])))) {
                  $_POST['action'] = 'enter_data';
                  $error_level = 11; // Activities exist after new end date (thus between old and new end date)
                  break;
                }
              }
            }
          }
        }

        /******************************/
        /*** OK, entry can be saved ***/
        /******************************/

        if ($_POST['question_t1_answer']) {
          // Update tariffs with new start date
          if ($_POST['employees_roles_start_date'] < $administration_employee_role->start_date) {
            $tariffs_array = $administration_employee_role->has_tariffs(null, 'tariffs_start_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_employee_role->start_date));
          } else {
            $tariffs_array = $administration_employee_role->has_tariffs(null ,'tariffs_start_date', '<', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date']), 'AND', 'tariffs_end_date', '>=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_start_date']));
          }
          for ($index=0; $index<sizeof($tariffs_array); $index++) {
            $tariffs_array[$index]->start_date = $_POST['employees_roles_start_date'];
            $tariffs_array[$index]->save();
          }
        }
        if ($_POST['question_t2_answer']) {
          // Update tariffs with new end date
          if ((($_POST['employees_roles_end_date'] > $administration_employee_role->end_date) && $administration_employee_role->end_date != 0) || $_POST['employees_roles_end_date'] == 0) {
            $tariffs_array = $administration_employee_role->has_tariffs(null, 'tariffs_end_date', '=', tep_strftime(DATE_FORMAT_DATABASE, $administration_employee_role->end_date));
          } else {
            $tariffs_array = $administration_employee_role->has_tariffs(null, 'tariffs_start_date', '<=', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date']), 'AND', 'tariffs_end_date', '>', tep_strftime(DATE_FORMAT_DATABASE, $_POST['employees_roles_end_date']));
          }
          for ($index=0; $index<sizeof($tariffs_array); $index++) {
            $tariffs_array[$index]->end_date = $_POST['employees_roles_end_date'];
            $tariffs_array[$index]->save();
          }
        }

        // Finally save the employee_role
        $administration_employee_role->fill($_POST['employees_roles_start_date'],
                                            $_POST['employees_roles_end_date'],
                                            $_POST['roles_id'],
                                            $_POST['employees_id']);
        $administration_employee_role->save();

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
      $administration_employee_role = new employee_role($_POST['employees_roles_id']);
      if ($administration_employee_role->has_dependencies()) {
        $error_level = 5; // Related tariff(s) exist
        $_POST['action'] = '';
      }
      // Format dates (from uts to display)
      $_POST['employees_roles_start_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['employees_roles_start_date']);
      if ($_POST['employees_roles_end_date'] != 0) {
        $_POST['employees_roles_end_date_display'] = tep_strftime(DATE_FORMAT_SHORT, $_POST['employees_roles_end_date']);
      } else {
        $_POST['employees_roles_end_date_display'] = '';
      }
      break;
    case 'delete_entry_confirmed':
      $administration_employee_role = new employee_role($_POST['employees_roles_id']);
      $administration_employee_role->delete();
      unset($_POST['employees_roles_id']);
      $_POST['action'] = '';
      break;
  }

  // Create a new employee_role object with id == 0 (default)
  $_SESSION['employee_role'] = new employee_role(0, $_POST['projects_id']);

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_EMPLOYEES_ROLES; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'glue-64x64.png', HEADER_TEXT_ADMINISTRATION_EMPLOYEES_ROLES, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
                          <?php echo tep_draw_form('project_selection', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
                          $temp_project = new project();
                          echo tep_html_select('projects_id', tep_get_partial_array($temp_project->listing, 'id', 'name'), true, (tep_not_null($_POST['projects_id'])?$_POST['projects_id']:'select_none'), 'onChange="this.form.submit();" size="'.sizeof($temp_project->listing).'" style="width: 100%"');
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
                          <?php require(DIR_WS_INCLUDES . 'employee_role_entry.php'); ?>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                            <tr style="vertical-align:top">
                              <td class="entryListing-heading"><?php echo TEXT_ROLES; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_EMPLOYEES; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_EMPLOYEES_ROLES_START_DATE.'<br>'.TEXT_EMPLOYEES_ROLES_END_DATE; ?></td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                            </tr>
                            <?php if (tep_not_null($_POST['projects_id']) && !$_SESSION['employee_role']->listing_empty) {
                              $odd_or_even = "odd";
                              for ($index = 0; $index < sizeof($_SESSION['employee_role']->listing); $index++) { ?>
                                <tr class="entryListing-<?php echo $odd_or_even; ?>" style="vertical-align:top">
                                  <td class="entryListing-data"><?php echo $_SESSION['employee_role']->listing[$index]->role->name; ?></td>
                                  <td class="entryListing-data"><?php echo $_SESSION['employee_role']->listing[$index]->employee->fullname; ?></td>
                                  <td class="entryListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, $_SESSION['employee_role']->listing[$index]->start_date).'<br>'.($_SESSION['employee_role']->listing[$index]->end_date!=0?tep_strftime(DATE_FORMAT_SHORT, $_SESSION['employee_role']->listing[$index]->end_date):'&#8734;'); ?></td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('edit_entry', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('action'=>'enter_data', 'employees_roles_id'=>$_SESSION['employee_role']->listing[$index]->id, 'roles_id'=>$_SESSION['employee_role']->listing[$index]->role->id, 'employees_id'=>$_SESSION['employee_role']->listing[$index]->employee->id, 'employees_roles_start_date'=>$_SESSION['employee_role']->listing[$index]->start_date, 'employees_roles_end_date'=>$_SESSION['employee_role']->listing[$index]->end_date), array('mPath', 'projects_id'), 'hidden_field');
                                    echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php echo tep_draw_form('delete_entry', tep_href_link(FILENAME_ADMINISTRATION_EMPLOYEES_ROLES)) . tep_create_parameters(array('action'=>'delete_entry', 'employees_roles_id'=>$_SESSION['employee_role']->listing[$index]->id, 'roles_id'=>$_SESSION['employee_role']->listing[$index]->role->id, 'employees_id'=>$_SESSION['employee_role']->listing[$index]->employee->id, 'employees_roles_start_date'=>$_SESSION['employee_role']->listing[$index]->start_date, 'employees_roles_end_date'=>$_SESSION['employee_role']->listing[$index]->end_date), array('mPath', 'projects_id'), 'hidden_field');
                                    echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                                    echo '</form>'; ?>
                                  </td>
                                </tr>
                                <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                              }
                            } else { ?>
                              <tr class="entryListing-odd">
                                <td class="entryListing-data" colspan="5"  style="text-align:center">
                                  <?php echo TEXT_EMPLOYEES_ROLES_LISTING_IS_EMPTY; ?>
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