<?php
/****************************************************************************
 * CLASS FILE  : csv.php
 * Project     : BitTS - BART it TimeSheet
 * Author(s)   : Erwin Beukhof
 * Date        : 24 september 2008
 * Description : CSV class
 */

  class CSV {
    private $name, $buffer = '', $field_delimiter, $text_delimiter;

    public function __construct($name = 'default.csv', $field_delimiter = ',', $text_delimiter = '"') {
      $this->name = $name;
      $this->field_delimiter = $field_delimiter;
      $this->text_delimiter = $text_delimiter;
    }

    public function addrow($row = array()) {
      for ($index = 0; $index < sizeof($row); $index++) {
        $this->buffer .= ($index > 0?$this->field_delimiter:'') . $this->text_delimiter . $row[$index] . $this->text_delimiter;
      }
      $this->buffer .= "\n";
    }

    public function output($destination = '') {
      //Output CSV to some destination
      $destination = strtoupper($destination);
      if($destination == '') {
        if($this->name == '') {
          $this->name = 'default.csv';
          $destination = 'I';
        } else {
          $destination = 'F';
        }
      }
      switch($destination) {
        case 'I': // Still needs some work
          //Send to standard output
          //We send to a browser
          header('Content-Type: application/csv');
          header('Content-Length: '.strlen($this->buffer));
          header('Content-Disposition: inline; filename="'.$name.'"');
          header('Cache-Control: private, max-age=0, must-revalidate');
          header('Pragma: public');
          echo $this->buffer;
          break;
        case 'D':
          //Download file
          header('Content-Type: application/octect-stream');
          header('Content-Length: ' . strlen($this->buffer));
          header('Content-Disposition: attachment; filename="' . $this->name . '"');
          header('Cache-Control: private, max-age=0, must-revalidate');
          header('Pragma: public');
          echo $this->buffer;
          break;
        case 'F': // Still needs some work
          //Save to local file
          $f=fopen($name,'wb');
          if(!$f)
            $this->Error('Unable to create output file: '.$name);
          fwrite($f,$this->buffer,strlen($this->buffer));
          fclose($f);
          break;
        case 'S':
          //Return as a string
          return $this->buffer;
        default:
          //$this->Error('Incorrect output destination: '.$dest);
      }
      return '';
    }
  }
?>