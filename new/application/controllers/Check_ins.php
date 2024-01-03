<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('UPLOAD_DIR',  'uploads'.DIRECTORY_SEPARATOR . 'checkin' . DIRECTORY_SEPARATOR); 
define('APP_NAME','CareCare');
define('APP_URL','carcare.dc.net.sa');

class Check_ins extends CI_Controller {
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
		$this->load->model(array('main_model','permissions_model','cico_model','cars_model','spareparts_model'));
		$this->load->helper('cookie');
		//Get User Data
		if(get_cookie('carp_token')) {
			$userid=$this->encrypt->decode(get_cookie('carp_token'));
			$this->data['user_data']=$this->main_model->get_user_data($userid);
			$this->data['permission_data']=$this->permissions_model->get_permissions_data($this->data['user_data']->role);
		} else { header('location:'.base_url().'login'); exit; }
		if(!isset($this->data['permission_data']['check_ins'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
    }
	public function index()
	{
		$this->data['page']='check-ins';
		$this->data['title']=lang('check_ins');

		$this->data['check_ins']=$this->cico_model->get_check_ins();
		$this->load->view('inc/header',$this->data);
		$this->load->view('check-ins',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function add()
	{
		if(!isset($this->data['permission_data']['check_in_new'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='new-check-in';
		$this->data['title']=lang('new')." ".lang('check_in');
		
		$this->data['alertMsg']=$this->process_check_in_new();
		$this->data['cars']=$this->cars_model->get_cars(1,'car_id asc');
		$this->load->view('inc/header',$this->data);
		$this->load->view('check-in-new',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function edit($eid)
	{
		if(!isset($this->data['permission_data']['check_in_edit'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='edit-check-in';
		$this->data['title']=lang('edit')." ".lang('check_in');
		
		$id=url_id_decode($eid);
		$this->data['alertMsg']=$this->process_check_in_edit($id);
		$this->data['check_in_data']=$this->cico_model->get_check_in_data($id);
		$this->data['cars']=$this->cars_model->get_cars(1,'car_id asc');
		if($this->data['check_in_data']->stage==4 && !isset($this->data['permission_data']['check_in_completed_edit_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->load->view('inc/header',$this->data);
		$this->load->view('check-in-edit',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view($eid) {
		$this->data['page']='view-check-in';
		$this->data['title']=lang('view')." ".lang('check_in');
		
		$id=url_id_decode($eid);
		$this->data['check_in_data']=$this->cico_model->get_check_in_data($id);
		
		$this->data['alertMsg'][]=$this->process_state_change($id);
		$this->data['alertMsg'][]=$this->process_checkout($id);
		$this->data['alertMsg'][]=$this->process_update_payment($id);
		$this->data['alertMsg'][]=$this->process_update_discount($id);
		$this->data['alertMsg'][]=$this->process_add_spare_part($id);
		$this->data['alertMsg'][]=$this->process_add_car_images($id);
		
		$this->data['check_in_data']=$this->cico_model->get_check_in_data($id);
		$this->data['spare_parts']=$this->cico_model->get_check_in_spare_parts($id);
		$this->data['price']=$this->cico_model->get_spare_parts_price($id);
		if($this->data['check_in_data']->stage==2 && isset($this->data['permission_data']['spareparts'])) $this->data['scanner']=1;
		
		$this->load->view('inc/header',$this->data);
		$this->load->view('check-in-view',$this->data);
		$this->load->view('inc/footer',$this->data);
	}
	public function view_pdf($eid){		
		$id=url_id_decode($eid);
		$this->data['check_in_data']=$this->cico_model->get_check_in_data($id);
		$this->data['spare_parts']=$this->cico_model->get_check_in_spare_parts($id);
		$this->data['price']=$this->cico_model->get_spare_parts_price($id);
		$pdf=$this->init_check_in_pdf();
		
		$html=$this->load->view('check-in-view-pdf',$this->data,true);
		ob_start();
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('check_in_'.$id.'.pdf', 'I');
	}
	private function init_check_in_pdf(){
		// Include the main TCPDF library (search for installation path).
		require_once('tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(APP_NAME);
		$pdf->SetTitle('Check In Status');
		$pdf->SetSubject(lang('check_in'));
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
	private function process_checkout($id) {
		$msg="";
		if(isset($_POST['checkout_btn'])) {
			if(!isset($this->data['permission_data']['progress_pending'])) {
				header('location:'.base_url().'unauthorized-access'); exit;
			}
			$stage=3;	//means pending
			$ddata['check_out']=sanitize_input_post($this->input->post('check_out'));
			$ddata['paid']=sanitize_input_post($this->input->post('paid'));
			$ddata['stage']=$stage;
			if($this->cico_model->update_checkout($id,$ddata)) {
				$msg="<div class='alert alert-success'>".lang('invoice_generated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
	}
	private function process_update_payment($id) {
		$msg="";
		if(isset($_POST['update_payment_btn'])) {
			if(!isset($this->data['permission_data']['update_payment'])) {
				header('location:'.base_url().'unauthorized-access'); exit;
			}
			$ddata['paid']=sanitize_input_post($this->input->post('upaid'));
			if($this->cico_model->update_payment($id,$ddata)) {
				$msg="<div class='alert alert-success'>".lang('payment_updated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
	}
	private function process_state_change($id) {
		$msg="";
		if(isset($_POST['state_in_progress']) && isset($this->data['permission_data']['new_progress'])) {
			if($this->input->post('cc_id')==$id) {
				$stage=2;	//means in progress
				$ddata['stage']=$stage;
				if($this->cico_model->update_stage($id,$ddata)) {
					$msg="<div class='alert alert-success'>".lang('work_in_progress')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		if(isset($_POST['state_pending']) && isset($this->data['permission_data']['progress_pending'])) {
			if($this->input->post('cc_id')==$id) {
				$stage=3;	//means pending
				$ddata['stage']=$stage;
				if($this->cico_model->update_stage($id,$ddata)) {
					$msg="<div class='alert alert-success'>".lang('work_done_invoice_generated')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		if(isset($_POST['state_complete']) && isset($this->data['permission_data']['pending_completed'])) {
			if($this->input->post('cc_id')==$id) {
				$stage=4;	//means completed
				$ddata['stage']=$stage;
				if($this->cico_model->update_stage($id,$ddata)) {
					$msg="<div class='alert alert-success'>".lang('payment_done')."</div>";
				} else {
					$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
				}
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
	private function process_update_discount($id) {
		$msg="";
		if(isset($_POST['update_discount_btn'])) {
			if(!isset($this->data['permission_data']['update_discount'])) {
				header('location:'.base_url().'unauthorized-access'); exit;
			}
			$ddata['discount']=sanitize_input_post($this->input->post('discount'));
			if($this->cico_model->update_discount($id,$ddata)) {
				$msg="<div class='alert alert-success'>".lang('discount_updated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
	private function process_add_car_images($id) {
		$msg="";
		$success=0;
		if(isset($_POST['add_car_image_btn'])) {
			if(!isset($this->data['permission_data']['check_in_files'])) {
				header('location:'.base_url().'unauthorized-access'); exit;
			}
			$ddata['nof']=sanitize_input_post($this->input->post('nof'));
			if($ddata['nof'] > 0 && $ddata['nof']<21) {
				$ddata['files']=$this->upload_files($id);
				$success=1;
			}
			if($success==1) {
				$msg="<div class='alert alert-success'>".lang('image_added')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
	private function upload_files($checkin_id) {
		$this->load->library('upload');
		$files_name="";
		$files = $_FILES;
		$cpt = count($_FILES['car_image']['name']);
		for($i=0; $i<$cpt; $i++)
		{
			$_FILES['car_image']['name']= $files['car_image']['name'][$i];
			$_FILES['car_image']['type']= $files['car_image']['type'][$i];
			$_FILES['car_image']['tmp_name']= $files['car_image']['tmp_name'][$i];
			$_FILES['car_image']['error']= $files['car_image']['error'][$i];
			$_FILES['car_image']['size']= $files['car_image']['size'][$i];    
			$this->upload->initialize($this->set_upload_options(time(),$checkin_id));
			$upload_chk=$this->upload->do_upload('car_image');
			$this->cico_model->update_last_modified($checkin_id);
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$files_name .= $upload_data['file_name'].",";
		}
		if(!empty($files_name)) {
			return $files_name;
		} else {
			return NULL;
		}
	}
	private function set_upload_options($file_name,$checkin_id){
		//upload an image options
		if (!file_exists('uploads/checkin/'.$checkin_id)) {
			mkdir('uploads/checkin/'.$checkin_id, 0777, true);
			mkdir('uploads/checkin/'.$checkin_id.'/images', 0777, true);
		}
		$config = array();
		$config['file_name'] = $file_name;
		$config['upload_path'] = 'uploads/checkin/'.$checkin_id.'/images/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;
		return $config;
	}
	public function delete_image() {
		if(!isset($this->data['permission_data']['check_in_files'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$path=url_id_decode($_POST['file']);
		unlink($path);
		$this->cico_model->update_last_modified($id);
		echo "Deleted";
	}
	private function process_add_spare_part($id) {
		$msg="";
		if(isset($_POST['add_spare_part_btn'])) {
			if(!isset($this->data['permission_data']['sparepart_new'])) {
				header('location:'.base_url().'unauthorized-access'); exit;
			}
			$err=0;
			$ddata['cico_id']=$id;
			$ddata['name']=sanitize_input_post($this->input->post('name'));
			$ddata['invoice_id']=sanitize_input_post($this->input->post('invoice_id'));
			$ddata['invoice_price']=sanitize_input_post($this->input->post('invoice_price'));
			$ddata['hand_price']=sanitize_input_post($this->input->post('hand_price'));
			$ddata['details']=sanitize_input_post($this->input->post('details'));
			if(!$this->spareparts_model->is_exists_sparepart($ddata['name'],$id)) {
				if(!empty($_FILES['invoice_file']['name']) && $err==0) {
					$this->load->library('upload');
					$field_name = "invoice_file";
					$config['upload_path'] = 'uploads/checkin/'.$ddata['cico_id']."/";
					$config['file_name'] = $id."_".time();
					$config['allowed_types'] = 'gif|jpg|png|pdf';
					$config['max_size']     = '1024';
					$config['max_width'] = '1200';
					$config['max_height'] = '1200';
					$this->upload->initialize($config);
					if($this->upload->do_upload($field_name)) {
						$ddata['invoice_file']=$this->upload->data('file_name');
					} else {
						$err=1;
						$startEnc="<div class='alert alert-danger'>";
						$msg.=$this->upload->display_errors();
						$endEnc="</div>";
					}
				} elseif(!empty($_POST['scanned_file_name'])) {
					$ddata['invoice_file']=sanitize_input_post($this->input->post('scanned_file_name'));
				} else { $ddata['invoice_file']=NULL; }
				if($err==0) {
					if($this->spareparts_model->add_sparepart($ddata)) {
						$msg="<div class='alert alert-success'>&quot;".$ddata['name']."&quot; ".lang('sparepart_added')."</div>";
						$this->cico_model->update_last_modified($id);
					} else {
						$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
					}
				} else {
					$msg=$startEnc.$msg.$endEnc;
				}
			} else { $msg="<div class='alert alert-danger'>".$ddata['name']." ".lang('sparepart_exists')."</div>"; }
		}
		return $msg;
	}
	public function delete($eid) {
		if(!isset($this->data['permission_data']['check_in_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$this->data['page']='delete-check-in';
		$this->data['title']=lang('delete')." ".lang('check_in');
		
		$id=url_id_decode($eid);
		$check_in_data=$this->cico_model->get_check_in_data($id);
		if($this->data['check_in_data']->stage==4 && !isset($this->data['permission_data']['check_in_completed_edit_delete'])) {
			header('location:'.base_url().'unauthorized-access'); exit;
		}
		$chk=$this->process_check_in_delete($id);
		if($chk==1) header('location:'.base_url().'check-ins?successmsg=%22'.$check_in_data->name.'%22 '.lang('checkin_deleted'));
		else header('location:'.base_url().'check-ins?errormsg='.lang('some_error_occurred'));
	}
	private function process_check_in_delete($id) {
		$success=0;
		if($this->cico_model->delete_check_in($id)) {
			$success=1;
		} else {
			$success=0;
		}
		return $success;
	}
	private function process_check_in_new() {
		$msg="";
		if(isset($_POST['add_check_in_btn'])) {
			$ddata['car_id']=sanitize_input_post($this->input->post('car_id'));
			$ddata['check_in']=sanitize_input_post($this->input->post('check_in'));
			if($this->cico_model->add_check_in($ddata)) {
				$msg="<div class='alert alert-success'>".lang('check_in_added')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
		}
		return $msg;
	}
	private function process_check_in_edit($id) {
		$msg="";
		if(isset($_POST['edit_check_in_btn'])) {
			$permission_chk=1;
			$ddata['check_in']=sanitize_input_post($this->input->post('check_in'));
			$ddata['stage']=sanitize_input_post($this->input->post('stage'));
			$ddata['check_out']=sanitize_input_post($this->input->post('check_out'));
			if($ddata['stage']==2 && !isset($this->data['permission_data']['new_progress'])) $permission_chk=0;
			if($ddata['stage']==3 && !isset($this->data['permission_data']['progress_pending'])) $permission_chk=0;
			if($ddata['stage']==4 && !isset($this->data['permission_data']['pending_completed'])) $permission_chk=0;
			if($permission_chk==1) {
			if($this->cico_model->update_check_in($id,$ddata)) {
				$msg="<div class='alert alert-success'>".lang('check_in_updated')."</div>";
			} else {
				$msg="<div class='alert alert-danger'>".lang('some_error_occurred')."</div>";
			}
			} else {
				$msg="<div class='alert alert-danger'>".lang('permission_denied')."</div>";
			}
		}
		return $msg;
	}
	
	
	//Scanning Process
	public function process_sparepart($eid) {
		$id=url_id_decode($eid);
		$this->processRequest($id);
		return;
	}

	private function processRequest($id) { 
		header('Content-type: text/plain; charset=utf-8'); 
		$fileNames = $this->handleUploadedFiles($id);
		if(is_array($fileNames) && count($fileNames) > 0) { 
			foreach($fileNames as $index => $filename) { 
				if($index > 0) { 
					print('\n'); 
				} 
				if(strpos($filename, 'ERROR:') === 0) { 
					print($filename); // error mesg 
				} else { 
					print( str_replace(DIRECTORY_SEPARATOR, "/", substr(UPLOAD_DIR, strlen(__DIR__))) . $filename); 
				}
			} 
		} else { 
			print('ERROR: no file uploaded'); 
		} 
		return; 
	}

	private function handleUploadedFiles($id) {
		$fileNames = array();
		if(is_array($_FILES)) {
			foreach($_FILES as $name => $fileSpec) { 
				if(! is_array($fileSpec)) { 
					continue; 
				} 

				if(is_array($fileSpec['tmp_name'])) { // multiple files with same name 
					foreach($fileSpec['tmp_name'] as $index => $value) { 
						if($fileSpec['error'][$index] == UPLOAD_ERR_OK) { 
							// Provides: <body text='black'>
							$fileSpec['name'][$index] = str_replace("asprise", "carcare", $fileSpec['name'][$index]);
							array_push($fileNames, $this->doHandleUploadedFile($fileSpec['name'][$index], $fileSpec['type'][$index], $fileSpec['tmp_name'][$index], $fileSpec['error'][$index], $fileSpec['size'][$index], $id)); 
						} 
					}
				} else { 
					if($fileSpec['error'] == UPLOAD_ERR_OK) { 
						$fileSpec['name'] = str_replace("asprise", "carcare", $fileSpec['name']);
						array_push($fileNames, $this->doHandleUploadedFile($fileSpec['name'], $fileSpec['type'], $fileSpec['tmp_name'], $fileSpec['error'], $fileSpec['size'], $id)); 
					} 
				} 
			} 
		} 

		return $fileNames; 
	} 
 
	private function doHandleUploadedFile($name, $type, $tmp_name, $error, $size, $id) { 
		if($error != UPLOAD_ERR_OK) { 
			return 'ERROR: upload error code: ' . $error . ' for file ' . $name; 
		} 

		$extension = pathinfo($name, PATHINFO_EXTENSION); 
		if($extension == null || strlen($extension) == 0) { 
			$extension = $this->getImageExtensionByMimeType($type); 
			if($extension != null) { 
				$name .= '.' . $extension; 
			} 
		} 

		if($extension == null || strlen($extension) == 0 ||  (strlen($extension) > 0 && (!in_array(strtolower($extension), array('jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff', 'pdf'))))) { 
			return 'ERROR: extension not allowed: ' . $extension . ' for file ' . $name; 
		} 

		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $name); 
		// don't overwrite an existing file 
		$i = 0; 
		$parts = pathinfo($name); 
		while (file_exists(UPLOAD_DIR . $name)) { 
			$i++; 
			$name = $parts["filename"] . "-" . $i . "." . $parts["extension"]; 
		} 

		if(!file_exists(UPLOAD_DIR)) { 
			mkdir(UPLOAD_DIR); // try to mkdir 
		}
		if(!file_exists(UPLOAD_DIR . $id . "/")) {
			mkdir(UPLOAD_DIR . $id . "/");
		}

		$moved = move_uploaded_file($tmp_name, UPLOAD_DIR .$id."/". $name); 
		if($moved) { 
			chmod(UPLOAD_DIR .$id."/". $name, 0644); 
		} else { 
			return 'ERROR: moving uploaded file failed' . ' for file ' . $name; 
		} 

		return $name; 
	} 

	private function getCurrentPageURL() { 
		$defaultPort = "80"; 
		$pageURL = 'http'; 
		if ($_SERVER["HTTPS"] == "on") { 
			$pageURL .= "s"; 
			$defaultPort = "443"; 
		} 
		$pageURL .= "://"; 
		if ($_SERVER["SERVER_PORT"] != $defaultPort) { 
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
		} else { 
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
		} 
		return $pageURL; 
	} 

	private function getImageExtensionByMimeType($mimeType) { 
		$mimeType = strtolower($mimeType); 
		switch($mimeType) { 
			case 'image/jpeg': return "jpg"; 
			case 'image/pjpeg': return 'jpg'; 
			case 'image/png': return 'png'; 
			case 'image/gif': return 'gif'; 
			case 'image/tiff': return 'tif'; 
			case 'image/x-tiff': return 'tif'; 
			case 'application/pdf': return 'pdf'; 
			default: return ''; 
		} 
	}

}