<?php
/****************************************************************************
 * CLASS FILE  : pdf.php
 * Project     : BitTS - BART it TimeSheet
 * Auteur(s)   : Erwin Beukhof
 * Datum       : 19 september 2008
 * Beschrijving: FPDF wrapper class with pre-formatting
 */

  require('fpdf.php');

  class PDF extends FPDF {
    private $per_employee, $show_tariff, $show_travel_distance, $show_expenses, $show_ticket_number;

    //public function Header() {
      //$this->Image(DIR_WS_IMAGES . COMPANY_BANNER, null, null, 40);
      //Arial bold 15
      //$this->SetFont('Arial', 'B', 15);
      //Calculate width of title and position
      //$width = $this->GetStringWidth($this->title)+6;
      //$this->SetX(($this->w-$width)/2);
      //Colors of frame, background and text
      //$this->SetDrawColor(0, 0, 0);
      //$this->SetFillColor(255, 255, 255);
      //$this->SetTextColor(0, 0, 0);
      //Thickness of frame (1 mm)
      //$this->SetLineWidth(1);
      //Title
      //$this->Cell($width, 9, $this->title, 1, 1, 'C', true);
      //Line break
      //$this->Ln(4);
    //}

    public function Footer() {
      //Position at 1.5 cm from bottom
      $this->SetY(-15);
      //Arial italic 8
      $this->SetFont('Arial','I',8);
      //Text color in gray
      $this->SetTextColor(128);
      //Software title
      $this->Cell(0, 5, TITLE, 0, 0, 'C');
    }

    public function InvoiceHeader($business_unit_image, $customer_name, $period, $project_name, $role_name, $employee_name = '') {
      if ($business_unit_image!='') {
        $this->Image(DIR_WS_IMAGES . $business_unit_image, null, null, 0, 20); // Logo area height == 20
        $this->Ln(6);
      }
      $this->SetDrawColor(0, 0, 0);
      $this->SetTextColor(0, 0, 0);
      $this->SetLineWidth(.3);
      $this->SetFont('Arial', '', 12);
      $this->Cell(30, 6, REPORT_TEXT_CUSTOMER_NAME, 0, 0, 'L');
      $this->Cell(50, 6, $customer_name, 0, 0, 'L');
      $this->Ln();
      $this->Cell(30, 6, REPORT_TEXT_PERIOD, 0, 0, 'L');
      $this->Cell(50, 6, $period, 0, 0, 'L');
      $this->Ln();
      $this->Cell(30, 6, REPORT_TEXT_PROJECT_NAME, 0, 0, 'L');
      $this->Cell(50, 6, $project_name, 0, 0, 'L');
      $this->Ln();
      $this->Cell(30, 6, REPORT_TEXT_ROLE_NAME, 0, 0, 'L');
      $this->Cell(50, 6, $role_name, 0, 0, 'L');
      $this->Ln();
      if (employee_name != '') {
        $this->Cell(30, 6, REPORT_TEXT_EMPLOYEE_NAME, 0, 0, 'L');
        $this->Cell(50, 6, $employee_name, 0, 0, 'L');
        $this->Ln();
      }
      $this->Ln(6);
    }

    public function InvoiceTableHeader($table_header_text = '', $per_employee = true, $show_tariff = true, $show_travel_distance = true, $show_expenses = true, $show_ticket_number = true) {
      $this->per_employee = $per_employee;
      $this->show_tariff = $show_tariff;
      $this->show_travel_distance = $show_travel_distance;
      $this->show_expenses = $show_expenses;
      $this->show_ticket_number = $show_ticket_number;
      $this->SetFont('Arial', 'B', 10);
      $this->SetFillColor(191, 191, 191);
      if ($table_header_text!='') {
        $this->Cell(0, 5, $table_header_text, 0, 1, 'C', true);
      }
      $this->Cell(20, 5, REPORT_TABLE_HEADER_DATE, 'LR', 0, 'C', true);
      if (!$this->per_employee) {
        $this->Cell(50, 5, REPORT_TABLE_HEADER_EMPLOYEE_NAME, 'LR', 0, 'C', true);
      }
      $this->Cell(20, 5, REPORT_TABLE_HEADER_ACTIVITY_AMOUNT, 'LR', 0, 'C', true);
      if (!$this->per_employee) {
        $this->Cell(64, 5, REPORT_TABLE_HEADER_UNITS_NAME, 'LR', 0, 'C', true);
        if ($this->show_tariff) {
          $this->Cell(22, 5, REPORT_TABLE_HEADER_TARIFF, 'L', 0, 'C', true);
        }
      }
      if ($this->show_travel_distance) {
        $this->Cell(22, 5, REPORT_TABLE_HEADER_TRAVEL_DISTANCE, 'LR', 0, 'C', true);
      }
      if ($this->show_expenses) {
        $this->Cell(22, 5, REPORT_TABLE_HEADER_EXPENSES, 'LR', 0, 'C', true);
      }
      if ($this->show_ticket_number) {
        $this->Cell(35, 5, REPORT_TABLE_HEADER_TICKET_NUMBER, 'LR', 0, 'C', true);
      }
      if ($this->show_tariff) {
        $this->Cell(22, 5, REPORT_TABLE_HEADER_TOTAL, 'L', 0, 'C', true);
      }
      // Fill the rest of the available space (277 mm -/- cell widths)
      $this->Cell(0, 5, '', 'L', 1, 'C', true);
    }

    public function InvoiceTableContents($date, $employee_name, $activity_amount, $units_name = '', $tariff = 0.00, $travel_distance = 0, $expenses = 0.00, $ticket_number = '', $total_value = 0.00) {
      $this->SetFont('Arial', '', 10);
      $this->Cell(20, 5, tep_strftime(DATE_FORMAT_SHORT, $date), 0, 0, 'C');
      if (!$this->per_employee) {
        $this->Cell(50, 5, $employee_name);
      }
      $this->Cell(20, 5, tep_number_db_to_user($activity_amount, 2), 0, 0, 'R');
      if (!$this->per_employee) {
        $this->Cell(64, 5);
        if ($this->show_tariff) {
          $this->Cell(22, 5, tep_number_db_to_user($tariff, 2), 0, 0, 'R');
        }
      }
      if ($this->show_travel_distance) {
        $this->Cell(22, 5, $travel_distance, 0, 0, 'R');
      }
      if ($this->show_expenses) {
        $this->Cell(22, 5, tep_number_db_to_user($expenses, 2), 0, 0, 'R');
      }
      if ($this->show_ticket_number) {
        $this->Cell(35, 5, $ticket_number, 0, 0, 'C');
      }
      if ($this->show_tariff) {
        $this->Cell(22, 5, tep_number_db_to_user($total_value, 2), 0, 0, 'R');
      }
      $this->Ln();
    }

    public function InvoiceTableFooter($total_amount, $total_travel_distance = 0, $total_expenses = 0.00, $total_value = 0.00) {
      $this->SetLineWidth(.3);
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(20, 5);
      if (!$this->per_employee) {
        $this->Cell(50, 5);
      }
      $this->Cell(20, 5, tep_number_db_to_user($total_amount, 2), 'T', 0, 'R');
      if ($this->show_travel_distance) {
        $this->Cell(22, 5, $total_travel_distance, 'T', 0, 'R');
      }
      if ($this->show_expenses) {
        $this->Cell(22, 5, tep_number_db_to_user($total_expenses, 2), 'T', 0, 'R');
      }
      if ($this->show_ticket_number) {
        $this->Cell(35, 5);
      }
      if ($this->show_tariff) {
        $this->Cell(22, 5, tep_number_db_to_user($total_value, 2), 'T', 0, 'R');
      }
      $this->Ln();
    }

    public function InvoiceSignature() {
      $this->SetLineWidth(.3);
      $this->SetFont('Arial', '', 12);
      $signature_width = 60;
      $signature_height = 41; // Keep a margin of 8
      $x_pos = $this->w - $this->rMargin - $signature_width;
      // Determine if the signature cells fit on the page
      if (($this->h - $this->x - $this->bMargin) < $signature_height) {
        $this->AddPage();
        // Refer to previous page
      }
      $this->SetY(-54);
      $this->Cell($signature_width, 6, REPORT_TEXT_FOOTER_SIGNATURE_EMPLOYEE, 'LTR');
      $this->SetX($x_pos);
      $this->Cell(0, 6, REPORT_TEXT_FOOTER_SIGNATURE_CUSTOMER, 'LTR');
      $this->Ln();
      $this->Cell($signature_width, 20, '', 'LBR');
      $this->SetX($x_pos);
      $this->Cell(0, 20, '', 'LBR');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(0, 7, REPORT_TEXT_FOOTER_ACKNOWLEDGE, 0, 1, 'C');
    }

    public function ChapterTitle($number, $label) {
      //Arial 12
      $this->SetFont('Arial', '', 12);
      //Background color
      $this->SetFillColor(12, 151, 172);
      //Title
      $this->Cell(0, 6, $number . ' : ' . $label, 0, 1, 'L', true);
      //Line break
      $this->Ln(4);
    }

    public function ChapterBody($file) {
      //Read text file
      $f=fopen($file,'r');
      $txt=fread($f,filesize($file));
      fclose($f);
      //Times 12
      $this->SetFont('Times','',12);
      //Output justified text
      $this->MultiCell(0,5,$txt);
      //Line break
      $this->Ln();
      //Mention in italics
      $this->SetFont('','I');
      $this->Cell(0,5,'(end of excerpt)');
    }

    public function PrintChapter($num,$title,$file) {
      $this->AddPage();
      $this->ChapterTitle($num,$title);
      $this->ChapterBody($file);
    }
  
    //Simple table
    function BasicTable($header,$data) {
      //Header
      foreach($header as $col)
        $this->Cell(40,7,$col,1);
      $this->Ln();
      //Data
      foreach($data as $row) {
        foreach($row as $col) {
          $this->Cell(40,6,$col,1);
        }
        $this->Ln();
      }
    }

    //Better table
    function ImprovedTable($header,$data) {
      //Column widths
      $w=array(40,35,40,45);
      //Header
      for($i=0;$i<count($header);$i++) {
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
      }
      $this->Ln();
      //Data
      foreach($data as $row) {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
      }
      //Closure line
      $this->Cell(array_sum($w),0,'','T');
    }

    //Colored table
    function EmployeeTable($header, $data, $orientation) {
      //Colors, line width and bold font
      $this->SetFillColor(191, 191, 191);
      $this->SetTextColor(0, 0, 0);
      $this->SetDrawColor(0, 0, 0);
      $this->SetLineWidth(.3);
      // Determine and set column widths
      $width = array();
      $this->SetFont('Arial', 'B', 10);
      for($index=0; $index<count($header); $index++) {
        $width[$index] = $this->GetStringWidth($header[$index])+6;
      }
      $this->SetFont('Arial', '', 10);
      foreach ($data as $row) {
        for ($index=0; $index<count($row); $index++) {
          if ($width[$index] < $this->GetStringWidth($row[$index])+6) {
            $width[$index] = $this->GetStringWidth($row[$index])+6;
          }
        }
      }
      //Header
      $this->SetFont('Arial', 'B', 10);
      $this->SetX(($this->w-array_sum($width))/2);
      for ($index=0; $index<count($header); $index++) {
        $this->Cell($width[$index], 7, $header[$index], 1, 0, 'C', true);
      }
      $this->Ln();
      //Color and font restoration
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->SetFont('Arial', '', 10);
      //Data
      $fill=false;
      foreach ($data as $row) {
        $this->SetX(($this->w-array_sum($width))/2);
        for ($index=0; $index<count($row); $index++) {
          $this->Cell($width[$index], 6, $row[$index], 'LR', 0, $orientation[$index], $fill);
        }
        $this->Ln();
        $fill=!$fill;
      }
      $this->SetX(($this->w-array_sum($width))/2);
      $this->Cell(array_sum($width),0,'','T');
    }
  }
?>