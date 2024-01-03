<?php
class Cars_model extends CI_Model {
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
	public function get_cars($status=FALSE,$order_by='id desc') {
		$this->main->select('c.id,c.car_id,c.model,ct.id as ct_id,ct.name as type,ct.picture as ct_pic,c.owner,c.createdon,c.status');
		$this->main->from('cars as c');
		$this->main->join('car_types as ct','c.type=ct.id','left');
		if($status!=FALSE) $this->main->where('c.status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query == FALSE) $result=false; else if($query->num_rows() > 0){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_car_data($id){
		$this->main->select('c.id,c.car_id,c.model,ct.id as ct_id,ct.name as type,ct.picture as ct_pic,c.image,c.details,cus.name as owner,c.owner as cus_id,c.status,c.createdon,c.last_modified_on,u.name as last_modified_by,u.username as last_modified_by_user');
		$this->main->from('cars as c');
		$this->main->join('car_types as ct','c.type=ct.id');
		$this->main->join('customers as cus','c.owner=cus.id');
		$this->main->join('users as u','u.id=c.last_modified_by','left');
		$this->main->where('c.id',$id);
		$query=$this->main->get();
		if ($query != FALSE){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function get_carid_by_id($id){
		$this->main->select('car_id');
		$this->main->from('cars');
		$this->main->where('id',$id);
		$query=$this->main->get();
		if ($query != FALSE){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0]->car_id;
		else return false;
	}
	public function is_car_have_data($cid) {
		$this->main->select('count(*) as c');
		$this->main->from('cico');
		$this->main->where('car_id',$cid);
		$query=$this->main->get();
		if ($query != FALSE){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function is_exists_car($car_id) {
		$this->main->select('count(*) as c');
		$this->main->from('cars');
		$this->main->where('car_id',$car_id);
		$query=$this->main->get();
		if ($query != FALSE){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function get_customer_cars($cid,$status=FALSE,$order_by='id desc') {
		$this->main->select('c.id,c.car_id,c.model,ct.id as ct_id,ct.name as type,ct.picture as ct_pic,c.owner,c.status');
		$this->main->from('cars as c');
		$this->main->join('car_types as ct','c.type=ct.id','left');
		$this->main->where('c.owner',$cid);
		if($status!=FALSE) $this->main->where('c.status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query == FALSE) $result=false; else if($query->num_rows() > 0){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function add_car($data) {
		$this->main->set('car_id',$data['car_id']);
		$this->main->set('owner',$data['owner']);
		$this->main->set('model',$data['model']);
		$this->main->set('type',$data['type']);
		$this->main->set('details',$data['details']);
		$this->main->set('image',$data['picture']);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		if(!isset($data['status'])) $data['status']=0;
		$this->main->set('status',$data['status']);
		return $this->main->insert('cars');
	}
	public function delete_car($id) {
		$this->main->where('id', $id);
		if($this->main->delete('cars')) return true;
		return false;
		
	}
	public function update_car($id,$data) {
		if(!isset($data['status'])) $data['status']=0;
		$udata = array(
		'owner' => $data['owner'],
		'model' => $data['model'],
		'type' => $data['type'],
		'image' => $data['picture'],
		'details' => $data['details'],
		'status' => $data['status'],
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cars',$udata);
	}
}