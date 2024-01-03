<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends CI_Controller {
	private $data;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->model(array('main_model','permissions_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['sessions'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='sessions';
		$this->data['title']=lang('sessions');
		$this->data['sessions']=$this->main_model->get_sessions();
		$this->load->view('inc/header',$this->data);
		$this->load->view('sessions',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view($username)
	{
		$this->data['page']='sessions';
		$this->data['title']=lang('sessions');
		$this->data['username']=$username;
		$this->data['sessions']=$this->main_model->get_sessions($username);
		$this->load->view('inc/header',$this->data);
		$this->load->view('sessions',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid) {
		if(!isset($this->data['permission_data']['session_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-session';
		$this->data['title']=lang('delete')." ".lang('session');
		
		$id=url_id_decode($eid);
		$chk=$this->process_session_delete($id);
		if($chk==1) header('location:'.base_url().'sessions?successmsg='.lang('session_deleted'));
		else header('location:'.base_url().'sessions?errormsg='.lang('some_error_occurred'));
	}
	private function process_session_delete($id) {
		$success=0;
		if($this->main_model->delete_session($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
}
