<?php 
include_once APPPATH.'/third_party/mpdf/mpdf.php';
class M_pdf {
 
    public $param;
    public $pdf;
 
    public function __construct($param = '"en-GB-x","B","","",10,10,10,10,6,3')
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
        
        //$this->pdf->showImageErrors = true;
     
    }
}