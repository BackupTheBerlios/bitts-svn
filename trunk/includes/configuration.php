<?php
/****************************************************************************
 * CODE FILE   : configuration.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : Configuration parameters.
 *               Semi-permanent and basic configuration parameters are placed
 *               here.
 * 
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // define the project version
  define('PROJECT_NAME', 'BitTS');
  define('PROJECT_VERSION', 'v0.1a');

  // Define the webserver and path parameters
  define('HTTP_SERVER', 'http://localhost'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://localhost'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for application?
  define('HTTP_COOKIE_DOMAIN', 'localhost');
  define('HTTPS_COOKIE_DOMAIN', 'localhost');
  define('HTTP_COOKIE_PATH', '/bitts/');
  define('HTTPS_COOKIE_PATH', '/bitts/');

  // * DIR_WS_* = Webserver directories (virtual/URL)
  define('DIR_WS_CATALOG', '/bitts/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  // * DIR_FS_* = Filesystem directories (local/physical)
  define('DIR_FS_CATALOG', '/srv/www/htdocs/bitts/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  // define our database connection
  define('DB_SERVER_NAME', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_TYPE', 'mysql'); // eg, mysql - database server type
  define('DB_SERVER_USERNAME', 'bitts_conn');
  define('DB_SERVER_PASSWORD', 'bitts_pwd');
  define('DB_DATABASE_NAME', 'bitts'); // database name
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>