<?php
/****************************************************************************
 * CODE FILE   : sessions.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 11 december 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $database = new database();
      $database->connect();
      $value_query = $database->query("select sessions_value from " . TABLE_SESSIONS . " where sessions_key = '" . $database->input($key) . "' and sessions_expiry > '" . time() . "'");
      $value = $database->fetch_array($value_query);
      $database->close();

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $database = new database();
      $database->connect();
      $check_query = $database->query("select count(*) as total from " . TABLE_SESSIONS . " where sessions_key = '" . $database->input($key) . "'");
      $check = $database->fetch_array($check_query);

      if ($check['total'] > 0) {
        $result = $database->query("update " . TABLE_SESSIONS . " set sessions_expiry = '" . $database->input($expiry) . "', sessions_value = '" . $database->input($value) . "' where sessions_key = '" . $database->input($key) . "'");
      } else {
        $result = $database->query("insert into " . TABLE_SESSIONS . " values ('" . $database->input($key) . "', '" . $database->input($expiry) . "', '" . $database->input($value) . "')");
      }
      $database->close();
      return $result;
    }

    function _sess_destroy($key) {
      $database = new database();
      $database->connect();
      $result = $database->query("delete from " . TABLE_SESSIONS . " where sessions_key = '" . tep_db_input($key) . "'");
      $database->close();
      return $result;
    }

    function _sess_gc($maxlifetime) {
      $database = new database();
      $database->connect();
      $database->query("delete from " . TABLE_SESSIONS . " where sessions_expiry < '" . time() . "'");
      $database->close();
      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function tep_session_start() {
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS;

    $sane_session_id = true;

    if (isset($HTTP_GET_VARS[tep_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_GET_VARS[tep_session_name()]) == false) {
        unset($HTTP_GET_VARS[tep_session_name()]);

        $sane_session_id = false;
      }
    } elseif (isset($HTTP_POST_VARS[tep_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_POST_VARS[tep_session_name()]) == false) {
        unset($HTTP_POST_VARS[tep_session_name()]);

        $sane_session_id = false;
      }
    } elseif (isset($HTTP_COOKIE_VARS[tep_session_name()])) {
      if (preg_match('/^[a-zA-Z0-9]+$/', $HTTP_COOKIE_VARS[tep_session_name()]) == false) {
        $session_data = session_get_cookie_params();

        setcookie(tep_session_name(), '', time()-42000, $session_data['path'], $session_data['domain']);

        $sane_session_id = false;
      }
    }

    if ($sane_session_id == false) {
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    return session_start();
  }

  function tep_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
      return session_register($variable);
    } else {
      return false;
    }
  }

  function tep_session_is_registered($variable) {
    return session_is_registered($variable);
  }

  function tep_session_unregister($variable) {
    return session_unregister($variable);
  }

  function tep_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function tep_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function tep_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }

  function tep_session_destroy() {
    return session_destroy();
  }

  function tep_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function tep_session_recreate() {
    if (PHP_VERSION >= 4.1) {
      $session_backup = $_SESSION;

      unset($_COOKIE[tep_session_name()]);

      tep_session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      tep_session_start();

      $_SESSION = $session_backup;
      unset($session_backup);
    }
  }
?>
