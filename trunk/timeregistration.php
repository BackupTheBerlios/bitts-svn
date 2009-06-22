<?php
/****************************************************************************
 * CODE FILE   : timeregistration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 21 june 2009
 * Description : Time registration form
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
  if (!$_SESSION['employee']->is_user)
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  // Create a new timesheet object with id == 0
  // If a timesheet already exists for this employee and period, the timesheet class will automatically
  // retrieve the correct id and put this into $_SESSION['timesheet']->id
  if (!tep_not_null($_POST['period'])) {
    $_POST['period'] = tep_datetoperiod();
  }

  // Make sure there is a sort order to start with
  if (!tep_not_null($_POST['sort_order'])) {
    $_POST['sort_order'] = 'activities_date asc';
  }

  $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, $_POST['period'], true, $_POST['sort_order']);

  switch ($_POST['action']) {
    case '':
      $_POST['activity_id'] = 0;
      break;
    case 'save_data':
      if ($_SESSION['timesheet']->id == 0) {
        // Timesheet does not exist yet so a new one has to be created in db
        // This is required for the test if previous unconfirmed timesheets exist
        $_SESSION['timesheet']->save();
      }
      // Adding the activity is handled in activity_entry.php
      break;
    case 'delete_activity':
      break;
    case 'delete_activity_confirmed':
      $_SESSION['timesheet']->delete_activity($_POST['activity_id']);
      $_POST['activity_id'] = 0;
      $_POST['action'] = '';
      // Reload the timesheet object in order to
      // update the activity listing that follows
      $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, $_POST['period'], true, $_POST['sort_order']);
      break;
    case 'timesheet_to_be_confirmed':
      break;
    case 'timesheet_confirmed':
      $_SESSION['timesheet']->confirm();
      $_POST['action'] = '';
      break;
  }

  // Check if there are unconfirmed timesheets available with timesheets_end_date previous to today
  // If so, create an info message
  $oldest_unconfirmed_period = $_SESSION['timesheet']->get_oldest_unconfirmed_period();
  if (tep_not_null($oldest_unconfirmed_period)) {
    $_POST['info_message'] = sprintf(HEADER_INFO_UNCONFIRMED_PERIOD, $oldest_unconfirmed_period);
  }

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_TIMEREGISTRATION; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'journal-64x64.png', HEADER_TEXT_TIMEREGISTRATION, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="15%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <?php echo tep_draw_form('period_back', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('period'=>tep_next_period($_SESSION['timesheet']->period, -1)), array('mPath'), 'hidden_field'); ?>
                    <td align="left" class="infoBoxHeading">
                      <?php echo tep_image_submit('arrow_left.gif', TEXT_TIMEREGISTRATION_BACK, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                  <td align="center" class="infoBoxHeading"><?php echo TEXT_TIMEREGISTRATION_PERIOD . $_SESSION['timesheet']->period; ?></td>
                  <?php echo tep_draw_form('period_forward', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('period'=>tep_next_period($_SESSION['timesheet']->period, 1)), array('mPath'), 'hidden_field'); ?>
                    <td align="right" class="infoBoxHeading">
                      <?php echo tep_image_submit('arrow_right.gif', TEXT_TIMEREGISTRATION_FORWARD, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0px" width="20%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                <tr>
                  <td align="center" class="boxText"><?php echo tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date)) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->end_date)); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <?php if ($_SESSION['timesheet']->locked) {
              echo '<td align="center" class="smallText">' . TEXT_TIMEREGISTRATION_LOCKED;
            } else {
              echo '<td align="center">';
              require(DIR_WS_INCLUDES . 'activity_entry.php');
            } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo tep_draw_form('sort_activities_date', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('sort_order'=>($_POST['sort_order']=='activities_date asc'?'activities_date desc':'activities_date asc')), array('mPath','period'), 'hidden_field') . tep_href_submit(TEXT_ACTIVITY_DAY, 'submitLinkInfoBoxHeading') . ($_POST['sort_order']=='activities_date asc'?tep_image(DIR_WS_IMAGES . 'triangle_down.png'):($_POST['sort_order']=='activities_date desc'?tep_image(DIR_WS_IMAGES . 'triangle_up.png'):tep_draw_separator('pixel_trans.gif', '9', '9'))) . '</form>'; ?></td>
                  <td class="entryListing-heading"><?php echo tep_draw_form('sort_projects_name', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('sort_order'=>($_POST['sort_order']=='projects_name asc'?'projects_name desc':'projects_name asc')), array('mPath','period'), 'hidden_field') . tep_href_submit(TEXT_ACTIVITY_PROJECTNAME, 'submitLinkInfoBoxHeading') . ($_POST['sort_order']=='projects_name asc'?tep_image(DIR_WS_IMAGES . 'triangle_down.png'):($_POST['sort_order']=='projects_name desc'?tep_image(DIR_WS_IMAGES . 'triangle_up.png'):tep_draw_separator('pixel_trans.gif', '9', '9'))) . '</form><br>&nbsp;' . TEXT_ACTIVITY_ROLENAME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_AMOUNT; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_UNIT; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_TRAVELDISTANCE; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_EXPENSES; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_TICKETNUMBER; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_ACTIVITY_COMMENT; ?></td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                  <td width="20" class="entryListing-heading">&nbsp;</td>
                </tr>
                <?php if (!$_SESSION['timesheet']->empty) {
                  $odd_or_even = "odd";
                  for ($index = 0; $index < sizeof($_SESSION['timesheet']->activities); $index++) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>">
                      <td class="entryListing-data"><?php echo strftime('%d', $_SESSION['timesheet']->activities[$index]->date); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->project_name.'<br>'.$_SESSION['timesheet']->activities[$index]->role_name; ?></td>
                      <td class="entryListing-data"><?php echo tep_number_db_to_user($_SESSION['timesheet']->activities[$index]->amount, 2); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->unit_name; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->travel_distance; ?></td>
                      <td class="entryListing-data"><?php echo tep_number_db_to_user($_SESSION['timesheet']->activities[$index]->expenses, 2); ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->ticket_number; ?></td>
                      <td class="entryListing-data"><?php echo $_SESSION['timesheet']->activities[$index]->comment; ?></td>
                      <td align="center" width="20" class="entryListing-data">
                      <?php if (!$_SESSION['timesheet']->locked) {
                        echo tep_draw_form('edit_activity', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'enter_data','selected_date'=>$_SESSION['timesheet']->activities[$index]->date,'projects_id'=>$_SESSION['timesheet']->activities[$index]->projects_id,'roles_id'=>$_SESSION['timesheet']->activities[$index]->roles_id, 'activity_id'=>$_SESSION['timesheet']->activities[$index]->activity_id, 'activity_amount'=>tep_number_db_to_user($_SESSION['timesheet']->activities[$index]->amount, 2), 'original_activity_amount'=>$_SESSION['timesheet']->activities[$index]->amount, 'tariffs_id'=>$_SESSION['timesheet']->activities[$index]->tariff->id, 'activity_travel_distance'=>"".$_SESSION['timesheet']->activities[$index]->travel_distance, 'activity_expenses'=>tep_number_db_to_user($_SESSION['timesheet']->activities[$index]->expenses, 2), 'activity_ticket_number'=>$_SESSION['timesheet']->activities[$index]->ticket_number, 'activity_comment'=>$_SESSION['timesheet']->activities[$index]->comment), array('mPath','period','sort_order'), 'hidden_field');
                        echo tep_image_submit('edit.gif', TEXT_ENTRY_EDIT,'',DIR_WS_IMAGES);
                        echo '</form>';
                      } ?>
                      </td>
                      <td align="center" width="20" class="entryListing-data">
                      <?php if (!$_SESSION['timesheet']->locked) {
                        echo tep_draw_form('delete_activity', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'delete_activity', 'activity_id'=>$_SESSION['timesheet']->activities[$index]->activity_id), array('mPath','period','sort_order'), 'hidden_field');
                        echo tep_image_submit('delete.gif', TEXT_ENTRY_DELETE,'',DIR_WS_IMAGES);
                        echo '</form>';
                      } ?>
                      </td>
                    </tr>
                    <?php if ($_POST['action']=='delete_activity' && $_POST['activity_id']==$_SESSION['timesheet']->activities[$index]->activity_id) { ?>
                      <!-- Show OK and Cancel buttons below the activity-to-be-deleted -->
                      <tr class="entryListing-<?php echo $odd_or_even; ?>">
                        <td align="right" valign="middle" class="entryListing-data" colspan="10">
                          <?php echo TEXT_ENTRY_DELETE_QUESTION; ?>&nbsp;
                          <?php echo tep_draw_form('delete_activity_confirm', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'delete_activity_confirmed'), array('mPath','period','sort_order','activity_id'), 'hidden_field');
                            echo tep_image_submit('button_ok.gif', TEXT_ENTRY_DELETE_OK, 'style="vertical-align:middle"'); ?>
                          </form>&nbsp;
                          <?php echo tep_draw_form('delete_activity_cancel', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array(), array('mPath','period','sort_order'), 'hidden_field');
                            echo tep_image_submit('button_cancel.gif', TEXT_ENTRY_DELETE_CANCEL, 'style="vertical-align:middle"'); ?>
                          </form>
                        </td>
                      </tr>
                    <?php }
                    $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  }
                } else { ?>
                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="10" align="center">
                      <?php echo TEXT_TIMEREGISTRATION_IS_EMPTY; ?>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td align="right" valign="middle" class="entryListing-data">
              <?php if (!$_SESSION['timesheet']->locked && $_POST['action']!='timesheet_to_be_confirmed') {
                // Confirm button enabled
                echo tep_draw_form('pre_confirm_timesheet', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'timesheet_to_be_confirmed'), array('mPath','period','sort_order'), 'hidden_field');
                echo tep_image_submit('button_confirm.gif', TEXT_TIMEREGISTRATION_CONFIRM); ?>
                </form>
              <?php } else if ($_POST['action']=='timesheet_to_be_confirmed') { ?>
                <!-- Show OK and Cancel buttons below the timesheet-to-be-confirmed -->
                <?php echo TEXT_TIMEREGISTRATION_CONFIRM_QUESTION; ?>&nbsp;
                <?php echo tep_draw_form('confirm_timesheet', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('action'=>'timesheet_confirmed'), array('mPath','period','sort_order'), 'hidden_field');
                  echo tep_image_submit('button_ok.gif', TEXT_TIMEREGISTRATION_CONFIRM_OK, 'style="vertical-align:middle"'); ?>
                </form>&nbsp;
                <?php echo tep_draw_form('confirm_timesheet_cancel', tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array(), array('mPath','period','sort_order'), 'hidden_field');
                  echo tep_image_submit('button_cancel.gif', TEXT_TIMEREGISTRATION_CONFIRM_CANCEL, 'style="vertical-align:middle"'); ?>
                </form>
              <?php } else {
                // Confirm button disabled
                echo tep_image(DIR_WS_LANGUAGES . $_SESSION['language'] . '/images/buttons/button_confirm_disabled.gif');
              } ?>
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