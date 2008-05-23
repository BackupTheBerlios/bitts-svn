<?php
/****************************************************************************
 * CODE FILE   : login.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 23 may 2008
 * Description : Login screen
 *
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // application_top //
  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'process_login':
        if (employee::verify_login($_POST['login'], $_POST['password'])) {
          $_SESSION['employee_login'] = $_POST['login'];
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        } else {
          $action = 'login_failed';
        }
        break;
    }
  }

  // header //
  require(DIR_WS_INCLUDES . 'header.php');
echo $action;
?>
<!-- body //-->
  <table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
      <!-- body_text //-->
      <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADER_TEXT_LOGIN; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'key.gif', HEADER_TEXT_LOGIN, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '50'); ?></td>
          </tr>
          <tr>
            <td align="center">
              <table border="0px" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <td>
                    <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process_login')) ?>
                      <table border="0px" width="100%" cellspacing="0" cellpadding="3" class="infoBoxContents">
                        <tr>
                          <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                        </tr>
                        <tr>
                          <td><?php echo tep_draw_separator('pixel_trans.gif', '10'); ?></td>
                          <td class="boxText"><?php echo BODY_TEXT_LOGIN . ' : ';?></td>
                          <td class="boxText"><?php echo tep_draw_input_field('login', '', 'size="1" style="width: 150px"', 'text'); ?></td>
                          <td class="boxText">&nbsp;</td>
                          <td><?php echo tep_draw_separator('pixel_trans.gif', '10'); ?></td>
                        </tr>
                        <tr>
                          <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                        </tr>
                        <tr>
                          <td><?php echo tep_draw_separator('pixel_trans.gif', '10'); ?></td>
                          <td class="boxText"><?php echo BODY_TEXT_PASSWORD . ' : ';?></td>
                          <td class="boxText"><?php echo tep_draw_input_field('password', '', 'size="1" style="width: 150px"', 'password'); ?></td>
                          <td class="boxText"><?php echo tep_image_submit('button_login.gif', HEADER_TEXT_LOGIN); ?></td>
                          <td><?php echo tep_draw_separator('pixel_trans.gif', '10'); ?></td>
                        </tr>
                        <tr>
                          <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
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
<?php echo tep_javascript_focus('login', 'login'); ?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<!-- application_bottom //-->
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<!-- application_bottom_eof //-->