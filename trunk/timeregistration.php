<?php
/****************************************************************************
 * CODE FILE   : timeregistration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 22 april 2008
 * Description : Time registration form
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // application_top //
  require('includes/application_top.php');
  // header //
  require(DIR_WS_INCLUDES . 'header.php');

//  if (!isset($_SESSION['timesheet'])) {
  	$_SESSION['timesheet'] = new timesheet(0, $_SESSION['employee']->employee_id, $_GET['period']);
//  }
?>
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
        <table border="1" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_TIMEREGISTRATION; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'calendar.gif', HEADER_TEXT_YOUR_DATA, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="20%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                      <tr>
                        <td align="left" class="boxText"><?php echo '<a href="' . tep_href_link(FILENAME_TIMEREGISTRATION, 'period=' . tep_next_period($_SESSION['timesheet']->period, -1)) . '">' . tep_image(DIR_WS_IMAGES . 'arrow_left.gif', TEXT_TIMEREGISTRATION_BACK) . '</a>'; ?></td>
                        <td align="center" class="boxText"><?php echo TEXT_TIMEREGISTRATION_PERIOD . $_SESSION['timesheet']->period; ?></td>
                        <td align="right" class="boxText"><?php echo '<a href="' . tep_href_link(FILENAME_TIMEREGISTRATION, 'period=' . tep_next_period($_SESSION['timesheet']->period, 1)) . '">' . tep_image(DIR_WS_IMAGES . 'arrow_right.gif', TEXT_TIMEREGISTRATION_FORWARD) . '</a>'; ?></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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