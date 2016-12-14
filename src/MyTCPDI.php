<?php

namespace rcamposp\tcpdi_merger;
// Include the main TCPDF library and TCPDI.
use rcamposp\tcpdi_merger\tcpdi;  

#Intended to extend stuff from the base TCPDF class
class MyTCPDI extends TCPDI{
    protected $header_line_color = array(255,255,255);

    protected $showPagination;
    
    public function __construct($showPagination = false){        
        $this->showPagination = $showPagination;
        parent::__construct();
    }

    // Page footer
	public function Footer() {
        if($this->showPagination){
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
	}
}


?>
