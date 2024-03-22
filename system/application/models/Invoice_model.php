<?php
class Invoice_model extends CI_Model
{

	/** get invoice deatail **/
	public function getInvoice($invoiceId) {
		$query = $this->db->select('*')->where('id', $invoiceId)->get('tbl_international_invoice');
		return $query->row();	
	}
	
	/** Get invoice detail **/
	public function getInvoiceDetail($invoiceId)
	{
		$query = $this->db->select('*')->where('invoice_id', $invoiceId)->get('tbl_international_invoice_detail');
		return $query->result();
	}
	
	/** get import invoice deatail **/
	public function getImportInvoice($invoiceId) {
		$query = $this->db->select('*')->where('id', $invoiceId)->get('tbl_international_invoice_import');
		return $query->row();	
	}
	
	/** Get import invoice detail **/
	public function getImportInvoiceDetail($invoiceId)
	{
		$query = $this->db->select('*')->where('invoice_id', $invoiceId)->get('tbl_international_invoice_detail_import');
		return $query->result();
	}

	public function getDomesticInvoice($invoiceId) {
		$query = $this->db->select('*')->where('id', $invoiceId)->get('tbl_domestic_invoice');
		return $query->row();	
	}
	public function getDomesticInvoiceDetail($invoiceId)
	{
		$query = $this->db->select('*')->where('invoice_id', $invoiceId)->get('tbl_domestic_invoice_detail');
		return $query->result();
	}

	
}
