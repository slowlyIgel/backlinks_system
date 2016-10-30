<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

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
		if (!isset($this->session->admin)) {
			$this->twig->display("login_page");
		} else {
			echo "ready to start backlinks";
			$this->twig->display("client_index");
		}
	}
	public function login($loginID){
		$login = array("admin" =>$loginID );
		$this->session->set_userdata($login);
		header("location: /");
	}
	public function explode_link($n_id){
		$this->db->select("n_link")
						 ->from("client_table")
						 ->where("n_id",$n_id);
		$data = $this->db->get()->result_array();
		$finaldata["original_data"] = $data[0]["n_link"];

		// 區分群組
		$eachgroup = explode("Seperate%%GROUP%%Here",$data[0]["n_link"]);
		// 區分群組名稱
		foreach ($eachgroup as $key => $unseperatename_group) {
			list($eachgroup2[$key]["groupname"],$eachgroup2[$key]["grouplink"]) = explode("Seperate%%GROUPNMAE%%Here",$unseperatename_group);
			$eachgroup2[$key]["groupname"] = preg_replace('/<!--([^-]*)-(.*)/','${1}',$eachgroup2[$key]["groupname"] );
			$finaldata["group"][$key]["groupname"] = $eachgroup2[$key]["groupname"];
		}
		// 區分群組內連結和錨文本
		foreach ($eachgroup2 as $key => $find_grouplink) {
			preg_match_all("/>([^<]*)<\/a/",$find_grouplink["grouplink"],$anchor);
			preg_match_all("/href=\"([^\"]*)\"/",$find_grouplink["grouplink"],$links);
			foreach ($links[1] as $key2 => $value) {
				$finaldata["group"][$key]["grouplink"][$key2]["link"] = $value;
				$finaldata["group"][$key]["grouplink"][$key2]["anchor"] = $anchor[1][$key2];
			}

		}
		$finaldata["n_id"] = $n_id;
		$this->twig->display("changeLinkSytle",$finaldata);
	}
}
