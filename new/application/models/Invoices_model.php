<?php
class Invoices_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
    }
	public function get_invoices($status=FALSE,$order_by='id desc') {
		$this->main->select('id,name,type,status');
		$this->main->from('invoices');
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_invoice_data($id){
		$this->main->select('cc.id,cc.check_in,cc.check_out,cc.discount,cc.stage,
		cu.name as customer_name,cu.email as customer_email,cu.phone as customer_phone,
		c.car_id as car_id,c.model');
		$this->main->from('cico as cc');
		$this->main->join('cars as c','c.id=cc.car_id');
		$this->main->join('customers as cu','cu.id=c.owner');
		$this->main->where('cc.id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function delete_invoice($id) {
		$this->main->where('id', $id);
		if($this->main->delete('invoices')) return true;
		return false;
		
	}
}