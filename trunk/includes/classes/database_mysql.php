<?php
/****************************************************************************
 * CLASS FILE  : database_mysql.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : Mysql server implementation of the database interface
 */

  // Requires interface database_interface
  require(DIR_WS_CLASSES . 'database_interface.php');

  class database_mysql implements database_interface {
    private $link;

  	public function connect($server='', $username='', $password='', $new_link=true, $client_flags=0) {
      $this->link = mysql_connect($server, $username, $password, $new_link, $client_flags);
      //echo '$link value on connect(): ' . $this->link . '<br>';
    }

    public function select_db($database) {
      mysql_select_db($database);
    }

    public function error() {
      return mysql_error($this->link);
    }

    public function errno() {
      return mysql_errno($this->link);
    }

    public static function escape_string($string) {
      return mysql_real_escape_string($string);
    }

    public function query($query) {
      return mysql_query($query, $this->link);
    }

    public function fetch_array($result, $array_type = MYSQL_ASSOC) {
      return mysql_fetch_array($result, $array_type);
    }

    public function fetch_row($result) {
      return mysql_fetch_row($result);
    }

    public function fetch_assoc($result) {
      return mysql_fetch_assoc($result);
    }

    public function fetch_object($result) { 
      return mysql_fetch_object($result);
    }

    public function num_rows($result) {
      return mysql_num_rows($result);
    }

    public function data_seek($query, $row_number) {
      return mysql_data_seek($query, $row_number);
    }

    public function insert_id() {
      return mysql_insert_id();
    }

    public function free_result($db_query) {
      return mysql_free_result($db_query);
    }

    public function fetch_fields($db_query) {
      return mysql_fetch_field($db_query);
    }

    public function input($string) {
      if (function_exists('mysql_real_escape_string')) {
        return mysql_real_escape_string($string, $this->link);
      } elseif (function_exists('mysql_escape_string')) {
        return mysql_escape_string($string);
      }
      return addslashes($string);
    }

    public function close() {
      //echo '$link value on close(): ' . $this->link . '<br>';
      return mysql_close($this->link);
    }

    public function get_link() {
      return $this->link;
    }
  }
?>