<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('APP_NAME','CareCare');
define('APP_URL','carcare.dc.net.sa');
class Invoices extends CI_Controller {
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
		$this->load->model(array('main_model','permissions_model','invoices_model','cico_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['invoices'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	
	public function view($ecico_id) {
		$this->data['page']='view-invoice';
		$this->data['title']=lang('view')." ".lang('invoice');
		
		$cico_id=url_id_decode($ecico_id);
		$this->data['invoice_data']=$this->invoices_model->get_invoice_data($cico_id);
		if(($this->data['invoice_data']->stage==3 || $this->data['invoice_data']->stage==4) && $this->data['invoice_data']->check_out!=NULL) {
			$this->data['spare_parts']=$this->cico_model->get_check_in_spare_parts($cico_id,1);
			
			$this->load->view('inc/header',$this->data);
			$this->load->view('invoice-view',$this->data);
			$this->load->view('inc/footer',$this->data);
		} else {
			$this->load->view('inc/header',$this->data);
			$this->load->view('invoice-not-ready',$this->data);
			$this->load->view('inc/footer',$this->data);
		}
	}
	public function view_pdf($ecico_id){		
		$cico_id=url_id_decode($ecico_id);
		$this->data['invoice_data']=$this->invoices_model->get_invoice_data($cico_id);
		if(($this->data['invoice_data']->stage==3 || $this->data['invoice_data']->stage==4) && $this->data['invoice_data']->check_out!=NULL) {
			$this->data['spare_parts']=$this->cico_model->get_check_in_spare_parts($cico_id,1);
			
			$pdf=$this->init_invoice_pdf();
			
			$html=$this->load->view('invoice-view-pdf',$this->data,true);
			ob_start();
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->Output('invoice_'.$cico_id.'.pdf', 'I');
		} else {
			$this->load->view('inc/header',$this->data);
			$this->load->view('invoice-not-ready',$this->data);
			$this->load->view('inc/footer',$this->data);
		}
	}
	private function init_invoice_pdf(){
		// Include the main TCPDF library (search for installation path).
		require_once('tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(APP_NAME);
		$pdf->SetTitle('Customer Invoice');
		$pdf->SetSubject(lang('invoice'));
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData("logo.png", 20, APP_NAME, APP_URL);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// ---------------------------------------------------------

		if(DIRECTION=="rtl") {
		// set some language dependent data:
		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'rtl';
		$lg['a_meta_language'] = 'sa';
		$lg['w_page'] = 'page';

		// set some language-dependent strings (optional)
		$pdf->setLanguageArray($lg);
		}
		
		// set font
		$pdf->SetFont('dejavusans', '', 12);
		
		// add a page
		$pdf->AddPage();
		
		return $pdf;
	}
}
