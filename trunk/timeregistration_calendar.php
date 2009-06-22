<?php
/****************************************************************************
 * CODE FILE   : timeregistration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 18 june 2009
 * Description : Overview of total hours per day in a calender
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
  $_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->id, $_POST['period'], true, 'activities_date asc');

  // Check if there are unconfirmed timesheets available with timesheets_end_date previous to today
  // If so, create an info message
  $oldest_unconfirmed_period = $_SESSION['timesheet']->get_oldest_unconfirmed_period();
  if (tep_not_null($oldest_unconfirmed_period)) {
    $_POST['info_message'] = sprintf(HEADER_INFO_UNCONFIRMED_PERIOD, $oldest_unconfirmed_period);
  }

  // header //
  require(DIR_WS_INCLUDES . 'header.php');?>
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
                  <td class="pageHeading"><?php echo HEADER_TEXT_TIMEREGISTRATION_CALENDAR; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'calendar-64x64.png', HEADER_TEXT_TIMEREGISTRATION_CALENDAR, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="15%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <?php echo tep_draw_form('period_back', tep_href_link(FILENAME_TIMEREGISTRATION_CALENDAR)) . tep_create_parameters(array('period'=>tep_next_period($_SESSION['timesheet']->period, -1)), array('mPath'), 'hidden_field'); ?>
                    <td align="left" class="infoBoxHeading">
                      <?php echo tep_image_submit('arrow_left.gif', TEXT_TIMEREGISTRATION_BACK, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                  <td align="center" class="infoBoxHeading"><?php echo TEXT_TIMEREGISTRATION_PERIOD . $_SESSION['timesheet']->period; ?></td>
                  <?php echo tep_draw_form('period_forward', tep_href_link(FILENAME_TIMEREGISTRATION_CALENDAR)) . tep_create_parameters(array('period'=>tep_next_period($_SESSION['timesheet']->period, 1)), array('mPath'), 'hidden_field'); ?>
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
            <td align="center" class="main"><?php echo TEXT_TIMEREGISTRATION_CALENDAR_DESCRIPTION; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td align="center">
              <!-- large_calendar //-->
              <?php $calendar_start = (int)strftime('%Y%m%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date));
              $calendar_end = (int)strftime('%Y%m%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->end_date));

              // Determine start day index
              $calendar_day_index = (int)strftime('%u', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date)) - 1;
              $calendar_day = (int)strftime('%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date));

              // Get the day totals
              $total_amount_per_day = $_SESSION['timesheet']->get_total_amount_per_day(); ?>

              <table border="0" cellspacing="0" cellpadding="2" class="calendar">
                <tr>
                  <?php $colindex = 0;
                  // First display weekdays on top
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_MONDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_TUESDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_WEDNESDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_THURSDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_FRIDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_SATURDAY . '</td>';
                  echo '<td class="calendar-heading" align="center">' . TEXT_CALENDAR_SUNDAY . '</td>';
                  echo '</tr><tr>';
                  // Walk the cells until the first day is reached
                  for (;$colindex < $calendar_day_index; $colindex++) { ?>
                    <td class="<?php echo ($colindex >= 0 && $colindex <= 4?'large-calendar-notavailable':'large-calendar-notavailable') ?>">&nbsp;</td>
                  <?php }
                  for ($counter = $calendar_start; $counter <= $calendar_end; $counter++, $calendar_day++, $colindex++) {
                    if ($colindex%7 == 0) { ?>
                      </tr>
                      <tr>
                    <?php } ?>
                    <td class="<?php echo ($colindex%7 >= 0 && $colindex%7 <= 4?'large-calendar-weekday':'large-calendar-weekend') ?>">
                      <?php echo '<b>' . $calendar_day . '</b><br>&nbsp;<br>' . ($total_amount_per_day[$calendar_day]!=0?tep_number_db_to_user($total_amount_per_day[$calendar_day], 2):'&nbsp;') . '<br>&nbsp;'; ?>
                    </td>
                  <?php }
                  // Walk the rest of the cells to complete the current row
                  for ($restindex = $colindex%7; $restindex > 0 && $restindex <= 6; $restindex++) { ?>
                    <td class="<?php echo ($restindex >= 0 && $restindex <= 4?'large-calendar-notavailable':'large-calendar-notavailable') ?>">&nbsp;</td>
                  <?php } ?>
                </tr>
              </table>
              <!-- large_calendar_eof //-->            
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