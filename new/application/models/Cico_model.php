<?php
class Cico_model extends CI_Model {
	private $main=NULL,$userid;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
		$this->load->helper('cookie');
		if(get_cookie('carp_token')) {
			$this->userid=$this->encrypt->decode(get_cookie('carp_token'));
		}
    }
	public function get_check_ins($status=FALSE,$order_by='id desc') {
		$this->main->select('ci.id,c.car_id,ci.check_in,ci.check_out,ci.stage,ci.status');
		$this->main->from('cico as ci');
		$this->main->join('cars as c','c.id=ci.car_id');
		if($status!=FALSE) $this->main->where('ci.status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_check_in_data($id){
		$this->main->select('ci.id,c.car_id as car_id,ci.car_id as cid,ci.check_in,ci.check_out,ci.discount,ci.paid,ci.stage,ci.status,ci.last_modified_on,u.name as last_modified_by,u.username as last_modified_by_user');
		$this->main->from('cico as ci');
		$this->main->join('cars as c','c.id=ci.car_id');
		$this->main->join('users as u','u.id=ci.last_modified_by','left');
		$this->main->where('ci.id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function get_car_checkin_data($car_id,$status=FALSE,$order_by='id desc') {
		$this->main->select('ci.id,c.car_id,ci.check_in,ci.check_out,ci.stage,ci.status');
		$this->main->from('cico as ci');
		$this->main->join('cars as c','c.id=ci.car_id');
		$this->main->where('ci.car_id',$car_id);
		if($status!=FALSE) $this->main->where('ci.status',$status);
		$this->main->order_by($order_by);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_check_in_spare_parts($id,$status=FALSE) {
		$this->main->select('id,cico_id,name,hand_price,invoice_price,invoice_id,invoice_file');
		$this->main->from('spareparts');
		$this->main->where('cico_id',$id);
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by('id','desc');
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function get_spare_parts_price($cico_id,$status=FALSE) {
		$this->main->select('sum(hand_price) as net_hand_price,sum(invoice_price) as net_invoice_price');
		$this->main->from('spareparts');
		$this->main->where('cico_id',$cico_id);
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by('id','desc');
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function update_discount($id,$data) {
		$udata = array(
		'discount' => $data['discount'],
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
	public function update_stage($id,$data) {
		$udata = array(
		'stage' => $data['stage'],
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
	public function update_checkout($id,$data) {
		$udata = array(
		'stage' => $data['stage'],
		'paid' => $data['paid'],
		'check_out' => date('Y-m-d H:i:s',strtotime($data['check_out'])),
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
	public function update_payment($id,$data) {
		$udata = array(
		'paid' => $data['paid'],
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
	public function add_check_in($data) {
		$this->main->set('car_id',$data['car_id']);
		$this->main->set('check_in',date('Y-m-d H:i:s',strtotime($data['check_in'])));
		return $this->main->insert('cico');
	}
	public function delete_check_in($id) {
		$this->main->where('id', $id);
		if($this->main->delete('cico')) return true;
		return false;
		
	}
	public function update_check_in($id,$data) {
		if(empty($data['check_out'])) $data['check_out']=NULL;
		$udata = array(
		'stage' => $data['stage'],
		'check_in' => $data['check_in'],
		'check_out' => date('Y-m-d H:i:s',strtotime($data['check_out'])),
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
	
	/* For Invoices */
	public function get_check_in_invoice_data($id) {
		$this->main->select('ci.id,c.car_id as car_id,ci.car_id as cid,ci.check_in,ci.check_out,ci.discount,ci.stage,ci.status');
		$this->main->from('cico as ci');
		$this->main->join('cars as c','c.id=ci.car_id');
		$this->main->where('ci.id',$id);
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result[0];
		else return false;
	}
	public function get_check_in_invoice_spare_parts($id,$status=FALSE) {
		$this->main->select('id,cico_id,name,hand_price,invoice_price,invoice_id,invoice_file');
		$this->main->from('spareparts');
		$this->main->where('cico_id',$id);
		if($status!=FALSE) $this->main->where('status',$status);
		$this->main->order_by('id','desc');
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
	public function update_last_modified($id) {
		$udata = array(
		'last_modified_on' => date('Y-m-d H:i:s'),
		'last_modified_by' => $this->userid,
        );
		$this->main->where('id', $id);
		return $this->main->update('cico',$udata);
	}
}