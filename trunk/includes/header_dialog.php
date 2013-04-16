<?php
/****************************************************************************
 * CODE FILE   : header_dialog.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 april 2013
 * Description : Header file
 *               Contains html declarations and parameters
 *               Simple version for dialogs
 */
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo ((getenv('HTTPS') == 'on') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_APPLICATION; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link rel="shortcut icon" href="<?php echo DIR_WS_IMAGES ?>favicon.ico" type="image/x-icon">
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <table border="0" width="100%" cellspacing="0" cellpadding="1">
      <tr class="headerNavigation">
        <td class="headerNavigation">&nbsp;</td>
      </tr>
    </table>