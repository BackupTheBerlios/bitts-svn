<?php
/****************************************************************************
 * CODE FILE   : calendar.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 june 2009
 * Description : Calendar control
 *
 */

  $calendar_start = (int)strftime('%Y%m%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date));
  $calendar_end = (int)strftime('%Y%m%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->end_date));
  $today = (int)strftime('%Y%m%d');

  // Determine start day index
  $calendar_day_index = (int)strftime('%u', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date)) - 1;
  $calendar_day = (int)strftime('%d', tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date));
?>
  <!-- calendar_control //-->
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
        <td class="<?php echo ($colindex >= 0 && $colindex <= 4?'calendar-notavailable':'calendar-notavailable') ?>" align="center">&nbsp;</td>
      <?php }
      for ($counter = $calendar_start; $counter <= $calendar_end; $counter++, $calendar_day++, $colindex++) {
        if ($colindex%7 == 0) { ?>
          </tr>
          <tr>
        <?php }
        echo tep_draw_form('select_day_'.$calendar_day, tep_href_link(FILENAME_TIMEREGISTRATION)) . tep_create_parameters(array('mPath'=>'21','selected_date'=>tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date, $calendar_day - 1),'action'=>'select_project'), array('period', 'sort_order', 'activity_id'), 'hidden_field'); ?>
          <td class="<?php echo ($counter==$today?'calendar-today':($colindex%7 >= 0 && $colindex%7 <= 4?'calendar-weekday':'calendar-weekend')) ?>" align="center">
            <?php //echo '<a href=' . tep_href_link(FILENAME_TIMEREGISTRATION, tep_create_parameters(array('mPath'=>'21','selected_date'=>tep_datetouts('%Y-%m-%d', $_SESSION['timesheet']->start_date, $calendar_day - 1),'action'=>'select_project'), array('period', 'activity_id'))) . '>' . $calendar_day . '</a>';
            echo tep_href_submit($calendar_day); ?>
          </td>
        </form>
      <?php }
      // Walk the rest of the cells to complete the current row
      for ($restindex = $colindex%7; $restindex > 0 && $restindex <= 6; $restindex++) { ?>
        <td class="<?php echo ($restindex >= 0 && $restindex <= 4?'calendar-notavailable':'calendar-notavailable') ?>" align="center">&nbsp;</td>
      <?php } ?>
    </tr>
  </table>
  <!-- calendar_control_eof //-->