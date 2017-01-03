<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_source extends MY_Controller {

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

				$this->thismonday = strtotime("Monday this Week",time());
				$this->thissunday = strtotime("Sunday this Week",time());


				$this->load->model("source_model");
				// 取得共用類別們
				$this->finaldata["type_source_status"] = $this->source_model->get_sourcetypeInfo("type_source_status");
				$this->finaldata["type_source_anchor"] = $this->source_model->get_sourcetypeInfo("type_source_anchor");
				$this->finaldata["type_source_indexstatus"] = $this->source_model->get_sourcetypeInfo("type_source_indexstatus");
				$this->finaldata["type_source_kpnbuild"] = $this->source_model->get_sourcetypeInfo("type_source_kpnbuild");
				$this->finaldata["type_source_lang"] = $this->source_model->get_sourcetypeInfo("type_source_lang");
				$this->finaldata["type_source_level"] = $this->source_model->get_sourcetypeInfo("type_source_level");
				$this->finaldata["type_source_sitetype"] = $this->source_model->get_sourcetypeInfo("type_source_sitetype");
				$this->finaldata["type_source_topic"] = $this->source_model->get_sourcetypeInfo("type_source_topic");


    }

    public function source_dataedit($id){
      $this->db->where("source_id",$_POST["sourcedata"]["source_id"])
               ->update("source_table",$_POST["sourcedata"]["changedata"]);
      echo "good";
    }

    public function source_guideedit($id){
      $this->db->where("source_id",$id)
               ->update("source_table",$_POST);
      echo "good";
    }

		public function sourcedata_add(){
			if ($_POST["newsourcedata"]) {
				$this->db->insert("source_table",$_POST["newsourcedata"]);
				$message = $this->db->error();
				if (!empty($message["message"])) {
					echo $message["message"];
				} else{ echo "good"; }
			}
		}

		public function delete_source(){
			if (is_numeric($_POST["id"])) {
				$table = array("source_table","source_submit_record");
				$this->db->where("source_id",$_POST["id"])
								 ->delete($table);
				$message = $this->db->error();
				if (!empty($message["message"])) {
					echo $message["message"];
				}
			}
		}

		public function export_list_thisweek(){
			if ($_POST["export_list"]) {
				$submit_time = time();
				foreach ($_POST["export_list"] as $key => $value) {
					$data[$key]["source_id"] = $value;
					$data[$key]["submit_time"] = $submit_time;
				}
				unset($_POST["export_list"]);
				$this->db->insert_batch("source_submit_record",$data);
				$message = $this->db->error();
				if (!empty($message["message"])) {
					echo $message["message"];
				}
			}
		}

		public function search_source(){
			$this->load->model("source_model");

			if (!empty($_POST["searchdata"]["source_address"])) {
				$search_address = $_POST["searchdata"]["source_address"];
				unset($_POST["searchdata"]["source_address"]);
			} else{$search_address = NULL;}

			$searchresult = $this->source_model->get_source_table_likeindex($search_address,$_POST["searchdata"]);
			if(empty($searchresult)){	exit; }

			foreach ($searchresult as $key => $value) {
				$searchresult["$key"]["source_level"] = $this->finaldata["type_source_level"][ $this->finaldata["type_source_sitetype"][$value["source_sitetype"]]["Type_level"] ]["Type_name"];
				$searchresult["$key"]["source_topic"] = $this->finaldata["type_source_topic"][$value["source_topic"]]["Type_name"];
				$searchresult["$key"]["source_status"] = $this->finaldata["type_source_status"][$value["source_status"]]["Type_name"];
				$searchresult["$key"]["source_indexstatus"] = $this->finaldata["type_source_indexstatus"][$value["source_indexstatus"]]["Type_name"];
				$searchresult["$key"]["source_kpnbuild"] = $this->finaldata["type_source_kpnbuild"][$value["source_kpnbuild"]]["Type_name"];
				$searchresult["$key"]["source_lang"] = $this->finaldata["type_source_lang"][$value["source_lang"]]["Type_name"];
				$searchresult["$key"]["source_anchor"] = $this->finaldata["type_source_anchor"][$value["source_anchor"]]["Type_name"];
				$searchresult["$key"]["source_sitetype"] = $this->finaldata["type_source_sitetype"][$value["source_sitetype"]]["Type_name"];

				if (is_null($value["source_lastexport"])) {
					$searchresult["$key"]["source_lastexport"] = "---";
				} else{
					$searchresult["$key"]["source_lastexport"] = date("Y-n-d",$value["source_lastexport"]);
				}
			}
			print_r(json_encode($searchresult));


		}


}
