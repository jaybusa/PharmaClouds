<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spare_parts extends CI_Controller {
	private $data;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->model(array('main_model','permissions_model','cico_model','spareparts_model'));
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['spareparts'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='spare-parts';
		$this->data['title']=lang('spare_parts');

		$this->data['spareparts']=$this->spareparts_model->get_spareparts();
		$this->load->view('inc/header',$this->data);
		$this->load->view('spareparts',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['sparepart_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-spare-part';
		$this->data['title']=lang('edit')." ".lang('spare_part');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_sparepart_edit($id);
		$this->data['sparepart_data']=$this->spareparts_model->get_sparepart_data($id);
		$this->data['checkin_data']=$this->cico_model->get_check_in_data($this->data['sparepart_data']->cico_id);
		
		$this->load->view('inc/header',$this->data);
		$this->load->view('sparepart-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view($eid) {
		$this->data['page']='view-spare-part';
		$this->data['title']=lang('view')." ".lang('spare_part');
		
		$id=url_id_decode($eid);
		$this->data['sparepart_data']=$this->spareparts_model->get_sparepart_data($id);
		$this->data['checkin_data']=$this->cico_model->get_check_in_data($this->data['sparepart_data']->cico_id);
		
		$this->load->view('inc/header',$this->data);
		$this->load->view('sparepart-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function delete($eid)
	{
		if(!isset($this->data['permission_data']['sparepart_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-spare-part';
		$this->data['title']=lang('delete')." ".lang('spare_part');
		
		$id=url_id_decode($eid);
		$sparepart_data=$this->spareparts_model->get_sparepart_data($id);
		if(file_exists("uploads/checkin/".$sparepart_data->invoice_file)) {
			unlink("uploads/checkin/".$sparepart_data->invoice_file);
		}
		$chk=$this->process_sparepart_delete($id);
		if($chk==1) header('location:'.base_url().'check-ins/view/'.url_id_encode($sparepart_data->cico_id).'?successmsg=%22'.$sparepart_data->name.'%22 '.lang('sparepart_deleted'));
		else header('location:'.base_url().'check-ins/view/'.url_id_encode($sparepart_data->cico_id).'?errormsg='.lang('some_error_occurred'));
	}
	private function process_sparepart_delete($id) {
		$success=0;
		if($this->spareparts_model->delete_sparepart($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_sparepart_edit($id) {
		$msg="";
		if(isset($_POST['edit_sparepart_btn'])) {
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['invoice_id']=sanitize_input_post($this->input->post('invoice_id'));
			$ddata['invoice_price']=sanitize_input_post($this->input->post('invoice_price'));
			$ddata['hand_price']=sanitize_input_post($this->input->post('hand_price'));
			$ddata['details']=sanitize_input_post($this->input->post('details'));
			if($this->spareparts_model->update_sparepart($id,$ddata)) {
				$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('sparepart_updated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
}
