<?php
/****************************************************************************
 * CLASS FILE  : database_interface.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 16 april 2013
 * Description : Interface to database specific functions
 */

  interface database_interface {
    public function connect();
    public function select_db($database);
    public function error();
    public function errno();
    public static function escape_string($string);
    public function query($query);
    public function fetch_array($result, $array_type);
    public function fetch_row($result);
    public function fetch_assoc($result);
    public function fetch_object($result);
    public function num_rows($result);
    public function data_seek($query, $row_number);
    public function insert_id();
    public function free_result($db_query);
    public function fetch_fields($db_query);
    public function input($string);
    public function close();
    public function get_link();
  }
?>