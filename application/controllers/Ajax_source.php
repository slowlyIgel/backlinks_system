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


}
