<?php
/****************************************************************************
 * CODE FILE   : administration_timesheets.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 29 june 2009
 * Description : Timesheet administration form
 *               (Un)locking timesheets
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
  if (!$_SESSION['employee']->employee_right->right['administration'])
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  switch ($_POST['action']) {
    case 'unlock_timesheet':
      $administration_timesheet = new timesheet($_POST['timesheets_id'], 0, null, false);
      $administration_timesheet->unlock();
      break;
    case 'lock_timesheet':
      $administration_timesheet = new timesheet($_POST['timesheets_id'], 0, null, false);
      $administration_timesheet->confirm();
      break;
  }

  // Clear all values except mPath and employees_id
  foreach($_POST as $key=>$value) {
    if ($key != 'mPath' && $key != 'employees_id') {
      unset($_POST[$key]);
    }
  }

  // Create a new timesheet object with id == 0 (default)
  $_SESSION['timesheet'] = new timesheet(0, $_POST['employees_id']);

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ADMINISTRATION_TIMESHEETS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'journal-64x64.png', HEADER_TEXT_ADMINISTRATION_TIMESHEETS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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
                        <td class="item_entry" style="text-align:center"><?php echo TEXT_EMPLOYEES; ?></td>
                      </tr>
                      <tr>
                        <td class="item_entry">
                          <?php echo tep_draw_form('employee_selection', tep_href_link(FILENAME_ADMINISTRATION_TIMESHEETS)) . tep_create_parameters(array(), array('mPath'), 'hidden_field');
                          $temp_employee = new employee();
                          echo tep_html_select('employees_id', tep_get_partial_array($temp_employee->listing, 'id', 'fullname'), true, (tep_not_null($_POST['employees_id'])?$_POST['employees_id']:'select_none'), 'onChange="this.form.submit();" size="'.(sizeof($temp_employee->listing)>1?(sizeof($temp_employee->listing)<25?sizeof($temp_employee->listing):25):2).'" style="width:175pt"'); ?>
                          </form>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td style="padding:0"><?php echo tep_draw_separator('pixel_trans.gif', '10', '100%'); ?></td>
                  <td style="width:100%;vertical-align:top;padding:0">
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" width="100%" cellspacing="0" cellpadding="2" class="entryListing">
                            <tr style="vertical-align:top">
                              <td class="entryListing-heading"><?php echo TEXT_TIMESHEETS_PERIOD; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_TIMESHEETS_START_DATE; ?></td>
                              <td class="entryListing-heading"><?php echo TEXT_TIMESHEETS_END_DATE; ?></td>
                              <td class="entryListing-heading" style="width:75px;text-align:center"><?php echo TEXT_TIMESHEETS_UNCONFIRMED; ?></td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                              <td class="entryListing-heading" style="width:20px">&nbsp;</td>
                            </tr>
                            <?php if (tep_not_null($_POST['employees_id']) && !$_SESSION['timesheet']->listing_empty) {
                              $odd_or_even = "odd";
                              for ($index = 0; $index < sizeof($_SESSION['timesheet']->listing); $index++) { ?>
                                <tr class="entryListing-<?php echo $odd_or_even; ?>" style="text-align:top">
                                  <td class="entryListing-data"><?php echo $_SESSION['timesheet']->listing[$index]->period; ?></td>
                                  <td class="entryListing-data"><?php echo $_SESSION['timesheet']->listing[$index]->start_date; ?></td>
                                  <td class="entryListing-data"><?php echo $_SESSION['timesheet']->listing[$index]->end_date; ?></td>
                                  <td class="entryListing-data" style="width:75px;text-align:center">
                                    <?php if(!$_SESSION['timesheet']->listing[$index]->locked) {
                                      echo tep_image(DIR_WS_IMAGES . 'refresh.png', TEXT_TIMESHEETS_UNCONFIRMED, null, null, 'style="vertical-align:middle"');
                                    } else {
                                      echo tep_draw_separator('pixel_trans.gif', '16', '16');
                                    } ?>
                                  </td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php if ($_SESSION['timesheet']->listing[$index]->locked) {
                                      echo tep_draw_form('unlock_timesheet', tep_href_link(FILENAME_ADMINISTRATION_TIMESHEETS)) . tep_create_parameters(array('action'=>'unlock_timesheet', 'timesheets_id'=>$_SESSION['timesheet']->listing[$index]->id), array('mPath', 'employees_id'), 'hidden_field');
                                      echo tep_image_submit('play.png', TEXT_TIMESHEETS_UNLOCK,'',DIR_WS_IMAGES);
                                      echo '</form>';
                                    } else {
                                      echo tep_draw_separator('pixel_trans.gif', '16', '16');
                                    } ?>
                                  </td>
                                  <td class="entryListing-data" style="width:20px;text-align:center">
                                    <?php if (!$_SESSION['timesheet']->listing[$index]->locked) {
                                      echo tep_draw_form('lock_timesheet', tep_href_link(FILENAME_ADMINISTRATION_TIMESHEETS)) . tep_create_parameters(array('action'=>'lock_timesheet', 'timesheets_id'=>$_SESSION['timesheet']->listing[$index]->id), array('mPath', 'employees_id'), 'hidden_field');
                                      echo tep_image_submit('stop.png', TEXT_TIMESHEETS_LOCK,'',DIR_WS_IMAGES);
                                      echo '</form>';
                                    } else {
                                      echo tep_draw_separator('pixel_trans.gif', '16', '16');
                                    } ?>
                                  </td>
                                </tr>
                                <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                              }
                            } else { ?>
                              <tr class="entryListing-odd">
                                <td class="entryListing-data" colspan="6"  style="text-align:center">
                                  <?php echo TEXT_TIMESHEETS_LISTING_IS_EMPTY; ?>
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