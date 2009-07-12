<?php
/****************************************************************************
 * CODE FILE   : upload.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 12 july 2009
 * Description : Upload class
 */

  class upload {
    private $uploadelementname, $file, $destination, $permissions, $allowed_extensions;

    public function __construct($uploadelementname = '', $destination = '', $permissions = '777', $allowed_extensions = '') {
      $this->uploadelementname = $uploadelementname;
      $this->destination = $destination;
      $this->permissions = octdec($permissions);
      $this->__set('allowed_extensions', $allowed_extensions);
    }

    public function __get($varname) {
      switch ($varname) {
        case 'filename':
          return $this->file['name'];
      }
      return null;
    }

    public function __set($varname, $value) {
      switch ($varname) {
        case 'uploadelementname':
          $this->uploadelementname = $value;
          break;
        case 'destination':
          $this->destination = $value;
          break;
        case 'permissions':
          $this->permissions = octdec($value);
          break;
        case 'allowed_extensions':
          if (tep_not_null($value)) {
            if (is_array($value)) {
              $this->allowed_extensions = $value;
            } else {
              $this->allowed_extensions = array($value);
            }
          } else {
            $this->allowed_extensions = array();
          }
          break;
      }
    }

    public function parse() {
      if (isset($_FILES[$this->uploadelementname])) {
        $file = array('name' => $_FILES[$this->uploadelementname]['name'],
                      'type' => $_FILES[$this->uploadelementname]['type'],
                      'size' => $_FILES[$this->uploadelementname]['size'],
                      'tmp_name' => $_FILES[$this->uploadelementname]['tmp_name']);
      }

      echo 'about to check extension array size';
      if ( tep_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        echo 'check extension array size';
        if (sizeof($this->allowed_extensions) > 0) {
          echo 'check extension itself';
          if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->allowed_extensions)) {
            // Filetype not allowed
            return 2;
          }
        }

        $this->file = $file;

        return $this->check_destination();
      } else {
        // No file uploaded
        return 5;
      }
    }

    public function save() {
      if (substr($this->destination, -1) != '/') {
        $this->destination .= '/';
      }

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->file['name'])) {
        // Temporarily disable error reporting because the chmod() operation
        // can fail. This happens when we are trying to chmod() a file that
        // resides on a non *nix filesystem
        $previous_error_reporting_level = error_reporting(0);
        // Fire che chmod() operation
        chmod($this->destination . $this->file['name'], $this->permissions);
        // And put the error reporting level back to where it was
        error_reporting($previous_error_reporting_level);
        // File saved successfully
        return true;
      } else {
        // File not saved
        return false;
      }
    }

    private function check_destination() {
      if (!is_writeable($this->destination)) {
        if (is_dir($this->destination)) {
          // Destination not writeable
          return 3;
        } else {
          // Destination does not exist
          return 4;
        }
      } else {
        return 0;
      }
    }
  }
?>