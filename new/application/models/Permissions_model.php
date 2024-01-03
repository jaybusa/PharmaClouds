<?php
class Permissions_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
    }
	public function get_permissions_data($role) {
		//1=Spare Part Entry;2=Data Entry;3=Admin
		$data="";
		if($role==1) {	//Spare Part Entry
			$data['customers']=1;
			$data['customer_new']=1;
			$data['customer_edit']=1;
			
			$data['customer_types']=1;
			$data['customer_type_new']=1;
			$data['customer_type_edit']=1;
			
			$data['cars']=1;
			$data['car_new']=1;
			$data['car_edit']=1;
			
			$data['car_types']=1;
			$data['car_type_new']=1;
			$data['car_type_edit']=1;
			
			$data['spareparts']=1;
			$data['sparepart_new']=1;
			$data['sparepart_edit']=1;
			$data['sparepart_delete']=1;
			
			$data['check_ins']=1;
			$data['check_in_new']=1;
			$data['new_progress']=1;		//Can change New > Progress
			$data['progress_pending']=1;	//Can change Progress > Pending
			$data['pending_completed']=1;	//Can change Pending > Completed
			
			$data['new_stage']=1;
			$data['progress_stage']=1;
			$data['pending_stage']=1;
			$data['completed_stage']=1;
			
			$data['check_in_files']=1;
			$data['update_discount']=1;
			$data['update_payment']=1;
			
			$data['invoices']=1;
			$data['mail']=1;
			
		} elseif($role==2) {	//Data Entry
			$data['customers']=1;
			$data['customer_new']=1;
			$data['customer_edit']=1;
			
			$data['customer_types']=1;
			$data['customer_type_new']=1;
			$data['customer_type_edit']=1;
			
			$data['cars']=1;
			$data['car_new']=1;
			$data['car_edit']=1;
			
			$data['car_types']=1;
			$data['car_type_new']=1;
			$data['car_type_edit']=1;
			
			$data['check_ins']=1;
			$data['check_in_new']=1;
			$data['new_progress']=1;		//Can change New > Progress
			
			$data['check_in_files']=1;
			$data['new_stage']=1;
			
		} elseif($role==3) {	//Admin
			$data['customers']=1;
			$data['customer_new']=1;
			$data['customer_edit']=1;
			$data['customer_delete']=1;
			
			$data['customer_types']=1;
			$data['customer_type_new']=1;
			$data['customer_type_edit']=1;
			$data['customer_type_delete']=1;
			
			$data['cars']=1;
			$data['car_new']=1;
			$data['car_edit']=1;
			$data['car_delete']=1;
			
			$data['car_types']=1;
			$data['car_type_new']=1;
			$data['car_type_edit']=1;
			$data['car_type_delete']=1;
			
			$data['spareparts']=1;
			$data['sparepart_new']=1;
			$data['sparepart_edit']=1;
			$data['sparepart_delete']=1;
			
			$data['check_ins']=1;
			$data['check_in_new']=1;
			$data['check_in_edit']=1;
			$data['check_in_delete']=1;
			$data['check_in_completed_edit_delete']=1;
			$data['new_progress']=1;		//Can change New > Progress
			$data['progress_pending']=1;	//Can change Progress > Pending
			$data['pending_completed']=1;	//Can change Pending > Completed
			
			$data['new_stage']=1;
			$data['progress_stage']=1;
			$data['pending_stage']=1;
			$data['completed_stage']=1;
			
			$data['check_in_files']=1;
			$data['update_discount']=1;
			$data['update_payment']=1;
			
			$data['invoices']=1;
			$data['mail']=1;
			
			$data['users']=1;
			$data['user_new']=1;
			$data['user_edit']=1;
			$data['user_delete']=1;
			$data['reports']=1;
			
			$data['sessions']=1;
			$data['session_delete']=1;
			
			$data['otp']=1;
		}
		return $data;
	}
}