<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	private $data,$otpEnabled=0;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->library('form_validation');
		$this->load->helper('cookie');
		$this->load->model(array('main_model','permissions_model','settings_model'));
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		}
		//Loading settings
		$this->otpEnabled=$this->settings_model->get_otp_status();
		$this->data['otpEnabled']=$this->otpEnabled;
    }
	private function generateOTP() {
		$otp_value=mt_rand(100000, 999999);
		return $otp_value;
	}
	public function index()
	{
		if(get_cookie('carp_token')) { header('location:'.base_url().'dashboard'); exit; }
		$this->data['page']='home';
		$this->data['title']=lang('home');
		$this->load->view('index',$this->data);
	}
	public function login()
	{
		if(get_cookie('carp_token')) { header('location:'.base_url().'dashboard'); exit; }
		$this->data['page']='login';
		$this->data['title']=lang('login');
		
		if(isset($_POST['login_form_submit'])) {
			$otpError=0;
			if($this->otpEnabled) {
				if($_POST['otp_value']!=$_SESSION['otp_value']) $otpError=1;
			}
			if(!$otpError) {
				$this->form_validation->set_rules('user', 'Username', 'required');
				$this->form_validation->set_rules('pass', 'Password', 'required');
				if($this->form_validation->run() != FALSE) {
					$data = sanitize_input_post($this->input->post(NULL));
					$login_data['user']=$data['user'];
					$login_data['pass']=md5($data['pass']);
					$response=$this->main_model->authUser($login_data);
					if($response) {
						$cookieExpiry=60*60*5;
						if(isset($_POST['remember_me'])) {
							$cookieExpiry=60*60*24*365;
						}
						$this->input->set_cookie('carp_token',$this->encrypt->encode($response->id),$cookieExpiry);
						$this->main_model->add_activity($response->id,lang('user_logged_in'));
						$this->main_model->add_session($response->id);
						header('location:'.base_url().'dashboard');
					} else {
						$this->data['alertMsg']="<div class='alert alert-danger'>".lang('incorrect_user_pass')."</div>";
					}
				}
			} else {
				$this->data['alertMsg']="<div class='alert alert-danger'>".lang('incorrect_otp')."</div>";
			}
		}
		if($this->otpEnabled) {
			$_SESSION['otp_value']=$this->generateOTP();
			$this->data['otp_value']=$_SESSION['otp_value'];
		}
		$this->load->view('login',$this->data);
	}
	public function forgot_password() {
		if(get_cookie('carp_token')) { header('location:'.base_url().'dashboard'); exit; }
		$this->data['page']='forgot-password';
		$this->data['title']=lang('forgot_password');
		
		if(isset($_POST['forgot-btn'])) {
			$this->form_validation->set_rules('email', 'Email', 'required');
			if($this->form_validation->run() != FALSE) {
				$data = sanitize_input_post($this->input->post(NULL));
				$login_data['email']=$data['email'];
				
				$time=url_id_encode(time());
				$useremail=url_id_encode($login_data['email']);
				$link=base_url()."reset-password/".$time."/".$useremail;
								
				$response=$this->main_model->forgot_pass($login_data,$link);
				if($response) {
					$this->data['alertMsg']="<div class='alert alert-success'>".lang('pass_reset_mail_sent')."</div>";
				} else {
					$this->data['alertMsg']="<div class='alert alert-danger'>".lang('email_not_registered')."</div>";
				}
			}
		}
		if($this->otpEnabled) {
			$_SESSION['otp_value']=$this->generateOTP();
			$this->data['otp_value']=$_SESSION['otp_value'];
		}
		
		$this->load->view('forgot-password',$this->data);
	}
	public function reset_password($time,$useremail) {
		if(get_cookie('carp_token')) { header('location:'.base_url().'dashboard'); exit; }
		$this->data['page']='forgot-password';
		$this->data['title']=lang('forgot_password');
		
		$time=url_id_decode($time);
		$useremail=url_id_decode($useremail);
		$currTime=time();
		if(($currTime-$time)>(60*60*24)) {
			$this->data['alertMsg']="<div class='alert alert-danger'>".lang('pass_successfully_reset')."</div>";
		} else {
			$login_data['email']=$useremail;
			$response=$this->main_model->reset_password($login_data);
			if($response) {
				$this->data['alertMsg']="<div class='alert alert-success'>".lang('pass_successfully_reset')."</div>";
			} else {
				$this->data['alertMsg']="<div class='alert alert-danger'>".lang('email_not_registered')."</div>";
			}
		}
		$this->load->view('forgot-password',$this->data);
	}
	public function logout() {
		$this->main_model->add_activity($this->data['user_data']->id,lang('user_logged_out'));
		delete_cookie('carp_token');
		header('location:'.base_url().'login');
	}
	public function dashboard()
	{
		if(!get_cookie('carp_token')) { header('location:'.base_url().'login'); exit; }
		$this->data['page']='dashboard';
		$this->data['title']=lang('dashboard');
		$this->load->view('inc/header',$this->data);
		$this->load->view('dashboard',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function send_otp() {
		if(isset($_POST['user']) && isset($_POST['pass'])) {
			$data['user']=sanitize_input_post($this->input->post('user'));
			$data['pass']=md5(sanitize_input_post($this->input->post('pass')));
			$userData=$this->main_model->authUserPhone($data);
			if(!empty($userData)) {
				$to=$userData->phone;
				$msg='Your OTP for '.lang('app_name').' is '.$_SESSION['otp_value'].'.';
				include_once('mobily/send.php');
			} else {
				echo "-1";
			}
		}
	}
	public function validate_otp(){
		$otp=sanitize_input_post($this->input->post('otp'));
		if($otp==$_SESSION['otp_value']) echo "1";
		else echo "-1";
	}
	public function unauthorized_access(){
		if(!get_cookie('carp_token')) { header('location:'.base_url().'login'); exit; }
		$this->data['page']='unauthorized-access';
		$this->data['title']=lang('unauthorized_access');
		$this->load->view('inc/header',$this->data);
		$this->load->view('unauthorized-access',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function change_language($lang) {
		if(!get_cookie('carp_token')) { header('location:'.base_url().'login'); exit; }
		$_SESSION['lang']=$lang;
		if(isset($_GET['uri'])) $redirect_url=$_GET['uri']; else $redirect_url=FALSE;
		if($redirect_url!=FALSE) header('location:'.$redirect_url);
		else header('location:'.base_url().'dashboard');
	}
}