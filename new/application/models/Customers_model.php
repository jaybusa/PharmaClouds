<?php
class Customers_model extends CI_Model {
	private $main=NULL,$userid;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
		$this->load->helper('cookie');
		if(get_cookie('carp_token')) {
			$this->userid=$this->encrypt->decode(get_cookie('carp_token'));
		}
    }
	public function get_customers($status=FALSE,$order_by='id desc') {
		$this->main->select('c.id,c.name,ct.name as type,c.createdon,c.status');
		$this->main->from('customers as c');
		$this->main->join('customer_types as ct','ct.id=c.type');
		if($status!=FALSE) $this->main->where('c.status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_customer_data($id){
		$this->main->select('c.id,c.name,c.email,c.phone,t.name as type,t.id as type_id,c.status,c.createdon,c.last_modified_on,u.name as last_modified_by,u.username as last_modified_by_user');
		$this->main->from('customers as c');
		$this->main->join('customer_types as t','t.id=c.type');
		$this->main->join('users as u','u.id=c.last_modified_by','left');
		$this->main->where('c.id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function is_customer_have_data($id) {
		$this->main->select('count(*) as c');
		$this->main->from('cars');
		$this->main->where('owner',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function is_phone_exists($phone,$id=FALSE) {
		$this->main->select('count(*) as c');
		$this->main->from('customers');
		$this->main->where('phone',$phone);
		if($id!=FALSE) $this->main->where('id!=',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function add_customer($data) {
		$this->main->set('name',$data['name']);
		$this->main->set('email',$data['email']);
		$this->main->set('phone',$data['phone']);
		$this->main->set('type',$data['type']);
		if(!isset($data['status'])) $data['status']=0;
		$this->main->set('status',$data['status']);
		return $this->main->insert('customers');
	}
	public function delete_customer($id) {
		$this->main->where('id', $id);
		if($this->main->delete('customers')) return true;
		return false;
		
	}
	public function update_customer($id,$data) {
		if(!isset($data['status'])) $data['status']=0;
		$udata = array(
		'name' => $data['name'],
		'email' => $data['email'],
		'phone' => $data['phone'],
		'type' => $data['type'],
		'status' => $data['status'],
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('customers',$udata);
	}
}