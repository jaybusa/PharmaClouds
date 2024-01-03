<?php
class Customertypes_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt'));
    }
	public function get_customer_types($status=FALSE,$order_by='id desc') {
		$this->main->select('id,name,status');
		$this->main->from('customer_types');
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_customer_type_data($id){
		$this->main->select('id,name,status,createdon');
		$this->main->from('customer_types');
		$this->main->where('id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function is_customer_type_exists($name,$id=FALSE) {
		$this->main->select('count(*) as c');
		$this->main->from('customer_types');
		$this->main->where('name',$name);
		if($id!=FALSE) $this->main->where('id!=',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function add_customer_type($data) {
		$this->main->set('name',$data['name']);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		if(!isset($data['status'])) $data['status']=0;
		$this->main->set('status',$data['status']);
		return $this->main->insert('customer_types');
	}
	public function delete_customer_type($id) {
		$this->main->where('id', $id);
		if($this->main->delete('customer_types')) return true;
		return false;
		
	}
	public function update_customer_type($id,$data) {
		if(!isset($data['status'])) $data['status']=0;
		$udata = array(
		'name' => $data['name'],
		'status' => $data['status']
        );
		$this->main->where('id', $id);
		return $this->main->update('customer_types',$udata);
	}
}