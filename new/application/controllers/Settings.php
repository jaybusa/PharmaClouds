<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	private $data;
	function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->library('form_validation');
		$this->load->model(array('main_model','permissions_model','settings_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
    }
	public function index()
	{
		$this->data['page']='settings';
		$this->data['title']=lang('settings');
		if(isset($_POST['settings-btn'])) {
			$errorFlag=0;
			$data = sanitize_input_post($this->input->post(NULL));
			$curr_pass_chk=$this->settings_model->check_current_pass($data['curr_pass'],$this->data['user_data']->username);
			if($curr_pass_chk) {
				if(!empty($data['new_pass']) || !empty($data['con_pass'])) {
					if($data['new_pass']!=$data['con_pass']) {
						$errorFlag=1;
						$this->data['alertMsg'][]="<div class='alert alert-danger'>".lang('pass_conpass_not_matching')."</div>";	
					} 
					if(!$this->is_password_strong($data['new_pass'])) {
						$errorFlag=1;
						$this->data['alertMsg'][]="<div class='alert alert-danger'>".lang('weak_pass')."</div>";
					}
					if($errorFlag==0) {
						$this->main_model->change_password($this->data['user_data']->id,$data['new_pass']);
						$this->data['alertMsg'][]="<div class='alert alert-success'>".lang('pass_changed')."</div>";
					}
				}
				
				if(isset($this->data['permission_data']['otp'])) {
					if(!isset($data['otp_status'])) $data['otp_status']=0;
					if($this->settings_model->update_otp($data['otp_status'])) {
						$this->data['alertMsg'][]="<div class='alert alert-success'>".lang('settings_saved')."</div>";
					} else {
						$errorFlag=1;
						$this->data['alertMsg'][]="<div class='alert alert-danger'>".lang('otp_status_change_error')."</div>";
					}
				}
			} else {
				$errorFlag=1;
				$this->data['alertMsg'][]="<div class='alert alert-danger'>".lang('incorrect_curpass')."</div>";
			}
		}
		$this->data['otp_status']=$this->settings_model->get_otp_status();
		
		$this->load->view('inc/header',$this->data);
		$this->load->view('settings',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	private function is_password_strong($pass) {
		$errflag=0;
		if (strlen($pass) < 8 || strlen($pass)>15) {
			$errflag=1;
		}
		if (!preg_match("#[0-9]+#", $pass)) {
			$errflag=1;
		}
		if (!preg_match("#[a-zA-Z]+#", $pass)) {
			$errflag=1;
		}     
		if($errflag==0) return true;
		else return false;
	}
}
