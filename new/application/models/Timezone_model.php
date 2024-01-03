<?php
class Timezone_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->main = $this->load->database('default', TRUE);
		$this->main->query("SET time_zone='+00:00'");
    }
}