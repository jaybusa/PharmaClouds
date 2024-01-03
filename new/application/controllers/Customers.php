<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends CI_Controller {
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
		$this->load->model(array('main_model','permissions_model','customers_model','customertypes_model','cars_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['customers'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='customers';
		$this->data['title']=lang('customers');
		
		$this->data['dataTable']=1;

		$this->data['customers']=$this->customers_model->get_customers();
		$this->load->view('inc/header',$this->data);
		$this->load->view('customers',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		if(!isset($this->data['permission_data']['customer_new'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='new-customer';
		$this->data['title']=lang('new')." ".lang('customer');
		
		$this->data['alertMsg']=$this->process_customer_new();
		$this->data['customer_types']=$this->customertypes_model->get_customer_types(1);
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['customer_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-customer';
		$this->data['title']=lang('edit')." ".lang('customer');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_customer_edit($id);
		$this->data['customer_data']=$this->customers_model->get_customer_data($id);
		$this->data['customer_types']=$this->customertypes_model->get_customer_types(1);
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view($eid) {
		$this->data['page']='view-customer';
		$this->data['title']=lang('view')." ".lang('customer');
		
		$id=url_id_decode($eid);
		$this->data['customer_data']=$this->customers_model->get_customer_data($id);
		$this->data['customer_cars']=$this->cars_model->get_customer_cars($id);
		
		if($this->data['customer_data']->status==0) {
			$this->data['customer_data']->state_class="inactive-state";
			$this->data['customer_data']->state_name=lang('inactive');
		} elseif($this->data['customer_data']->status==1) {
			$this->data['customer_data']->state_class="active-state";
			$this->data['customer_data']->state_name=lang('active');
		}
		$this->load->view('inc/header',$this->data);
		$this->load->view('customer-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		if(!isset($this->data['permission_data']['customer_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-customer';
		$this->data['title']=lang('delete')." ".lang('customer');
		
		$id=url_id_decode($eid);
		$customer_data=$this->customers_model->get_customer_data($id);
		if(!$this->customers_model->is_customer_have_data($id)) {
			$chk=$this->process_customer_delete($id);
		} else {
			header('location:'.base_url().'customers?errormsg='.lang('customer_have_data')); exit;
		}
		if($chk==1) {
			header('location:'.base_url().'customers?successmsg=%22'.$customer_data->name.'%22 '.lang('customer_deleted')); exit;
		} else {
			header('location:'.base_url().'customers?errormsg='.lang('some_error_occurred')); exit;
		}
	}
	private function process_customer_delete($id) {
		$success=0;
		if($this->customers_model->delete_customer($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_customer_new() {
		$msg="";
		if(isset($_POST['add_customer_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['email']=sanitize_input_post($this->input->post('email'));
			$ddata['phone']=sanitize_input_post($this->input->post('phone'));
			$ddata['type']=sanitize_input_post($this->input->post('type'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->customers_model->is_phone_exists($ddata['phone'])) {
				if($this->customers_model->add_customer($ddata)) {
					$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('customer_added')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['phone']."&quot; ".lang('phone_exists')."</div>"; }
		}
		return $msg;
	}
	private function process_customer_edit($id) {
		$msg="";
		if(isset($_POST['edit_customer_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['email']=sanitize_input_post($this->input->post('email'));
			$ddata['phone']=sanitize_input_post($this->input->post('phone'));
			$ddata['type']=sanitize_input_post($this->input->post('type'));
			$ddata['status']=sanitize_input_post($this->input->post('status'));
			if(!$this->customers_model->is_phone_exists($ddata['phone'],$id)) {
				if($this->customers_model->update_customer($id,$ddata)) {
					$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('customer_updated')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['phone']."&quot; ".lang('phone_exists')."</div>"; }
		}
		return $msg;
	}
}
