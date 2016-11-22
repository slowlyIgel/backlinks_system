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
				$this->load->library("find_tdk");

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

		public function case_search(){
			if (!empty($_POST["searchKey"])) {
					$this->db->select("auto_id, case_name")
									 ->from("case_table")
									 ->like("case_name",$_POST["searchKey"]);
					$this->output->set_output(json_encode(array("status"=>"success","print"=>json_encode($this->db->get()->result_array()))));
			} else {
				$this->output->set_output(json_encode(array("status"=>"fail","print"=>"請輸入關鍵字")));
			}
		}

		public function add_newcase(){
			if ($_POST["newcasedata"]) {
				$this->db->insert_batch("case_table",$_POST["newcasedata"]);
				$casename = "";
				foreach ($_POST["newcasedata"] as $key => $value) {
					$casename .= " ".$value["case_name"]." ";
				}
				echo "case".$casename."加了";
			}
		}

		public function delete_case(){
			if (is_numeric($_POST["case_id"])) {
				$table_array = array("backlink_content_table","backlink_submit_record");
				$this->db->where("case_id",$_POST["case_id"])
								 ->delete("backlink_content_table");
				$this->db->where("case_id",$_POST["case_id"])
									->delete("backlink_content_table");

				$this->db->where("auto_id",$_POST["case_id"])
									->delete("case_table");
				$this->output->set_output(json_encode(array("status"=>"success","alert"=>"已刪除")));
			} else {
				$this->output->set_output(json_encode(array("status"=>"fail","alert"=>"案件編號有誤")));
			}
		}



		public function checkTDK(){
			if ($_POST["caseAddress"] && $_POST["caseID"]) {
				$tdk = $this->find_tdk->get_tdktest($_POST["caseAddress"],$_POST["gacode"]);
				$this->db->where("auto_id",$_POST["caseID"])
								 ->update("case_table",$tdk);
								//  print_r($tdk);
								if ($tdk["case_gacode_check"] ===1) {
									$tdk["case_gacode_check"] = "是";
								} else{ $tdk["case_gacode_check"] = "否"; }
				$this->output->set_output(json_encode($tdk));
			}
		}


		public function manage_delete(){
			if ($_POST["manageFocus"]) {
				$table = $_POST["manageFocus"];
				$incase = str_replace("type","case",$_POST["manageFocus"]);
				$this->db->from("case_table")
								 ->where($incase,$_POST["idFocus"]);
				$data = $this->db->get();
				if ($data->num_rows()) {
					echo "還有資料屬於這個分類，不可刪除";
				} else{
					$this->db->where("auto_typeID",$_POST["idFocus"])
									 ->delete($table);
					echo "分類已刪除";
				}
				}
				if ($_POST["manageFocus"] == "type_backlink") {
					echo "hi";
				}
			}


		public function manage_rename(){
			if ($_POST["manageFocus"]) {
				$table = $_POST["manageFocus"];
				$this->db->from($table)
								 ->where("auto_typeID",$_POST["idFocus"]);
				$data = $this->db->get();
				if ($data->num_rows()) {
					$this->db->where("auto_typeID",$_POST["idFocus"])
									 ->update($table,array("Type_name"=>$_POST["nameFocus"]));

				}
			}
		}

}
