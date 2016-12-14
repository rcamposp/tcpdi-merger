<?php

require_once("src/MyTCPDI.php");

class Merger{
    private $tcpdi;

    private $files = array();

    private $output = "";    

    public function __construct($showPagination = false){
        $this->tcpdi = new MyTCPDI($showPagination); //Default page format params        
    }

    public function addRaw($pdf){
        assert('is_string($pdf)');
        // Create temporary file
        $fname = $this->getTempFname();
        if (@file_put_contents($fname, $pdf) === false) {
            throw new Exception("Unable to create temporary file");
        }
        $this->addFromFile($fname, true);
    }
    
    
    public function addFromFile($fname, $cleanup = false){
        assert('is_string($fname)');
        assert('is_bool($cleanup)');
        if (!is_file($fname) || !is_readable($fname)) {
            throw new Exception("'$fname' is not a valid file");
        }
     
        $this->files[] = array($fname, $cleanup);
    }

    public function merge(){
        if (empty($this->files)) {
            throw new Exception("Unable to merge, no PDFs added");
        }

        $fname = '';
        try {
            $tcpdi = clone $this->tcpdi;
            foreach ($this->files as $fileData) {
                list($fname, $cleanup) = $fileData;
                
                $iPageCount = $tcpdi->setSourceFile($fname);
                // Add all pages                
                $pages = range(1, $iPageCount);
                
                // Add specified pages
                foreach ($pages as $page) {
                    $template = $tcpdi->importPage($page);
                    $size = $tcpdi->getTemplateSize($template);
                    $orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
                    $tcpdi->AddPage($orientation);                    
                    $tcpdi->useTemplate($template);
                }
            }
            
            $output = $tcpdi->Output('', 'S');
            $tcpdi->cleanUp();
            foreach ($this->files as $fileData) {
                list($fname, $cleanup) = $fileData;
                if ($cleanup) {
                    unlink($fname);
                }
            }
            $this->files = array();            
            $this->output = $output;            
        } catch (\Exception $e) {
            throw new Exception("FPDI: '{$e->getMessage()}' in '$fname'", 0, $e);
        }
    }

    public function download($filename = 'document.pdf')
    {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="'.$filename.'"'
        ));
    }

    public function save($path = 'example/document.pdf')
    {
        file_put_contents($path, $this->output);
    }    
	
}

?>
