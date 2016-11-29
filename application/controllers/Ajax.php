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
					$data = $this->db->get()->result_array();
					if(!empty($data)){
						$this->output->set_output(json_encode(array("status"=>"success","print"=>json_encode($data))));
					} else {
						$this->output->set_output(json_encode(array("status"=>"fail","print"=>"查無此資料")));
					}
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
				$tdk["last_check_time"] = time();
				$this->db->select("case_title, case_description, case_keyword")
								 ->from("case_table")
								 ->where("auto_id",$_POST["caseID"]);
				$lastWeekData = $this->db->get()->result_array();
				$lastWeekData["last_week_title"] = $lastWeekData[0]["case_title"];
				$lastWeekData["last_week_description"] = $lastWeekData[0]["case_description"];
				$lastWeekData["last_week_keyword"] = $lastWeekData[0]["case_keyword"];
				unset($lastWeekData[0]);

				$this->db->where("auto_id",$_POST["caseID"])
								 ->update("case_table",$lastWeekData);

				$this->db->where("auto_id",$_POST["caseID"])
								 ->update("case_table",$tdk);


				foreach ($lastWeekData as $key => $value) {
					$tdk[$key] = $value;
				}
				unset($lastWeekData);
								//  print_r($tdk);
								if (!empty($tdk["case_gacode_check"]) && $tdk["case_gacode_check"] ===1) {
									$tdk["case_gacode_check"] = "是";
								} else{ $tdk["case_gacode_check"] = "否"; }
				$tdk["last_check_time"] = date("Y-n-d",$tdk["last_check_time"]);

				$this->output->set_output(json_encode($tdk));
			}
		}


		public function manage_delete(){
			if ($_POST["manageFocus"]) {
				$table = $_POST["manageFocus"];
				if ($_POST["manageFocus"] == "type_backlink") {
					$this->db->from("backlink_submit_record")
									 ->where("linktype_thisweek",$_POST["idFocus"]);
				} else{
					$incase = str_replace("type","case",$_POST["manageFocus"]);
					$this->db->from("case_table")
									 ->where($incase,$_POST["idFocus"]);
				}
				$data = $this->db->get();
				if ($data->num_rows()) {
					echo "還有資料屬於這個分類，不可刪除";
				} else{
					$this->db->where("auto_typeID",$_POST["idFocus"])
									 ->delete($table);
					echo "分類已刪除";
					}
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

		public function manage_add(){
			if ($_POST["manageFocus"]) {
				$table = $_POST["manageFocus"];
				$this->db->insert($table,array("Type_name"=>$_POST["nameFocus"]));
			}
		}

		public function manage_checkoldpw(){
			if ($_POST["password"]) {
				$this->db->from("admin_privilege")
								 ->where("admin_id",$_POST["account"]);
				$data = $this->db->get()->result_array();
				if(count($data) == 1 && password_verify($_POST["password"],$data[0]["admin_pw"])){
					echo "good";
				} else{print_r($data[0]["admin_pw"]);}
			}
		}

		public function manage_changepassword(){
			if($_POST["newone"]){
				$this->db->where("admin_id",$_POST["thisadmin"])
								 ->update("admin_privilege",array("admin_pw"=> password_hash($_POST["newone"],PASSWORD_DEFAULT)));
								 echo "changed";
			}
		}


		public function manage_addAccount(){
			if(count($_POST) === 3){
				$data["admin_name"] = $_POST["accountName"];
				$data["admin_pw"] = password_hash($_POST["accountPW"],PASSWORD_DEFAULT);
				$data["total_privilege"] = intval($_POST["accountPrivilege"]);
				$this->db->insert("admin_privilege",$data);
				echo "done!!";
			}
		}



		public function case_search_bygroup(){
			if($_POST["search_key"]){
				$this->db->select("case_name, auto_id")
								 ->from("case_table")
								 ->where($_POST["search_key"]);
				$data = $this->db->get()->result_array();
				$this->output->set_output(json_encode($data));

			}
		}


		public function privilege_upload(){
			if ($_POST["privilege"]) {
				$this->db->update_batch("admin_privilege",$_POST["privilege"],"admin_id");
			}
		}


		public function manage_deleteaccount(){
			if ($_POST["deleteAccount"]) {
				$this->db->where("admin_id",$_POST["deleteAccount"])
								 ->delete("admin_privilege");

			}
		}

}
