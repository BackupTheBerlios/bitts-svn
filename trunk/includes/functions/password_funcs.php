<?php
/****************************************************************************
 * CODE FILE   : password_funcs.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 28 november 2007
 * Description : .....
 *               .....
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // Validation of a plain text password against an encrpyted password
  function tep_validate_password($plain_password, $encrypted_password) {
    if (tep_not_null($plain_password) && tep_not_null($encrypted_password)) {
      // split apart the hash / salt
      $stack = explode(':', $encrypted_password);
      if (sizeof($stack) != 2) return false;
      if (md5($stack[1] . $plain_password) == $stack[0]) return true;
    }
    return false;
  }

  // Creation of a new password from a plaintext password
  function tep_encrypt_password($plain_password) {
    $encrypted_password = '';

    for ($i=0; $i<10; $i++) {
      $encrypted_password .= tep_rand();
    }

    $salt = substr(md5($encrypted_password), 0, 2);

    $encrypted_password = md5($salt . $plain_password) . ':' . $salt;

    return $encrypted_password;
  }
?>
