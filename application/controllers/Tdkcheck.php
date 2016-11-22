<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tdkcheck extends MY_Controller {

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

	public function index()
	{
    $this->db->select("auto_id, case_name, case_address, case_industry, case_alive, case_gacode, case_title, case_description, case_keyword, case_gacode_check")
             ->from("case_table");
    $this->finaldata["TDKdata"] = $this->db->get()->result_array();
		foreach ($this->finaldata["TDKdata"] as $key => $value) {
			if ($value["case_gacode_check"] === "1") {
				$this->finaldata["TDKdata"][$key]["case_gacode_check"] = "是";
			} else{ $this->finaldata["TDKdata"][$key]["case_gacode_check"] = "否";}
		}
    $this->twig->display("tdk_index",$this->finaldata);
	}

}
