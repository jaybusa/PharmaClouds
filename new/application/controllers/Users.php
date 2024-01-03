<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {
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
		$this->load->model(array('main_model','permissions_model','users_model','settings_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['users'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='users';
		$this->data['title']=lang('users');
		
		$this->data['users']=$this->users_model->get_users();
		$this->load->view('inc/header',$this->data);
		$this->load->view('users',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		$this->data['page']='new-user';
		$this->data['title']=lang('new')." ".lang('user');
		
		$this->data['alertMsg']=$this->process_user_new();
		$this->load->view('inc/header',$this->data);
		$this->load->view('user-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($username)
	{
		$this->data['page']='edit-user';
		$this->data['title']=lang('edit')." ".lang('user');
		
		$this->data['alertMsg']=$this->process_user_edit($username);
		$this->data['s_user_data']=$this->users_model->get_user_data($username);
		$this->load->view('inc/header',$this->data);
		$this->load->view('user-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		$this->data['page']='delete-user';
		$this->data['title']=lang('delete')." ".lang('user');
		
		$id=url_id_decode($eid);
		$username=$this->users_model->get_username($id);
		$user_data=$this->users_model->get_user_data($username);
		$chk=$this->process_user_delete($id);
		if($chk==1) header('location:'.base_url().'users?successmsg=%22'.$user_data->name.'%22 '.lang('user_deleted'));
		else header('location:'.base_url().'users?errormsg='.lang('some_error_occurred'));
	}
	public function view($username) {
		$this->data['page']='view-user';
		$this->data['title']=lang('view')." ".lang('user');
		
		$this->data['alertMsg']=$this->update_password($username);
		$this->data['s_user_data']=$this->users_model->get_user_data($username);
		switch($this->data['s_user_data']->role) {
			case 1: $this->data['s_user_data']->role_name=lang('spare_part_entry'); break;
			case 2: $this->data['s_user_data']->role_name=lang('data_entry'); break;
			case 3: $this->data['s_user_data']->role_name=lang('admin'); break;
		}
		if($this->data['s_user_data']->status==0) {
			$this->data['s_user_data']->state_class="inactive-state";
			$this->data['s_user_data']->state_name=lang('inactive');
		} elseif($this->data['s_user_data']->status==1) {
			$this->data['s_user_data']->state_class="active-state";
			$this->data['s_user_data']->state_name=lang('active');
		}
		$this->data['activities']=$this->users_model->get_user_activity($this->data['s_user_data']->id);
		$this->load->view('inc/header',$this->data);
		$this->load->view('user-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	private function update_password($username) {
		$msg="";
		$startEnc="";
		$endEnc="";
		$err=0;
		$userid=$this->users_model->get_user_id($username);
		
		if(!empty($_POST['change_pass_btn'])) {
			if($this->data['user_data']->role!=3) $curpass=sanitize_input_post($this->input->post('curpass'));
			$pass=sanitize_input_post($this->input->post('pass'));
			$conpass=sanitize_input_post($this->input->post('conpass'));
			if($this->data['user_data']->role!=3) {
				if(!$this->settings_model->check_current_pass($curpass,$username)) {
					$err=1;
					$startEnc="<div class='alert alert-danger'>";
					$msg.="<div>".lang('incorrect_curpass')."</div>";
					$endEnc="</div>";
				}
			}
			if($pass!=$conpass) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>".lang('pass_conpass_not_matching')."</div>";
				$endEnc="</div>";
			}
			if(!$this->is_password_strong($pass)) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>".lang('weak_pass')."</div>";		
				$endEnc="</div>";	
			}
			if($err==0) {
				$this->main_model->change_password($userid,$pass);
				$msg="<div class='alert alert-success'>".lang('pass_changed')."</div>";
			} else {
				$msg=$startEnc.$msg.$endEnc;
			}
			return $msg;
		}
	}
	private function process_user_new() {
		$msg="";
		$startEnc="";
		$endEnc="";
		$err=0;
		if(!empty($_POST['add_user_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['username']=sanitize_input_post($this->input->post('username'));
			$pass=sanitize_input_post($this->input->post('pass'));
			$conpass=sanitize_input_post($this->input->post('conpass'));
			$ddata['pass']=md5(sanitize_input_post($this->input->post('pass')));
			$ddata['email']=sanitize_input_post($this->input->post('email'));
			$ddata['phone']=sanitize_input_post($this->input->post('phone'));
			$ddata['role']=sanitize_input_post($this->input->post('role'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			$ddata['picture']=NULL;
			if($pass!=$conpass) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>".lang('pass_conpass_not_matching')."</div>";
				$endEnc="</div>";
			}
			if($this->users_model->is_exists_user($ddata['username'])) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>&quot;".$ddata['username']."&quot; ".lang('username_exists')."</div>";
				$endEnc="</div>";
			}
			if($this->users_model->is_exists_email($ddata['email'])) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>&quot;".$ddata['email']."&quot; ".lang('email_exists')."</div>";		
				$endEnc="</div>";				
			}
			if(!$this->is_password_strong($pass)) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>".lang('weak_pass')."</div>";		
				$endEnc="</div>";	
			}
			if(!empty($_FILES['picture']['name']) && $err==0) {
				$this->load->library('upload');
				$field_name = "picture";
				$config['upload_path'] = 'uploads/users/';
				$config['file_name'] = $ddata['username'];
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '1024';
				$config['max_width'] = '1200';
				$config['max_height'] = '1200';
				$this->upload->initialize($config);
				if($this->upload->do_upload($field_name)) {
					$ddata['picture']=$this->upload->data('file_name');
				} else {
					$err=1;
					$startEnc="<div class='alert alert-danger'>";
					$msg.=$this->upload->display_errors();
					$endEnc="</div>";
				}
			} else { $ddata['picture']=NULL;}
			if($err==0) {
				if($this->users_model->add_user($ddata)) {
					$msg="<div class='alert alert-success'>&quot;".$ddata['username']."&quot; ".lang('user_added')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg=$startEnc.$msg.$endEnc; }
		}
		return $msg;
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
	private function process_user_edit($username) {
		$msg="";
		$startEnc="";
		$endEnc="";
		$err=0;
		if(!empty($_POST['edit_user_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['email']=sanitize_input_post($this->input->post('email'));
			$ddata['phone']=sanitize_input_post($this->input->post('phone'));
			$ddata['role']=sanitize_input_post($this->input->post('role'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			$ddata['picture']=NULL;
			if($this->users_model->is_exists_email($ddata['email'],$username)) {
				$err=1;
				$startEnc="<div class='alert alert-danger'>";
				$msg.="<div>&quot;".$ddata['email']."&quot; ".lang('email_exists')."</div>";		
				$endEnc="</div>";				
			}
			if(!empty($_FILES['picture']['name'])) {
				$this->load->library('upload');
				$field_name = "picture";
				$config['upload_path'] = 'uploads/users/';
				$config['file_name'] = $username;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '1024';
				$config['max_width'] = '1200';
				$config['max_height'] = '1200';
				$this->upload->initialize($config);
				if($this->upload->do_upload($field_name)) {
					$ddata['picture']=$this->upload->data('file_name');
				} else {
					$err=1;
					$startEnc="<div class='alert alert-danger'>";
					$msg.=$this->upload->display_errors();
					$endEnc="</div>";
				}
			} else { $ddata['picture']=NULL;}
			if($err==0) {
				if($this->users_model->update_user($username,$ddata)) {
					$msg="<div class='alert alert-success'>".lang('user_updated')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg=$startEnc.$msg.$endEnc; }
		}
		return $msg;
	}
	
	private function process_user_delete($id) {
		$success=0;
		if($this->users_model->delete_user($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	
}
