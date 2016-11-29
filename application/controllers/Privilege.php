<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privilege extends MY_Controller {

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

				$this->db->select("admin_id, admin_name, total_privilege")
								 ->from("admin_privilege");
				$adminData = $this->db->get()->result_array();

				$this->db->from("page_privilege");
				$page_privilege = $this->db->get()->result_array();

				foreach ($adminData as $key => $value) {
					$key2 = array_search("adminderadmin",$value);
					if($key2 != ""){unset($adminData[$key]);break;}
					$adminData[$key]["total_privilege"] = intval($value["total_privilege"]);
				}

				foreach ($page_privilege as $key => $value) {
					$page_privilege[$key]["privilege_id"] = intval($value["privilege_id"]);
				}

				$this->finaldata["adminData"] = $adminData;
				$this->finaldata["page_privilegeData"] = $page_privilege;


    }

	public function index()
	{
		$this->finaldata["page_name"] = "權限編輯";

		$this->twig->display("privilege_index",$this->finaldata);
	}

	public function edit()
	{
		$this->finaldata["page_name"] = "帳號資料編輯";

		$this->twig->display("privilege_edit",$this->finaldata);
	}


}
