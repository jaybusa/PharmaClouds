<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Car_types extends CI_Controller {
	private $data;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->model(array('main_model','permissions_model','cartypes_model'));
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['car_types'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='car-types';
		$this->data['title']=lang('car_types');

		$this->data['car_types']=$this->cartypes_model->get_car_types();
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-types',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		if(!isset($this->data['permission_data']['car_type_new'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='new-car-type';
		$this->data['title']=lang('new')." ".lang('car_type');
		
		$this->data['alertMsg']=$this->process_car_type_new();
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-type-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['car_type_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-car-type';
		$this->data['title']=lang('edit')." ".lang('car_type');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_car_type_edit($id);
		$this->data['car_type_data']=$this->cartypes_model->get_car_type_data($id);
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-type-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		if(!isset($this->data['permission_data']['car_type_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-car-type';
		$this->data['title']=lang('delete')." ".lang('car_type');
		
		$id=url_id_decode($eid);
		$car_type_data=$this->cartypes_model->get_car_type_data($id);
		$chk=$this->process_car_type_delete($id);
		if($chk==1) header('location:'.base_url().'car-types?successmsg=%22'.$car_type_data->name.'%22 '.lang('car_type_deleted'));
		else header('location:'.base_url().'car-types?errormsg='.lang('some_error_occurred'));
	}
	private function process_car_type_delete($id) {
		$success=0;
		if($this->cartypes_model->delete_car_type($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_car_type_new() {
		$msg="";
		$err=0;
		if(isset($_POST['add_car_type_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->cartypes_model->is_car_type_exists($ddata['name'])) {
				if(!empty($_FILES['picture']['name']) && $err==0) {
					$this->load->library('upload');
					$field_name = "picture";
					$config['upload_path'] = 'uploads/car_types/';
					$config['file_name'] = time();
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
				} else { $ddata['picture']=NULL; }
				if($err==0) {
					if($this->cartypes_model->add_car_type($ddata)) {
						$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('car_type_added')."</div>";
					} else {
						$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
					}
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['name']."&quot; ".lang('car_type_exists')."</div>"; }
		}
		return $msg;
	}
	private function process_car_type_edit($id) {
		$msg="";
		$err=0;
		if(isset($_POST['edit_car_type_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->cartypes_model->is_car_type_exists($ddata['name'],$id)) {
				if(!empty($_FILES['picture']['name']) && $err==0) {
					$this->load->library('upload');
					$field_name = "picture";
					$config['upload_path'] = 'uploads/car_types/';
					$config['file_name'] = $id."_".time();
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
				} else { $ddata['picture']=$this->cartypes_model->get_car_type_picture($id); }
				if($err==0) {
					if($this->cartypes_model->update_car_type($id,$ddata)) {
						$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('car_type_updated')."</div>";
					} else {
						$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
					}
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['name']."&quot; ".lang('car_type_exists')."</div>"; }
		}
		return $msg;
	}
}
