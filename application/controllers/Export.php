<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller {

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
				$this->thissunday = strtotime("Monday next Week",time());

    }

		public function backlink_one(){
			$filename = date("Y-n-d");
			$this->load->model("export_data");
			$preexportArray = $this->export_data->backlink_one_and_multi($_POST["ContentIndex"]);
			$this->load->library("textworks");
			$this->textworks->wirte_txtfile($preexportArray,$filename);

			foreach ($_POST["ContentIndex"] as $key => $eachGroup) {
				$update_data["case_id"] = $eachGroup["case_id"];
				$update_data["backlinkGroup_id"] = $eachGroup["group_id_incase"];
				$update_data["linktype_thisweek"] = $eachGroup["LinkType"];
				$this->db->flush_cache()
								 ->where($update_data);
				$this->db->update("backlink_submit_record",array("export"=>1));

			}


			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=".$filename.".txt");
			readfile("upload/".$filename.".txt");
			unlink("upload/".$filename.".txt");

		}

		public function backlink_multi(){
			$this->load->model("export_data");
			$preexportArray = $this->export_data->backlink_one_and_multi($_POST["ContentIndex"]);
			$this->load->library("textworks");
			$countdata = count($preexportArray);
			$eachfiledata = floor($countdata/(intval($_POST["SeperateTo"])));

			for ($i=0; $i < $_POST["SeperateTo"] ; $i++) {
				$filename = date("Y-n-d")."-".($i + 1);
				if ($i == ((intval($_POST["SeperateTo"])) - 1)) {
					$finaldata = array_slice($preexportArray, ($i * $eachfiledata));
				} else {
					$finaldata = array_slice($preexportArray, ($i * $eachfiledata), $eachfiledata);
				}
				$this->textworks->wirte_txtfile($finaldata,$filename);
				unset($finaldata);
				$fileList[$i]["path"] = "upload/".$filename.".txt";
				$fileList[$i]["name"] = $filename.".txt";
			}


			/*建立臨時壓縮檔*/
			$file = tempnam("tmp", "zip");
			$zip = new ZipArchive;
			$res = $zip->open($file, ZipArchive::CREATE|ZipArchive::OVERWRITE);
			if ($res!==true) { exit('壓縮錯誤');}

			foreach ($fileList as $eachFile){
				 $zip->addFile($eachFile["path"], $eachFile["name"]);
			}
			$zip->close();

			foreach($fileList as $value){
				unlink($value["path"]);
			}
			foreach ($_POST["ContentIndex"] as $key => $eachGroup) {
				$update_data["case_id"] = $eachGroup["case_id"];
				$update_data["backlinkGroup_id"] = $eachGroup["group_id_incase"];
				$update_data["linktype_thisweek"] = $eachGroup["LinkType"];
				$this->db->flush_cache()
								 ->where($update_data);
				$this->db->update("backlink_submit_record",array("export"=>1));

			}


			ob_end_clean();
			header('Content-type: application/octet-stream');
			header('Content-Transfer-Encoding: Binary');
			header('Content-disposition: attachment; filename=Link.zip');

			readfile($file);
			unlink($file);

		}

		public function tdkexcel(){
			$this->db->select("case_name, case_address, case_gacode, case_gacode_check, case_alive, case_title, case_description, case_keyword")
							 ->from("case_table");
			$data = $this->db->get()->result_array();
			foreach ($data as $key => $value) {
				if ($value["case_gacode_check"] === "1") {
					$data[$key]["case_gacode_check"] = "是";
				} else { $data[$key]["case_gacode_check"] = "否";}
			}
			$this->finaldata["data"] = $data;
			header("Content-type:application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename= test.xls");

			$this->twig->display("export_xls",$this->finaldata);
		}

		public function source_guide(){
			$today = date("Y-n-d");
			$export_time = time();
			$this->db->select("source_address, source_account, source_password, source_guide")
							 ->from("source_table")
							 ->where_in("source_id",$_POST["exportIDs"]);
			$guide["guide"] = $this->db->get()->result_array();

			$this->db->where("submit_time >",$this->thismonday)
							 ->where("submit_time <",$this->thissunday)
							 ->where_in("source_id",$_POST["exportIDs"])
							 ->update("source_submit_record",array("export_time" => $export_time));

			$this->db->where_in("source_id",$_POST["exportIDs"])
							 ->update("source_table",array("source_lastexport" => $export_time));


			header("Content-type:text/html");
			header("Content-Disposition: attachment; filename= guide".$today.".html");

			$this->twig->display("export_source_guide",$guide);

		}

		public function testxlsexport(){
			$this->output->set_header("Content-type:application/vnd.ms-excel");
			$this->output->set_header("Content-Disposition: attachment; filename= test.xls");
			$this->twig->display("export_xls");
		}

}
