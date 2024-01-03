<?php
class Users_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
    }
	public function get_users($status=FALSE) {
		$this->main->select('id,name,username,role,status');
		$this->main->from('users');
		if($status!=FALSE) $this->main->where('status',$status);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result;
		else return false;
	}
	public function get_user_data($username,$status=FALSE) {
		$this->main->select('id,name,picture,username,email,phone,role,createdon,status');
		$this->main->from('users');
		$this->main->where('username',$username);
		if($status!=FALSE) $this->main->where('status',$status);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0];
		else return false;
	}
	public function get_username($id) {
		$this->main->select('username');
		$this->main->from('users');
		$this->main->where('id',$id);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0]->username;
		else return false;
	}
	public function get_user_id($username) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('username',$username);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0]->id;
		else return false;
	}
	public function is_exists_user($username,$id=FALSE) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('username',$username);
		if($id!=FALSE) $this->main->where('id!=',$id);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return true;
		else return false;
	}
	public function is_exists_email($email,$username=FALSE) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('email',$email);
		if($username!=FALSE) $this->main->where('username!=',$username);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return true;
		else return false;
	}
	public function add_user($data) {
		$this->main->set('name',$data['name']);
		$this->main->set('picture',$data['picture']);
		$this->main->set('username',$data['username']);
		$this->main->set('password',$data['pass']);
		$this->main->set('email',$data['email']);
		$this->main->set('phone',$data['phone']);
		$this->main->set('role',$data['role']);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		if(empty($data['status'])) $data['status']=0;
		$this->main->set('status',$data['status']);
		return $this->main->insert('users');
	}
	public function update_user($username,$data) {
		if(empty($data['status'])) $data['status']=0;
		$udata = array(
		'name' => $data['name'],
		'picture' => $data['picture'],
		'email' => $data['email'],
		'phone' => $data['phone'],
		'role' => $data['role'],
		'status' => $data['status'],
        );
		$this->main->where('username', $username);
		return $this->main->update('users',$udata);
	}
	public function delete_user($id) {
		$this->main->where('id', $id);
		if($this->main->delete('users')) return true;
		return false;
		
	}
	public function get_user_activity($id){
		$this->main->select('ip,msg,createdon');
		$this->main->from('activities');
		$this->main->where('uid',$id);
		$this->main->order_by('id','desc');
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result;
		else return false;
	}
}