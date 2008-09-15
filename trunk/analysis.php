<?php
/****************************************************************************
 * CODE FILE   : analysis.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 15 september 2008
 * Description : Reporting form
 */

  // application_top //
  require('includes/application_top.php');
  // Check if user is logged in. If not, redirect to login page
  if (!tep_not_null($_SESSION['employee_login']))
    tep_redirect(tep_href_link(FILENAME_LOGIN));

  // Set some $_POST values
  $_POST['mPath'] = '31';

  switch (tep_post_or_get('action')) {
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
        <?php echo tep_draw_form('report_employees', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_employees'), array(), 'hidden_field') . tep_href_submit(REPORT_NAME_EMPLOYEES, tep_href_link(FILENAME_ANALYSIS)) . '</form><br>'; ?>
        <?php echo tep_draw_form('report_projects', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_projects'), array(), 'hidden_field') . tep_href_submit(REPORT_NAME_PROJECTS, tep_href_link(FILENAME_ANALYSIS)) . '</form><br>'; ?>
        <?php echo tep_draw_form('report_timesheets', tep_href_link(FILENAME_REPORT), 'post', 'target="_new"') . tep_create_parameters(array('action'=>'report_timesheets'), array(), 'hidden_field') . tep_href_submit(HEADER_TEXT_LOGOUT, tep_href_link(FILENAME_ANALYSIS)) . '</form>'; ?>
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