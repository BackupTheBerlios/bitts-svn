<?php
/****************************************************************************
 * CODE FILE   : timeregistration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 14 april 2008
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // application_top //
  require('includes/application_top.php');
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
        <table border="1" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table border="1" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading">HEADING_TITLE</td>
                  <td class="pageHeading" align="right">categories_image, categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td>
              <table border="1" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td>
                    <table border="1" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td align="center" class="smallText" width="20%" valign="top">categories_image, categories_name, SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT<br>categories_name</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                  <td>include DIR_WS_MODULES . FILENAME_NEW_PRODUCTS</td>
                </tr>
              </table>
            </td>
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