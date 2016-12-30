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
				$this->thissunday = strtotime("Sunday this Week",time());

    }
  //   public function backlink_data(){
  //     if ($_POST["ContentIndex"]) {
  //       $filename = date("Y-n-d");
  //       $lastmonday = strtotime("Monday last Week",time());
  //       $lastsunday = strtotime("Monday this Week",time());
	//
  //       foreach ($_POST["ContentIndex"] as $key => $eachGroupinCase) {
  //               $this->db->start_cache()
  //                        ->flush_cache()
  //                        ->select("backlink_content")
  //                        ->from("backlink_content_table")
  //                        ->where($eachGroupinCase)
  //                        ->stop_cache();
  //               $data["eachGroupContent"] = $this->db->get()->result_array()[0]["backlink_content"];
  //               $prefinaldata[$key]["eachGroupContent"] = explode("Seperate%%EachLink%%Here",$data["eachGroupContent"]);
  //               $prefinaldata[$key]["CaseName"] = $_POST["CaseName"][$key];
	// 							$this->db->flush_cache()
	// 											 ->select("case_description")
	// 											 ->from("case_table")
	// 											 ->where("auto_id",$eachGroupinCase["case_id"]);
	// 							$description_data = $this->db->get()->result_array();
	// 							$prefinaldata[$key]["description"] = $description_data[0]["case_description"];
  //               // 之後標註已匯出用
  //               $forUpdateExport[$key]["backlinkGroup_id"] = $eachGroupinCase["group_id_incase"];
  //               $forUpdateExport[$key]["case_id"] = $eachGroupinCase["case_id"];
  //               $forUpdateExport[$key]["linktype_thisweek"] = $_POST["LinkType"][$key];
  //       }
  //       if (isset($_POST["SeperateTo"]) && !empty($_POST["SeperateTo"])) {
  //         $countdata = count($prefinaldata);
  //         $eachfiledata = floor($countdata/(intval($_POST["SeperateTo"])));
  //         for ($i=0; $i < (intval($_POST["SeperateTo"])); $i++) {
  //           $filename = date("Y-n-d")."-".($i + 1);
  //           if ($i == ((intval($_POST["SeperateTo"])) - 1)) {
  //             $finaldata = array_slice($prefinaldata, ($i * $eachfiledata));
  //           } else {
  //             $finaldata = array_slice($prefinaldata, ($i * $eachfiledata), $eachfiledata);
  //           }
  //           // 寫入txt檔
  //           $content = "";
  //           foreach ($finaldata as $key => $eachgroup) {
  //             $content .= $eachgroup["CaseName"]."\r\n\r\n\r\n\r\n";
	// 						$content .= "描述:"."\r\n";
	// 						$content .= $eachgroup["description"]."\r\n";
  //             $content .= "連結原始碼:"."\r\n";
  //             foreach ($eachgroup["eachGroupContent"] as $key2 => $eachlink) {
  //               $content .= $eachlink."\r\n";
  //             }
  //             $content .= "------------------------------------------------------"."\r\n";
  //           }
  //           file_put_contents("upload/".$filename.".txt",$content);
  //           unset($finaldata);
  //           unset($content);
  //           $fileList[$i]["path"] = "upload/".$filename.".txt";
  //           $fileList[$i]["name"] = $filename.".txt";
	//
  //         }
  //         /*建立臨時壓縮檔*/
  //         $file = tempnam("tmp", "zip");
  //         $zip = new ZipArchive;
  //         $res = $zip->open($file, ZipArchive::CREATE|ZipArchive::OVERWRITE);
  //         if ($res!==true) { exit('壓縮錯誤');}
	//
  //         foreach ($fileList as $eachFile){
  //            $zip->addFile($eachFile["path"], $eachFile["name"]);
  //         }
  //         $zip->close();
	//
  //         foreach($fileList as $value){
  //           unlink($value["path"]);
  //         }
  //         foreach ($forUpdateExport as $key => $eachGroup) {
  //           $this->db->flush_cache()
  //                    ->where($eachGroup)
  //                    ->where("submit_time >",$lastmonday)
  //                    ->where("submit_time <",$lastsunday)
  //                    ->update("backlink_submit_record",array("export"=> 1));
  //         }
  //         ob_end_clean();
  //         $this->output->set_header('Content-type: application/octet-stream');
  //         $this->output->set_header('Content-Transfer-Encoding: Binary');
  //         $this->output->set_header('Content-disposition: attachment; filename=Link.zip');
	//
  //         readfile($file);
  //         unlink($file);
	//
  //       }
  //   } else{
  //     header("Location: /backlink");
  //   }
  // }
	//
  //   public function test_seperate(){
	// 						$this->db->flush_cache()
	// 										 ->start_cache()
	// 										 ->select("case_id, group_id_incase, backlink_content")
	// 										 ->from("backlink_content_table")
	// 										 ->stop_cache();
	// 						foreach ($_POST["ContentIndex"] as $key => $eachGroupinCase) {
	// 							// $where = "case_id = '".$eachGroupinCase["case_id"]."' AND group_id_incase = '".$eachGroupinCase["group_id_incase"]."'";
	// 						$this->db->or_group_start()
	// 										 ->where("case_id",$eachGroupinCase["case_id"])
	// 										 ->where("group_id_incase",$eachGroupinCase["group_id_incase"])
	// 										 ->group_end()
	// 										 ->stop_cache();
	// 										 }
	// 						$test = $this->db->get()->result_array();
	// 							foreach ($_POST["ContentIndex"] as $groupkey => $eachGroupinCase) {
	// 									foreach ($test as $key => $value) {
	// 										if ($value["case_id"] === $eachGroupinCase["case_id"] && $value["group_id_incase"] === $eachGroupinCase["group_id_incase"]) {
	// 											$_POST["ContentIndex"][$groupkey]["linkContent"] = $value["backlink_content"];
	// 											break;
	// 										}
	// 							}
	// 						}
	// 						print_r($_POST["ContentIndex"]);
  //   }

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
