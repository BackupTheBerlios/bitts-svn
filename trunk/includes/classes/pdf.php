<?php
/****************************************************************************
 * CLASS FILE  : pdf.php
 * Project     : BitTS - BART it TimeSheet
 * Auteur(s)   : Erwin Beukhof
 * Datum       : 15 september 2008
 * Beschrijving: FPDF wrapper class with pre-formatting
 */

  require('fpdf.php');

  class PDF extends FPDF {
    public function Header() {
      $this->Image(DIR_WS_IMAGES . COMPANY_BANNER, null, null, 40);
      //Arial bold 15
      $this->SetFont('Arial', 'B', 15);
      //Calculate width of title and position
      $width = $this->GetStringWidth($this->title)+6;
      $this->SetX(($this->w-$width)/2);
      //Colors of frame, background and text
      $this->SetDrawColor(0, 0, 0);
      $this->SetFillColor(255, 255, 255);
      $this->SetTextColor(0, 0, 0);
      //Thickness of frame (1 mm)
      //$this->SetLineWidth(1);
      //Title
      $this->Cell($width, 9, $this->title, 1, 1, 'C', true);
      //Line break
      $this->Ln(4);
    }

    public function Footer() {
      //Position at 1.5 cm from bottom
      $this->SetY(-15);
      //Arial italic 8
      $this->SetFont('Arial','I',8);
      //Text color in gray
      $this->SetTextColor(128);
      //Page number
      //$this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
      $this->Cell(0, 10, TITLE, 0, 0, 'C');
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
    function FancyTable($header, $data) {
      //Colors, line width and bold font
      $this->SetFillColor(86, 22, 132);
      $this->SetTextColor(255, 255, 255);
      $this->SetDrawColor(86, 22, 132);
      $this->SetLineWidth(.3);
      // Determine and set column widths
      $width = array();
      $this->SetFont('','B');
      for($index=0; $index<count($header); $index++) {
        $width[$index] = $this->GetStringWidth($header[$index])+6;
      }
      $this->SetFont('');
      foreach ($data as $row) {
        for ($index=0; $index<count($row); $index++) {
          if ($width[$index] < $this->GetStringWidth($row[$index])+6) {
            $width[$index] = $this->GetStringWidth($row[$index])+6;
          }
        }
      }
      //Header
      $this->SetFont('','B');
      $this->SetX(($this->w-array_sum($width))/2);
      for ($index=0; $index<count($header); $index++) {
        $this->Cell($width[$index], 7, $header[$index], 1, 0, 'C', true);
      }
      $this->Ln();
      //Color and font restoration
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->SetFont('');
      //Data
      $fill=false;
      foreach ($data as $row) {
        $this->SetX(($this->w-array_sum($width))/2);
        for ($index=0; $index<count($row); $index++) {
          $this->Cell($width[$index],6,$row[$index],'LR',0,'L',$fill);
        }
        $this->Ln();
        $fill=!$fill;
      }
      $this->SetX(($this->w-array_sum($width))/2);
      $this->Cell(array_sum($width),0,'','T');
    }
  }
?>