<?php
/****************************************************************************
 * CODE FILE   : punch_clock.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 11 oct 2012
 * Description : Punck Clock form
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
  if (!$_SESSION['employee']->profile->right['timeregistration'])
    tep_redirect(tep_href_link(FILENAME_DEFAULT));

  $database = $_SESSION['database'];

  switch ($_POST['btn']) {
    case '1':
    case '2':
    case '3':
      $punch_clock_activities_id = intval($_REQUEST['btn']);
      break;
    case 'STOP':
      $punch_clock_activities_id = 0;
      break;
    default:
      $idResult = $database->query("SELECT activities_id " .
                                   "FROM punch_clock " .
                                   "WHERE punch_clock_datetime_stop = '0000-00-00' ".
                                     "AND employees_id = " . $_SESSION['employee']->id . " " .
                                   "ORDER BY punch_clock_datetime_start DESC " .
                                   "LIMIT 0, 1;");
      if ($idRow = $database->fetch_array($idResult)) {
        $punch_clock_activities_id = intval($idRow['stopwatch_activities_id']);
      } else { // if ($idRow = $database->fetch_array($idResult))
        $punch_clock_activities_id = 0;
      } // if ($idRow = $database->fetch_array($idResult))
      $database->free_result($idResult);
  } // switch ($_REQUEST['btn'])

  if (isset($_POST['btn']) && ($punch_clock_activities_id > 0 || $_POST['btn'] == 'STOP')) {
    $database->query("UPDATE punch_clock SET punch_clock_datetime_stop = NOW() WHERE punch_clock_datetime_stop = '0000-00-00' AND employees_id = " . $_SESSION['employee']->id . ";");
    if ($punch_clock_activities_id > 0) {
      $database->query("INSERT INTO punch_clock (punch_clock_datetime_start, employees_id, activities_id) VALUES (NOW(), " . $_SESSION['employee']->id . ", " . $punch_clock_activities_id . ");");
    } // if ($punch_clock_activities_id > 0)
  } // if (isset($_POST['btn']) && ($punch_clock_activities_id > 0 || $_POST['btn'] == 'STOP'))

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
                  <td class="pageHeading"><?php echo HEADER_TEXT_PUNCH_CLOCK; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'time_date-64x64.png', HEADER_TEXT_PUNCH_CLOCK, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" cellspacing="0" cellpadding="2" class="calendar">
                <tr>
                  <td style="vertical-align: middle;">
                    <form name="frm1" action="punch_clock.php" method="post">
                      <input name="btn" type="submit" style="width: 50px;" value="1"<?php if ($punch_clock_activities_id == 1) echo ' disabled'; ?>>
                    </form>
                  </td>
                  <td style="width: 100px;<?php if ($punch_clock_activities_id == 1) echo 'background: red;'; ?>"><?php echo 'Project 1'; ?></td>
                </tr>
                <tr>
                  <td style="vertical-align: middle;">
                    <form name="frm2" action="punch_clock.php" method="post">
                      <input name="btn" type="submit" style="width: 50px;" value="2"<?php if ($punch_clock_activities_id == 2) echo ' disabled'; ?>>
                    </form>
                  </td>
                  <td style="width: 100px;<?php if ($punch_clock_activities_id == 2) echo 'background: red;'; ?>"><?php echo 'Project 2'; ?></td>
                </tr>
                <tr>
                  <td style="vertical-align: middle;">
                    <form name="frm3" action="punch_clock.php" method="post">
                      <input name="btn" type="submit" style="width: 50px;" value="3"<?php if ($punch_clock_activities_id == 3) echo ' disabled'; ?>>
                    </form>
                  </td>
                  <td style="width: 100px;<?php if ($punch_clock_activities_id == 3) echo 'background: red;'; ?>"><?php echo 'Project 3'; ?></td>
                </tr>
                <tr>
                  <td style="vertical-align: middle;">
                    <form name="frmStop" action="punch_clock.php" method="post">
                      <input name="btn" type="submit" style="width: 50px;" value="STOP"<?php if ($punch_clock_activities_id == 0) echo ' disabled'; ?>>
                    </form>
                  </td>
                  <td style="width: 100px;">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" cellspacing="0" cellpadding="2" class="entryListing">
                <tr>
                  <td class="entryListing-heading"><?php echo TEXT_PUNCH_CLOCK_START; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PUNCH_CLOCK_STOP; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PUNCH_CLOCK_TIME; ?></td>
                  <td class="entryListing-heading"><?php echo TEXT_PUNCH_CLOCK_ACTIVITY; ?></td>
                </tr>
                <?php $activityDuration = array();
                $logResult = $database->query("SELECT TIME(punch_clock_datetime_start) AS start, " .
                                                "TIME(punch_clock_datetime_stop) AS stop, " .
                                                "TIMEDIFF(punch_clock_datetime_stop, punch_clock_datetime_start) AS duration, " .
                                                "activities_id " .
                                              "FROM punch_clock " .
                                              "WHERE DATE(punch_clock_datetime_start) = DATE(NOW()) " .
                                                "AND punch_clock_datetime_stop != '0000-00-00' " .
                                                "AND employees_id = " . $_SESSION['employee']->id . " " .
                                              "ORDER BY punch_clock_datetime_start;");

                if ($database->num_rows($logResult) != 0) {
                  $odd_or_even = "odd";
                  while ($logRow = $database->fetch_array($logResult)) { ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>">
                      <td class="entryListing-data" align="right"><?php echo $logRow['start']; ?></td>
                      <td class="entryListing-data" align="right"><?php echo $logRow['stop']; ?></td>
                      <td class="entryListing-data" align="right"><?php echo $logRow['duration']; ?></td>
                      <td class="entryListing-data" align="center"><?php echo $logRow['activities_id']; ?></td>
                    </tr>
                    <?php $timeArray = explode(':', $logRow['duration']);
                    $seconds = (60 * 60 * intval($timeArray[0])) + (60 * intval($timeArray[1])) + intval($timeArray[2]);
                    $activityDuration[$logRow['activities_id']] += $seconds;
                    $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  } // while ($logRow = $database->fetch_array($logResult)) ?>

                  <tr>
                    <td class="entryListing-heading" colspan="4" align="center"><?php echo TEXT_PUNCH_CLOCK_TOTAL; ?></td>
                  </tr>

                  <?php $odd_or_even = "odd";
                  foreach ($activityDuration AS $activity=>$seconds) {
                    $hours = floor($seconds / 3600);
                    $seconds -= ($hours * 3600);
                    $minutes = floor($seconds / 60);
                    $seconds -= ($minutes * 60); ?>
                    <tr class="entryListing-<?php echo $odd_or_even; ?>">
                      <td class="entryListing-data" colspan="2">&nbsp;</td>
                      <td class="entryListing-data" align="right"><?php echo strval($hours) . ':' . str_pad(strval($minutes), 2, '0', STR_PAD_LEFT) . ':' . str_pad(strval($seconds), 2, '0', STR_PAD_LEFT); ?></td>
                      <td class="entryListing-data" align="center"><?php echo $activity; ?></td>
                    </tr>
                    <?php $odd_or_even = ($odd_or_even == 'odd'?'even':'odd');
                  } // foreach ($activityDuration AS $activity=>$duration)

                } else { // if ($database->num_rows() != 0) ?>

                  <tr class="entryListing-odd">
                    <td class="entryListing-data" colspan="4" align="center">
                      <?php echo TEXT_PUNCH_CLOCK_IS_EMPTY; ?>
                    </td>
                  </tr>

                <?php } // if ($database->num_rows() != 0)
                $database->free_result($logResult); ?>

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