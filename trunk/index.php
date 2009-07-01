<?php
/****************************************************************************
 * CODE FILE   : index.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 01 july 2009
 * Description : Default (starting)page
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // application_top //
  require('includes/application_top.php');
  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee_login']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));
  // header //
  require(DIR_WS_INCLUDES . 'header.php');

  if (!tep_not_null($_POST['period'])) {
    $_POST['period'] = tep_datetoperiod();
  }
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
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_YOUR_DATA; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checklist-64x64.png', HEADER_TEXT_YOUR_DATA, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td align="center">
              <table border="0px" width="20%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <td>
                    <table border="0px" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_LOGIN;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo $_SESSION['employee']->login;?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_FULLNAME;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo $_SESSION['employee']->fullname;?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_EMPLOYEE_ID;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo $_SESSION['employee']->id;?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_PROFILE;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo $_SESSION['employee']->profile->name;?></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <?php if ($_SESSION['employee']->profile->right['projectlisting']) { ?>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo HEADER_TEXT_CURRENT_PROJECTS; ?></td>
                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td align="center">
                <table border="0" width="15%" cellspacing="0" cellpadding="1" class="infoBox">
                  <tr>
                    <?php echo tep_draw_form('period_back', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('period'=>tep_next_period($_POST['period'], -1)), array('mPath'), 'hidden_field'); ?>
                      <td align="left" class="infoBoxHeading">
                        <?php echo tep_image_submit('arrow_left.gif', TEXT_TIMEREGISTRATION_BACK, '', DIR_WS_IMAGES); ?>
                      </td>
                    </form>
                    <td align="center" class="infoBoxHeading"><?php echo TEXT_TIMEREGISTRATION_PERIOD . $_POST['period']; ?></td>
                    <?php echo tep_draw_form('period_forward', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('period'=>tep_next_period($_POST['period'], 1)), array('mPath'), 'hidden_field'); ?>
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
                    <td align="center" class="boxText"><?php echo tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', tep_periodstartdate($_POST['period']))) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', tep_periodenddate($_POST['period']))); ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
            </tr>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="2" class="projectListing">
                  <tr>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_NAME; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_START_DATE; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_END_DATE; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_CALCULATED_HOURS; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_CALCULATED_HOURS_PERIOD; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_HOURS_USED; ?></td>
                    <td class="projectListing-heading"><?php echo TEXT_PROJECTS_HOURS_USED_PERCENTAGE; ?></td>
                  </tr>
                  <?php $project_array = project::get_project_listing(tep_datetouts('%Y-%m-%d', tep_periodstartdate($_POST['period'])), tep_datetouts('%Y-%m-%d', tep_periodenddate($_POST['period']))); // Period end date in Unix timestamp
                  for ($index = 0; $index < sizeof($project_array); $index++) {
                    $projects_calculated_hours_used_percentage = ($project_array[$index]['projects_calculated_hours']!=0?round(($project_array[$index]['projects_calculated_hours_used']/$project_array[$index]['projects_calculated_hours'])*100).'%':BODY_TEXT_NOT_APPLICABLE); ?>
                    <tr class="projectListing-<?php echo ($projects_calculated_hours_used_percentage>=100?'red':($projects_calculated_hours_used_percentage>=75?'orange':'green'));?>">
                      <td class="projectListing-data"><?php echo $project_array[$index]['projects_name']; ?></td>
                      <td class="projectListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $project_array[$index]['projects_start_date'])); ?></td>
                      <td class="projectListing-data"><?php echo ($project_array[$index]['projects_end_date']!='2099-12-31'?tep_strftime(DATE_FORMAT_SHORT, tep_datetouts('%Y-%m-%d', $project_array[$index]['projects_end_date'])):BODY_TEXT_NOT_APPLICABLE); ?></td>
                      <td class="projectListing-data"><?php echo ($project_array[$index]['projects_calculated_hours']!=0?$project_array[$index]['projects_calculated_hours']:BODY_TEXT_NOT_APPLICABLE); ?></td>
                      <td class="projectListing-data"><?php echo ($project_array[$index]['projects_calculated_hours']!=0?$PROJECTS_CALCULATED_HOURS_PERIOD[$project_array[$index]['projects_calculated_hours_period']]:BODY_TEXT_NOT_APPLICABLE); ?></td>
                      <td class="projectListing-data"><?php echo tep_number_db_to_user($project_array[$index]['projects_calculated_hours_used'], 2); ?></td>
                      <td class="projectListing-data"><?php echo $projects_calculated_hours_used_percentage; ?></td>
                    </tr>
                  <?php } ?>
                </table>
              </td>
            </tr>
          <?php } ?>
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