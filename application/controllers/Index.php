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
			$this->db->select("n_id,n_name")
							 ->from("client_table");
			$finaldata["everycase"] = $this->db->get()->result_array();
			$this->twig->display("case_index",$finaldata);
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

		$this->db->from("type_backlink");
		$backlink_type = $this->db->get()->result_array();
		$finaldata["backlink_type"] = $backlink_type;
		$finaldata["original_data"] = $data[0]["n_link"];

		// 區分群組
		$eachgroup = explode("Seperate%%GROUP%%Here",$data[0]["n_link"]);
		// 區分群組名稱
		foreach ($eachgroup as $key => $unseperatename_group) {
			list($eachgroup2[$key]["groupname"],$eachgroup2[$key]["grouplink"]) = explode("Seperate%%GROUPNMAE%%Here",$unseperatename_group);
			$eachgroup2[$key]["groupname"] = preg_replace('/(.*)--([^-]*)-(.*)/','${2}',$eachgroup2[$key]["groupname"] );
			$finaldata["group"][$key]["groupname"] = $eachgroup2[$key]["groupname"];
		}
		// 區分群組內連結和錨文本
		foreach ($eachgroup2 as $key => $find_grouplink) {
			preg_match_all("/>([^<]*)<\/a/",$find_grouplink["grouplink"],$anchor);
			preg_match_all("/href=\"([^\"]*)\"/",$find_grouplink["grouplink"],$urls);
			preg_match_all("/title=\"([^\"]*)\"/",$find_grouplink["grouplink"],$titles);
			foreach ($urls[1] as $key2 => $value) {
				$finaldata["group"][$key]["grouplink"][$key2]["urls"] = $value;
				$finaldata["group"][$key]["grouplink"][$key2]["anchor"] = $anchor[1][$key2];
				$finaldata["group"][$key]["grouplink"][$key2]["titles"] = $titles[1][$key2];
			}

		}
		$finaldata["n_id"] = $n_id;
		$this->twig->display("changeLinkSytle",$finaldata);
	}
	public function case_search(){
		$this->twig->display("case_search");
	}
	public function casedata_edit($id){
		$this->db->select("n_id, n_name, web_links, ga_code")
						 ->from("client_table")
						 ->where("n_id",$id);
		$finaldata["casedata"] = $this->db->get()->result_array();

		$this->db->select("auto_industryID, industry_name")
						 ->from("type_industry");
		$finaldata["industry_tpye"] = $this->db->get()->result_array();

		$this->db->from("type_level");
		$finaldata["level_tpye"] = $this->db->get()->result_array();

		$this->twig->display("casedata_edit",$finaldata);
	}
	public function logout(){
		$this->session->sess_destroy();
		header("location: /");

	}
}
