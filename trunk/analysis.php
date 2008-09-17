<?php
/****************************************************************************
 * CODE FILE   : analysis.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 september 2008
 * Description : Reporting form
 */

  // application_top //
  require('includes/application_top.php');
  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee_login']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));

  switch ($_POST['action']) {
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
                  <td class="pageHeading"><?php echo HEADER_TEXT_ANALYSIS; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'presentation-64x64.png', HEADER_TEXT_ANALYSIS, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0" width="15%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <?php // If period is not set, use current period
                  if (!isset($_POST['period'])) {
                    $_POST['period'] = tep_datetoperiod();
                  }
                  echo tep_draw_form('period_back', tep_href_link(FILENAME_ANALYSIS)) . tep_create_parameters(array('period'=>tep_next_period($_POST['period'], -1)), array('mPath'), 'hidden_field'); ?>
                    <td align="left" class="infoBoxHeading">
                      <?php echo tep_image_submit('arrow_left.gif', TEXT_ANALYSIS_BACK, '', DIR_WS_IMAGES); ?>
                    </td>
                  </form>
                  <td align="center" class="infoBoxHeading"><?php echo TEXT_ANALYSIS_PERIOD . $_POST['period']; ?></td>
                  <?php echo tep_draw_form('period_forward', tep_href_link(FILENAME_ANALYSIS)) . tep_create_parameters(array('period'=>tep_next_period($_POST['period'], 1)), array('mPath'), 'hidden_field'); ?>
                    <td align="right" class="infoBoxHeading">
                      <?php echo tep_image_submit('arrow_right.gif', TEXT_ANALYSIS_FORWARD, '', DIR_WS_IMAGES); ?>
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
                  <td align="center" class="boxText"><?php echo tep_strftime(DATE_FORMAT_SHORT, tep_datetouts(tep_periodstartdate($_POST['period']))) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts(tep_periodenddate($_POST['period']))); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
          </tr>
          <tr>
            <td>
              <?php echo tep_draw_form('report_employees', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_employees'), array('period'), 'hidden_field') . tep_href_submit(REPORT_NAME_EMPLOYEES) . '</form><br>'; ?>
              <?php echo tep_draw_form('report_projects', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_projects', 'per_employee'=>'true', 'show_tariff'=>'true'), array('period'), 'hidden_field') . tep_href_submit(REPORT_NAME_PROJECTS) . '</form><br>'; ?>
              <?php echo tep_draw_form('report_timesheets', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_timesheets'), array('period'), 'hidden_field') . tep_href_submit(REPORT_NAME_TIMESHEETS) . '</form>'; ?>
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