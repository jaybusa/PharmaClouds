<?php
class Cartypes_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt'));
    }
	public function get_car_types($status=FALSE,$order_by='id desc') {
		$this->main->select('id,name,picture,status');
		$this->main->from('car_types');
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_car_type_data($id){
		$this->main->select('id,name,picture,status,createdon');
		$this->main->from('car_types');
		$this->main->where('id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function is_car_type_exists($name,$id=FALSE) {
		$this->main->select('count(*) as c');
		$this->main->from('car_types');
		$this->main->where('name',$name);
		if($id!=FALSE) $this->main->where('id!=',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function add_car_type($data) {
		$this->main->set('name',$data['name']);
		$this->main->set('picture',$data['picture']);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		if(!isset($data['status'])) $data['status']=0;
		$this->main->set('status',$data['status']);
		return $this->main->insert('car_types');
	}
	public function delete_car_type($id) {
		$this->main->where('id', $id);
		if($this->main->delete('car_types')) return true;
		return false;
		
	}
	public function update_car_type($id,$data) {
		if(!isset($data['status'])) $data['status']=0;
		$udata = array(
		'name' => $data['name'],
		'picture' => $data['picture'],
		'status' => $data['status']
        );
		$this->main->where('id', $id);
		return $this->main->update('car_types',$udata);
	}
	public function get_car_type_picture($id) {
		$this->main->select('picture');
		$this->main->from('car_types');
		$this->main->where('id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0]->picture;
		else return false;
	}
}