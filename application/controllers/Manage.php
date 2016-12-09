<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
			$this->finaldata["page_name"] = "分類管理";
      $this->twig->display("manage_index",$this->finaldata);
    }
		public function backlink_type(){
			$this->finaldata["page_name"] = "外鏈種類管理";
			$this->twig->display("manage_backlink_type",$this->finaldata);
		}
		public function industry(){
			$this->finaldata["page_name"] = "產業分類管理";
			$this->twig->display("manage_industry",$this->finaldata);
		}
		public function program(){
			$this->finaldata["page_name"] = "方案分類管理";
			$this->twig->display("manage_program",$this->finaldata);
		}
		public function level(){
			$this->finaldata["page_name"] = "操作等級管理";
			$this->twig->display("manage_level",$this->finaldata);
		}

		public function grouplimit(){
			$this->finaldata["page_name"] = "外鏈群組上限管理";
			$this->twig->display("manage_grouplimit",$this->finaldata);
		}

}
