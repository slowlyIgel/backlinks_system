<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_data extends CI_Model {

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

		public function backlink_one_and_multi($allindex){
						// 取群組內容
			$this->db->flush_cache()
							 ->start_cache()
							 ->select("case_id, group_id_incase, backlink_content")
							 ->from("backlink_content_table")
							 ->stop_cache();
			foreach ($allindex as $key => $eachGroupinCase) {
				// $where = "case_id = '".$eachGroupinCase["case_id"]."' AND group_id_incase = '".$eachGroupinCase["group_id_incase"]."'";
			$this->db->or_group_start()
							 ->where("case_id",$eachGroupinCase["case_id"])
							 ->where("group_id_incase",$eachGroupinCase["group_id_incase"])
							 ->group_end()
							 ->stop_cache();
							 }
			$contentdata = $this->db->get()->result_array();

			// 取網站description

			$this->db->flush_cache()
							 ->start_cache()
							 ->select("auto_id, case_description")
							 ->from("case_table")
							 ->stop_cache();
			foreach ($allindex as $key => $eachGroupinCase) {
				$this->db->or_where("auto_id",$eachGroupinCase["case_id"]);
							 }
			$description_data = $this->db->get()->result_array();



			foreach ($allindex as $groupkey => $eachGroupinCase) {
				$prefinaldata[$groupkey]["CaseName"] = $eachGroupinCase["CaseName"];

				foreach ($contentdata as $key2 => $value2) {
					if ($value2["case_id"] === $eachGroupinCase["case_id"] && $value2["group_id_incase"] === $eachGroupinCase["group_id_incase"]) {
						$prefinaldata[$groupkey]["linkContent"] = explode("Seperate%%EachLink%%Here",$value2["backlink_content"]);
						break;
					}
				}

				foreach ($description_data as $key3 => $value3) {
					if ($value3["auto_id"] === $eachGroupinCase["case_id"]) {
						$prefinaldata[$groupkey]["description"] = $value3["case_description"];
						break;
					}
				}
			}
				return $prefinaldata;
		}


}
