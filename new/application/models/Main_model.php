<?php
class Main_model extends CI_Model {
	private $main=NULL;
	public function __construct() {
		$this->main = $this->load->database('default', TRUE);
		$language = LANGUAGE;
		$this->lang->load(array('terms_lang','messages_lang','mail_lang'), $language);
		$this->load->library(array('encrypt','email'));
    }
	// get_visitor_location() returns IP of the visitor and called when construct
	public function get_visitor_location($param="ip") {
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {$real_ip_adress=$_SERVER['HTTP_CLIENT_IP'];}
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {$real_ip_adress=$_SERVER['HTTP_X_FORWARDED_FOR'];}
		else {$real_ip_adress=$_SERVER['REMOTE_ADDR'];}
		$cip=$real_ip_adress;
		$url="http://ipinfo.io/".$cip;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		$data=json_decode($result);
		switch($param) {
			case 'country':
				return $data->country;
				break;
			case 'ip':
				return $cip;
				break;
			default:
				return false;
		}
	}
	//send_mail() function sends mail. array data is passed as parameter
	public function send_mail($mail_data) {
		//$mail_data contains array { from, from_name, to, cc, subject, message }
		//configure email
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['priority'] = 2;
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);
		//configure email ends
		$this->email->from($mail_data['from'],$mail_data['from_name']);
		$this->email->to($mail_data['to']);
		$this->email->subject($mail_data['subject']);
		$this->email->message($mail_data['message']);
		if($this->email->send()) {
			return true;
		} else { return false; }
	}
	
	private function generate_random_pass(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	public function change_password($user_id,$pass) {
		$udata = array(
		'password' => md5($pass),
        );
		$this->main->where('id', $user_id);
		return $this->main->update('users',$udata);
	}
	public function authUser($data) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('username',$data['user']);
		$this->main->where('password',$data['pass']);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0];
		else return false;
	}
	public function authUserPhone($data) {
		$this->main->select('phone');
		$this->main->from('users');
		$this->main->where('username',$data['user']);
		$this->main->where('password',$data['pass']);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0];
		else return false;
	}
	public function forgot_pass($data,$link) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('email',$data['email']);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) {
			$maildata['from']="noreply@webolute.com";
			$maildata['from_name']=lang('app_name')." Webmaster";
			$maildata['to']=$data['email'];
			$maildata['subject']="Your reset password link for ".lang('app_name');
			$maildata['message']=sprintf($this->lang->line('forgot_password_link_email_template'),$link);
			$this->send_mail($maildata);
			return true;
		} else {
			return false;
		}
	}
	public function reset_password($data) {
		$this->main->select('id');
		$this->main->from('users');
		$this->main->where('email',$data['email']);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) {
			$rndPass=$this->generate_random_pass();
			$this->change_password($result[0]->id,$rndPass);
			$maildata['from']="noreply@webolute.com";
			$maildata['from_name']=lang('app_name')." Webmaster";
			$maildata['to']=$data['email'];
			$maildata['subject']="Your new password for ".lang('app_name');
			$maildata['message']=sprintf($this->lang->line('forgot_password_email_template'),$rndPass);
			$this->send_mail($maildata);
			return true;
		} else {
			return false;
		}		
	}
	public function get_user_data($id) {
		$this->main->select('id,name,username,role,picture');
		$this->main->from('users');
		$this->main->where('id',$id);
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result[0];
		else return false;
	}
	public function add_activity($uid,$msg) {
		$this->main->set('uid',$uid);
		$this->main->set('ip',$this->get_visitor_location('ip'));
		$this->main->set('msg',$msg);
		$this->main->set('createdon',date('Y-m-d H:i:s'));
		return $this->main->insert('activities');
	}
	public function add_session($uid) {
		$this->main->set('user_id',$uid);
		$this->main->set('user_ip',$this->get_visitor_location('ip'));
		$this->main->set('login_time',date('Y-m-d H:i:s'));
		return $this->main->insert('sessions');
	}
	public function get_sessions($username=FALSE) {
		$this->main->select('s.id,s.user_ip,s.login_time,u.username');
		$this->main->from('sessions as s');
		$this->main->join('users as u','u.id=s.user_id');
		if($username!=FALSE) $this->main->where('u.username',$username);
		$this->main->order_by('s.id','desc');
		$query=$this->main->get();
		$result=$query->result();
		if(!empty($result)) return $result;
		else return false;
	}
	public function delete_session($id) {
		$this->main->where('id', $id);
		if($this->main->delete('sessions')) return true;
		return false;
	}
}