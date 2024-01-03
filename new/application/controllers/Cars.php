<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cars extends CI_Controller {
	private $data;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->model(array('main_model','permissions_model','cars_model','cartypes_model','customers_model','cico_model'));
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		
		if(!isset($this->data['permission_data']['cars'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='cars';
		$this->data['title']=lang('cars');
		
		$this->data['dataTable']=1;

		$this->data['cars']=$this->cars_model->get_cars();
		$this->load->view('inc/header',$this->data);
		$this->load->view('cars',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		if(!isset($this->data['permission_data']['car_new'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='new-car';
		$this->data['title']=lang('new')." ".lang('car');
		
		$this->data['alertMsg']=$this->process_car_new();
		$this->data['car_types']=$this->cartypes_model->get_car_types(1);
		$this->data['customers']=$this->customers_model->get_customers(1);
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['car_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-car';
		$this->data['title']=lang('edit')." ".lang('car');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_car_edit($id);
		$this->data['car_data']=$this->cars_model->get_car_data($id);
		$this->data['car_types']=$this->cartypes_model->get_car_types(1);
		$this->data['customers']=$this->customers_model->get_customers(1);
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view($eid) {
		$this->data['page']='view-car';
		$this->data['title']=lang('view')." ".lang('car');
		
		$id=url_id_decode($eid);
		$this->data['car_data']=$this->cars_model->get_car_data($id);
		$this->data['car_checkin_data']=$this->cico_model->get_car_checkin_data($id);
		$this->load->view('inc/header',$this->data);
		$this->load->view('car-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		if(!isset($this->data['permission_data']['car_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-car';
		$this->data['title']=lang('delete')." ".lang('car');
		
		$id=url_id_decode($eid);
		$car_data=$this->cars_model->get_car_data($id);
		if(!$this->cars_model->is_car_have_data($id)) {
			$chk=$this->process_car_delete($id);
		} else {
			header('location:'.base_url().'cars?errormsg='.lang('car_have_data')); exit;
		}
		if($chk==1) {
			header('location:'.base_url().'cars?successmsg=%22'.$car_data->car_id.'%22 '.lang('car_deleted')); exit;
		} else {
			header('location:'.base_url().'cars?errormsg='.lang('some_error_occurred')); exit;
		}
	}
	private function process_car_delete($id) {
		$success=0;
		if($this->cars_model->delete_car($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_car_new() {
		$msg="";
		if(isset($_POST['add_car_btn'])) {
			$err=0;
			$ddata['car_id']=sanitize_input_post($this->input->post('car_id'));
			$ddata['owner']=sanitize_input_post($this->input->post('owner'));
			$ddata['model']=sanitize_input_post($this->input->post('model'));
			$ddata['type']=sanitize_input_post($this->input->post('type'));
			$ddata['details']=sanitize_input_post($this->input->post('details'));
			$ddata['status']=1;//sanitize_input_post($this->input->post('status'));
			
			if(!empty($_FILES['picture']['name']) && $err==0) {
				$this->load->library('upload');
				$field_name = "picture";
				$config['upload_path'] = 'uploads/cars/';
				$config['file_name'] = $ddata['car_id'];
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
				if(!$this->cars_model->is_exists_car($ddata['car_id'])) {
					if($this->cars_model->add_car($ddata)) {
						$msg="<div class='alert alert-success'>&quot;".$ddata['car_id']."&quot; ".lang('car_added')."</div>";
					} else {
						$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
					}
				} else { $msg="<div class='alert alert-danger'>&quot;".$ddata['car_id']."&quot; ".lang('car_exists')."</div>"; }
			} else { $msg=$startEnc.$msg.$endEnc; }
		}
		return $msg;
	}
	private function process_car_edit($id) {
		$msg="";
		if(isset($_POST['edit_car_btn'])) {
			$ddata['owner']=sanitize_input_post($this->input->post('owner'));
			$ddata['model']=sanitize_input_post($this->input->post('model'));
			$ddata['type']=sanitize_input_post($this->input->post('type'));
			$ddata['details']=sanitize_input_post($this->input->post('details'));
			$ddata['status']=1;//sanitize_input_post($this->input->post('status'));
			
			if(!empty($_FILES['picture']['name'])) {
				$this->load->library('upload');
				$field_name = "picture";
				$config['upload_path'] = 'uploads/cars/';
				$config['file_name'] = $this->cars_model->get_carid_by_id($id);
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
			
			if($this->cars_model->update_car($id,$ddata)) {
				$msg="<div class='alert alert-success'>&quot;".sanitize_input_post($this->input->post('car_id'))."&quot; ".lang('car_updated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
}
