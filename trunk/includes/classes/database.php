<?php
/****************************************************************************
 * CLASS FILE  : database.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 17 december 2007
 * Description : Database related functions and selection of proper database
 *               type.
 * 
 *               Framework: osCommerce, Open Source E-Commerce Solutions
 *               http://www.oscommerce.com
 */

  // Require the proper implementation file
  require(DIR_WS_CLASSES . 'database_' . DB_SERVER_TYPE . '.php');

  class database {
  	private $real_database;

  	public function __construct() {
  	  // Select to which type of db to connect to
  	  switch (DB_SERVER_TYPE) {
  	    case 'mysql':
  	      $this->real_database = new database_mysql();
  	      break;
  	  	case 'mssql':
  	      //require(DIR_WS_CLASSES . 'database_mysql.php');
	  	  //$this->real_database = new database_mssql();
  	  	  break;
  	  }
  	}

  	public function __destruct() {
  	}

  	public function connect($db_server = DB_SERVER_NAME, $db_server_username = DB_SERVER_USERNAME, $db_server_password = DB_SERVER_PASSWORD, $db_database = DB_DATABASE_NAME) {
      $this->real_database->connect($db_server, $db_server_username, $db_server_password);

      if ($this->real_database->get_link()) {
      	$this->real_database->select_db($db_database);
      }
      //echo "Link on open: " . $this->real_database->get_link();
      return $this->real_database->get_link();
    }

    public function close() {
      //echo "Link on close: " . $this->real_database->get_link();
      return $this->real_database->close();
    }

    public function error($query, $errno, $error) { 
      die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
    }

    public function query($query) {
      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }

      $result = $this->real_database->query($query) or $this->error($query, $this->real_database->errno(), $this->real_database->error());

      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
         $result_error = $this->real_database->error();
         error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }

      return $result;
    }

    public function perform($table, $data, $action = 'insert', $parameters = '') {
      reset($data);
      if ($action == 'insert') {
        $query = 'insert into ' . $table . ' (';
        while (list($columns, ) = each($data)) {
          $query .= $columns . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while (list(, $value) = each($data)) {
          switch ((string)$value) {
            case 'now()':
              $query .= 'now(), ';
              break;
            case 'null':
              $query .= 'null, ';
              break;
            default:
              $query .= '\'' . input($value) . '\', ';
              break;
          }
        }
        $query = substr($query, 0, -2) . ')';
      } elseif ($action == 'update') {
        $query = 'update ' . $table . ' set ';
        while (list($columns, $value) = each($data)) {
          switch ((string)$value) {
            case 'now()':
              $query .= $columns . ' = now(), ';
              break;
            case 'null':
              $query .= $columns .= ' = null, ';
              break;
            default:
              $query .= $columns . ' = \'' . input($value) . '\', ';
            break;
          }
        }
        $query = substr($query, 0, -2) . ' where ' . $parameters;
      }

      return query($query);
    }

    public function fetch_array($db_query) {
      return $this->real_database->fetch_array($db_query);
    }

    public function num_rows($db_query) {
      return $this->real_database->num_rows($db_query);
    }

    public function data_seek($db_query, $row_number) {
      return $this->real_database->data_seek($db_query, $row_number);
    }

    public function insert_id() {
      return $this->real_database->insert_id();
    }

    public function free_result($db_query) {
      return $this->real_database->free_result($db_query);
    }

    public function fetch_fields($db_query) {
      return $this->real_database->fetch_field($db_query);
    }

    public function output($string) {
      return htmlspecialchars($string);
    }

    public function input($string) {
      return $this->real_database->input($string);
    }

    public function prepare_input($string) {
      if (is_string($string)) {
        return trim(tep_sanitize_string(stripslashes($string)));
      } elseif (is_array($string)) {
        reset($string);
        while (list($key, $value) = each($string)) {
          $string[$key] = prepare_input($value);
        }
        return $string;
      } else {
        return $string;
      }
    }
  }
?>