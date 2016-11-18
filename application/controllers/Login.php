<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
   var $data = array();
    public function __construct()
    {
        parent::__construct();
    }

	public function login_page(){
		$this->twig->display("login_page");
	}
	public function login(){
		if (!empty($_POST["admin_name"])) {
			$this->db->select("admin_id, admin_name, total_privilege")
							 ->from("admin_privilege")
							 ->where("admin_name",$_POST["admin_name"])
							 ->where("admin_pw",$_POST["admin_pw"]);
		 $login = $this->db->get();
			if($login->num_rows()){
				$this->session->set_userdata($login->result_array()[0]);
			}
		}
		// header("location: /");
	}

}
