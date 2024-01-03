<?php
class Reports_model extends CI_Model {
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
	public function get_report_check_ins($stage='',$first_date='',$second_date='',$order_by='id',$status='') {
		$this->main->select('ci.id,c.car_id,ci.check_in,ci.check_out,ci.stage,ci.status');
		$this->main->from('cico as ci');
		$this->main->join('cars as c','c.id=ci.car_id');
		if($stage!='') $this->main->where('ci.stage',$stage);
		if($first_date!='') $this->main->where('check_in >=', $first_date);
		if($second_date!='') $this->main->where('check_in <=', $second_date);
		if($status!='') $this->main->where('ci.status',$status);
		if($order_by!='') $this->main->order_by($order_by." desc");
		$query=$this->main->get();
		if ($query != FALSE) {
			$result=$query->result();
		} else { $result=""; }
		if(!empty($result))  return $result;
		else return false;
	}
}
?>