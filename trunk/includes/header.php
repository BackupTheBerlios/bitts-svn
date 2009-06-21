<?php
/****************************************************************************
 * CODE FILE   : header.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 21 june 2009
 * Description : Header file
 *               Contains html declarations and parameters
 *               Creates error- and/or info message on top of page
 * 
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link rel="shortcut icon" href="<?php echo DIR_WS_IMAGES ?>favicon.ico" type="image/x-icon">
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr class="header">
        <td valign="middle"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . COMPANY_BANNER, COMPANY_NAME) . '</a>'; ?></td>
      </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="headerNavigation">
        <td class="headerNavigation">&nbsp;&nbsp;
          <?php if (isset($_SESSION['employee'])) {
            echo HEADER_TEXT_CURRENT_USER . $_SESSION['employee']->fullname . '&nbsp;&nbsp;&nbsp;';
            echo tep_draw_form('logout', tep_href_link(FILENAME_DEFAULT)) . tep_create_parameters(array('action'=>'logout'), null, 'hidden_field') . '[' . tep_href_submit(HEADER_TEXT_LOGOUT, 'style="font-size: 10px; text-align: center; color: #ffffff; font-weight: bold; font-family: Verdana, Arial, sans-serif"') . ']</form>';
          } else {
            echo HEADER_TEXT_NO_CURRENT_USER;
          } ?>
        </td>
        <td align="right" class="headerNavigation">&nbsp;&nbsp;</td>
      </tr>
    </table>

<?php
  if (isset($_POST['error_message']) && tep_not_null($_POST['error_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerError">
        <td class="headerError"><?php echo htmlspecialchars(urldecode($_POST['error_message'])); ?></td>
      </tr>
    </table>
<?php
  }

  if (isset($_POST['info_message']) && tep_not_null($_POST['info_message'])) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerInfo">
        <td class="headerInfo"><?php echo htmlspecialchars($_POST['info_message']); ?></td>
      </tr>
    </table>
<?php
  }
?>