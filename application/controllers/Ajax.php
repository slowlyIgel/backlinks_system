<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

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
		public function upload_newtypelink_oldver($n_id){
			if (isset($_POST["change"])) {
				$data["case_backlink"] = $_POST["change"];
				$this->db->where("auto_id",$n_id)
								 ->update("case_table",$data);

			} else{
				echo "bad!!";
			}
		}
		public function upload_linkgroupcontent($n_id){
			if ($_POST["allGroupStorge"]) {
				foreach ($_POST["allGroupStorge"] as $key => $eachgroup) {
					$this->db->from("backlink_content_table")
									 ->where("case_id",$eachgroup["case_id"])
									 ->where("group_id_incase",$eachgroup["group_id_incase"]);
					$checkGroupExist = $this->db->get()->result_array();
					if (empty($checkGroupExist)) {
						$this->db->insert("backlink_content_table",$eachgroup);
					} else{
						$this->db->where("case_id",$eachgroup["case_id"])
										 ->where("group_id_incase",$eachgroup["group_id_incase"])
										 ->update("backlink_content_table",$eachgroup);

					}
				}
			}
		}

		public function submit_thisweek_record(){
			if ($_POST) {
				$everyRecord = json_decode($_POST["everyRecord"],true);
				foreach ($everyRecord as $key => $eachRecord) {
					$everyRecord[$key]["submit_time"] = time();
					$everyRecord[$key]["export"] = 0;
					$this->db->insert("backlink_submit_record",$everyRecord[$key]);
				}
				echo "good";
			}
		}

		public function submit_casedata(){
			if ($_POST) {
				$this->db->where("auto_id",$_POST["casedata"]["case_id"])
								 ->update("case_table",$_POST["casedata"]["data"]);
								 echo "good";
			}
		}

		public function pre_export(){
			if ($_POST["exportData"]) {
					$this->db->start_cache()
									 ->flush_cache()
									 ->select("backlink_content")
									 ->from("backlink_content_table")
									 ->where($_POST["exportData"])
									 ->stop_cache();
					$data["eachGroupContent"] = $this->db->get()->result_array()[0]["backlink_content"];

					$this->db->start_cache()
									 ->flush_cache()
									 ->select("case_address")
									 ->from("case_table")
									 ->where("auto_id",$_POST["exportData"]["case_id"])
									 ->stop_cache();
					$basicUrl = $this->db->get()->result_array()[0]["case_address"];
					$this->load->library("find_tdk");
					$curlTD = $this->find_tdk->get_tdktest($basicUrl);
					$finaldata["TDK"] = $curlTD;

					$finaldata["eachGroupContent"] = explode("Seperate%%EachLink%%Here",$data["eachGroupContent"]);
					$finaldata["eachCaseName"] = $_POST["exportCaseName"];
				unset($data);
				$this->finaldata["allLinkinFile"] = $finaldata;
				$flashdataName = "finaldata".$_POST["exportData"]["case_id"]."_".$_POST["exportData"]["group_id_incase"];
				$this->session->set_flashdata($flashdataName, $this->finaldata);

			}
		}

		public function export(){
			// $this->output->set_header("Content-type:application/octet-stream");
			// $this->output->set_header("Content-Disposition: attachment; filename = text.txt");
			$test = $this->session->flashdata();
			// $this->twig->display("export_txt",$test);
			print_r($test);
		}
}
