<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer_types extends CI_Controller {
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
		$this->load->model(array('main_model','permissions_model','customertypes_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['customer_types'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='customer-types';
		$this->data['title']=lang('customer_types');

		$this->data['customer_types']=$this->customertypes_model->get_customer_types();
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-types',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		if(!isset($this->data['permission_data']['customer_type_new'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='new-customer-type';
		$this->data['title']=lang('new')." ".lang('customer_type');
		
		$this->data['alertMsg']=$this->process_customer_type_new();
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-type-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['customer_type_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-customer-type';
		$this->data['title']=lang('edit')." ".lang('customer_type');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_customer_type_edit($id);
		$this->data['customer_type_data']=$this->customertypes_model->get_customer_type_data($id);
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-type-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		if(!isset($this->data['permission_data']['customer_type_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-customer-type';
		$this->data['title']=lang('delete')." ".lang('customer_type');
		
		$id=url_id_decode($eid);
		$customer_type_data=$this->customertypes_model->get_customer_type_data($id);
		$chk=$this->process_customer_type_delete($id);
		if($chk==1) header('location:'.base_url().'customer-types?successmsg=%22'.$customer_type_data->name.'%22 '.lang('customer_type_deleted'));
		else header('location:'.base_url().'customer-types?errormsg='.lang('some_error_occurred'));
	}
	private function process_customer_type_delete($id) {
		$success=0;
		if($this->customertypes_model->delete_customer_type($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_customer_type_new() {
		$msg="";
		if(isset($_POST['add_customer_type_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->customertypes_model->is_customer_type_exists($ddata['name'])) {
				if($this->customertypes_model->add_customer_type($ddata)) {
					$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('customer_type_added')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['name']."&quot; ".lang('customer_type_exists')."</div>"; }
		}
		return $msg;
	}
	private function process_customer_type_edit($id) {
		$msg="";
		if(isset($_POST['edit_customer_type_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->customertypes_model->is_customer_type_exists($ddata['name'],$id)) {
				if($this->customertypes_model->update_customer_type($id,$ddata)) {
					$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('customer_type_updated')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['name']."&quot; ".lang('customer_type_exists')."</div>"; }
		}
		return $msg;
	}
}
