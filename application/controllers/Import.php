<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends MY_Controller {

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
    public function casedata(){
      if ($_FILES) {
				// 檢查是不是csv(或前面檢查)
				if (mb_check_encoding(file_get_contents($_FILES["casedata"]["tmp_name"]), 'UTF-8')){
					$file = fopen($_FILES["casedata"]["tmp_name"], 'r');
					$i = 0;
					while (($line = fgetcsv($file)) !== FALSE) {
						// $line is an array of the csv elements
						// 檢查匯入的編碼是不是UTF8
						// foreach ($line as $key => $value) {
						// 	$line[$key] = iconv("big5","UTF-8",$value);
						// }
						$casedata_withkey[$i]["case_name"] = $line[0];
						$casedata_withkey[$i]["case_address"] = $line[1];
						$casedata_withkey[$i]["case_backlink"] = $line[2];
						$casedata_withkey[$i]["case_gacode"] = $line[3];
						$casedata_withkey[$i]["case_industry"] = $line[4];
						$casedata_withkey[$i]["case_program"] = $line[5];
						$casedata_withkey[$i]["case_level"] = $line[6];
						$i ++;
					}
					unset($casedata_withkey[0]);
					$this->db->insert_batch("case_table",$casedata_withkey);
					fclose($file);
					echo "done!!!";
				} else{ echo "請用UTF-8編碼儲存csv檔案並且重新上傳";}

      }
    }


		public function add_backlink_insource_record(){
			if ($_FILES) {
				if (mb_check_encoding(file_get_contents($_FILES["link_insource"]["tmp_name"]), 'UTF-8')) {
					$file = fopen($_FILES["link_insource"]["tmp_name"], 'r');

					// 檢查案件名稱是否存在於系統上
					$case_list = fgetcsv($file);
					unset($case_list[0]);
					$case_count = count($case_list);
					$this->db->select("auto_id")
									 ->from("case_table")
									 ->where_in("case_name",$case_list);
					$get_case_autoid = $this->db->get();
					if ($case_count != $get_case_autoid->num_rows()) {
						echo "應搜尋到".$case_count."筆案件但只搜尋到".$get_case_autoid->num_rows()."筆";
						exit;
					}
					foreach ($get_case_autoid->result_array() as $key => $eachcase) {
						$caseid_thisweek[] = $eachcase;
					}
					// print_r($caseid_thisweek);

					// 檢查資源站是否存在系統上
				$sourcenum_incsv = 0;
					while (($line = fgetcsv($file)) !== FALSE) {
						$source_list[$sourcenum_incsv] = $line[0];
						for ($i=0; $i < $case_count ; $i++) {
							$link_incsv[$sourcenum_incsv][$i] = $line[($i+1)];
						}
						$sourcenum_incsv ++;
					}
					// print_r($source_list);
					// print_r($link_incsv);

					// 檢查資源站清單有沒有重複網址，有的話停止匯入
					$duplicate_source_check = array_filter(array_count_values($source_list),function($value){return ($value > 1);});
					if (!empty($duplicate_source_check)) {
							$alert = "";
						foreach ($duplicate_source_check as $key => $value) {
							$alert .= "於資源站上搜尋到".$value."筆".$key."\n";
						}
						echo $alert;
						exit;
					}

					$source_count = count($source_list);
					$this->db->select("source_id")
									 ->from("source_table")
									 ->where_in("source_address",$source_list)
									 ->order_by("source_id");//核對時按照順序排好連結才不會對錯
					$get_source_autoid = $this->db->get();
					if ($source_count != $get_source_autoid->num_rows()) {
						echo "應搜尋到".$source_count."筆資源站但只搜尋到".$get_source_autoid->num_rows()."筆";
						exit;
					}

					foreach ($get_source_autoid->result_array() as $key => $eachsource) {
						$sourceid_thisweek[] = $eachsource;
					}
					// print_r($sourceid_thisweek);
					$importdate = time();

					foreach ($link_incsv as $sourcekey => $links_eachsource) {
						foreach ($links_eachsource as $casekey => $link_eachcase) {
							if (strpos($link_eachcase,"ttp")) {
										$finalinsert[] = array(
											"case_id" => $caseid_thisweek[$casekey]["auto_id"],
											"source_id" => $sourceid_thisweek[$sourcekey]["source_id"],
											"linkpage" => $link_eachcase,
											"import_date" => $importdate
										);
							}
						}
					}
					$this->db->insert_batch("backlink_add_history",$finalinsert);
					echo "done";
				} else{ echo "請用UTF-8編碼儲存csv檔案並且重新上傳";}
			}
		}
}
