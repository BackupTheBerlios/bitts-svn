<?php
/****************************************************************************
 * CODE FILE   : index.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 14 june 2009
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
                        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_IS_USER;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo ($_SESSION['employee']->is_user)?BODY_TEXT_YES:BODY_TEXT_NO;?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_IS_ANALYST;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo ($_SESSION['employee']->is_analyst)?BODY_TEXT_YES:BODY_TEXT_NO;?></td>
                      </tr>
                      <tr>
                        <td align="right" class="boxText"><?php echo BODY_TEXT_IS_ADMINISTRATOR;?></td>
                        <td class="boxText">:</td>
                        <td class="boxText"><?php echo ($_SESSION['employee']->is_administrator)?BODY_TEXT_YES:BODY_TEXT_NO;?></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
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
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="projectListing">
                <tr>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_NAME; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_START_DATE; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_END_DATE; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_CALCULATED_HOURS; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_PERIOD; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_HOURS_USED; ?></td>
                  <td class="projectListing-heading"><?php echo TEXT_PROJECT_HOURS_USED_PERCENTAGE; ?></td>
                </tr>
                <?php $project_array = project::get_project_listing(mktime()); // Current date in Unix timestamp
                for ($index = 0; $index < sizeof($project_array); $index++) {
                  $projects_calculated_hours_used_percentage = ($project_array[$index]['projects_calculated_hours']!=0?round(($project_array[$index]['projects_calculated_hours_used']/$project_array[$index]['projects_calculated_hours'])*100).'%':BODY_TEXT_NOT_APPLICABLE); ?>
                  <tr class="projectListing-<?php echo ($projects_calculated_hours_used_percentage>=100?'red':($projects_calculated_hours_used_percentage>=75?'orange':'green'));?>">
                    <td class="projectListing-data"><?php echo $project_array[$index]['projects_name']; ?></td>
                    <td class="projectListing-data"><?php echo tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($project_array[$index]['projects_start_date'])); ?></td>
                    <td class="projectListing-data"><?php echo ($project_array[$index]['projects_end_date']!='2099-12-31'?tep_strftime(DATE_FORMAT_SHORT, tep_datetouts($project_array[$index]['projects_end_date'])):BODY_TEXT_NOT_APPLICABLE); ?></td>
                    <td class="projectListing-data"><?php echo ($project_array[$index]['projects_calculated_hours']!=0?$project_array[$index]['projects_calculated_hours']:BODY_TEXT_NOT_APPLICABLE); ?></td>
                    <td class="projectListing-data"><?php echo ($project_array[$index]['projects_calculated_hours']!=0?($project_array[$index]['projects_calculated_hours_period']=='E'?TEXT_PROJECT_PERIOD_ENTIREPROJECT:TEXT_PROJECT_PERIOD_BILLINGPERIOD):BODY_TEXT_NOT_APPLICABLE); ?></td>
                    <td class="projectListing-data"><?php echo tep_number_db_to_user($project_array[$index]['projects_calculated_hours_used'], 2); ?></td>
                    <td class="projectListing-data"><?php echo $projects_calculated_hours_used_percentage; ?></td>
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