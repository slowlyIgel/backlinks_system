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
				$this->db->insert_batch("case_table",$casedata_withkey);
        fclose($file);
				echo "done!!!";
      }
    }
}
