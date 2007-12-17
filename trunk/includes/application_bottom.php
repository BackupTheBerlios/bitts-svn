<?php
/****************************************************************************
 * CODE FILE   : application_bottom.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // Close database
  $database = $_SESSION['database'];
  $database->close();

  // Close session
  session_write_close();
?>