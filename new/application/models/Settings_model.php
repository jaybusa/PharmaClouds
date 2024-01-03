<?php
class Settings_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
    }
	public function get_otp_status() {
		$this->main->select('value');
		$this->main->from('settings');
		$this->main->where('key','otp_status');
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0]->value;
		else return false;
	}
	public function check_current_pass($pass,$username) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('username',$username);
		$this->main->where('password',md5($pass));
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return true;
		else return false;
	}
	public function update_otp($status) {
		$udata = array(
		'value' => $status,
        );
		$this->main->where('key', 'otp_status');
		return $this->main->update('settings',$udata);
	}
}