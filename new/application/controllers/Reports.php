<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
	private $data;
	 function __construct() {
        parent::__construct();
		$this->data['ctrl']=$this;
		$this->data['config']['version']=$this->config->item('version');
		$this->data['config']['lang'] = LANG;
		$this->data['config']['language'] = LANGUAGE;
		$this->data['config']['dir'] = DIRECTION;
		$this->lang->load(array('terms_lang','messages_lang'), $this->data['config']['language']);
		$this->load->model(array('main_model','permissions_model','reports_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['reports'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='reports';
		$this->data['title']=lang('reports');
		
		$this->data['stage']=$this->input->get('stage');		//stage
		$this->data['first_date']=$this->input->get('first_date');		//time start
		$this->data['second_date']=$this->input->get('second_date');		//time end
		$this->data['order_by']=$this->input->get('order_by');
		$this->data['report_results']=$this->reports_model->get_report_check_ins($this->data['stage'],$this->data['first_date'],$this->data['second_date'],$this->data['order_by']);
		$this->load->view('inc/header',$this->data);
		$this->load->view('report-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
}
