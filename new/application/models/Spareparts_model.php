<?php
class Spareparts_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt'));
    }
	public function get_spareparts($status=FALSE,$order_by='id desc') {
		$this->main->select('id,name,hand_price,invoice_price,status');
		$this->main->from('spareparts');
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query == FALSE) $result=false; else if($query->num_rows() > 0){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_sparepart_data($id){
		$this->main->select('id,name,cico_id,invoice_id,invoice_price,invoice_file,hand_price,details,status,createdon');
		$this->main->from('spareparts');
		$this->main->where('id',$id);
		$query=$this->main->get();
		if ($query == FALSE) $result=false; else if($query->num_rows() > 0){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function is_exists_sparepart($name,$cico_id) {
		$this->main->select('count(*) as c');
		$this->main->from('spareparts');
		$this->main->where('name',$name);
		$this->main->where('cico_id',$cico_id);
		$query=$this->main->get();
		if ($query == FALSE) $result=false; else if($query->num_rows() > 0){
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result) && $result[0]->c > 0)  return true;
		else return false;
	}
	public function add_sparepart($data) {
		$this->main->set('name',$data['name']);
		$this->main->set('cico_id',$data['cico_id']);
		$this->main->set('invoice_id',$data['invoice_id']);
		$this->main->set('invoice_price',$data['invoice_price']);
		$this->main->set('invoice_file',$data['invoice_file']);
		$this->main->set('hand_price',$data['hand_price']);
		$this->main->set('details',$data['details']);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		return $this->main->insert('spareparts');
	}
	public function delete_sparepart($id) {
		$this->main->where('id', $id);
		if($this->main->delete('spareparts')) return true;
		return false;
		
	}
	public function update_sparepart($id,$data) {
		if(!isset($data['status'])) $data['status']=0;
		$udata = array(
		'name' => $data['name'],
		'invoice_id' => $data['invoice_id'],
		'hand_price' => $data['hand_price'],
		'invoice_price' => $data['invoice_price'],
		'details' => $data['details'],
        );
		$this->main->where('id', $id);
		return $this->main->update('spareparts',$udata);
	}
}