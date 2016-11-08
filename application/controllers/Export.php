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
    }
    public function backlink_data(){
      if ($_POST["ContentIndex"]) {
        $filename = date("Y-n-d");
        $lastmonday = strtotime("Monday last Week",time());
        $lastsunday = strtotime("Monday this Week",time());

        foreach ($_POST["ContentIndex"] as $key => $eachGroupinCase) {
                $this->db->start_cache()
                         ->flush_cache()
                         ->select("backlink_content")
                         ->from("backlink_content_table")
                         ->where($eachGroupinCase)
                         ->stop_cache();
                $data["eachGroupContent"] = $this->db->get()->result_array()[0]["backlink_content"];
                $prefinaldata[$key]["eachGroupContent"] = explode("Seperate%%EachLink%%Here",$data["eachGroupContent"]);
                $prefinaldata[$key]["CaseName"] = $_POST["CaseName"][$key];
                // 之後標註已匯出用
                $forUpdateExport[$key]["backlinkGroup_id"] = $eachGroupinCase["group_id_incase"];
                $forUpdateExport[$key]["case_id"] = $eachGroupinCase["case_id"];
                $forUpdateExport[$key]["linktype_thisweek"] = $_POST["LinkType"][$key];
        }
        if (isset($_POST["SeperateTo"]) && !empty($_POST["SeperateTo"])) {
          $countdata = count($prefinaldata);
          $eachfiledata = floor($countdata/(intval($_POST["SeperateTo"])));
          for ($i=0; $i < (intval($_POST["SeperateTo"])); $i++) {
            $filename = date("Y-n-d")."-".($i + 1);
            if ($i == ((intval($_POST["SeperateTo"])) - 1)) {
              $finaldata = array_slice($prefinaldata, ($i * $eachfiledata));
            } else {
              $finaldata = array_slice($prefinaldata, ($i * $eachfiledata), $eachfiledata);
            }
            // 寫入txt檔
            $content = "";
            foreach ($finaldata as $key => $eachgroup) {
              $content .= $eachgroup["CaseName"]."\r\n\r\n\r\n\r\n";
              $content .= "連結原始碼:"."\r\n";
              foreach ($eachgroup["eachGroupContent"] as $key2 => $eachlink) {
                $content .= $eachlink."\r\n";
              }
              $content .= "------------------------------------------------------"."\r\n";
            }
            file_put_contents("upload/".$filename.".txt",$content);
            unset($finaldata);
            unset($content);
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
          $this->db->stop_cache();
          foreach ($forUpdateExport as $key => $eachGroup) {
            print_r($_POST["ContentIndex"][$key]);
            print_r($eachGroup);
            $this->db->flush_cache()
                     ->where($eachGroup)
                     ->where("submit_time >",$lastmonday)
                     ->where("submit_time <",$lastsunday)
                     ->update("backlink_submit_record",array("export"=> 1));
          }
          ob_end_clean();
          header('Content-type: application/octet-stream');
          header('Content-Transfer-Encoding: Binary');
          header('Content-disposition: attachment; filename=Link.zip');

          readfile($file);
          unlink($file);

        } else{
          $finaldata = $prefinaldata;
          // 寫入txt檔
          $content = "";
          foreach ($finaldata as $key => $eachgroup) {
            $content .= $eachgroup["CaseName"]."\r\n\r\n\r\n\r\n";
            $content .= "連結原始碼:"."\r\n";
            foreach ($eachgroup["eachGroupContent"] as $key2 => $eachlink) {
              $content .= $eachlink."\r\n";
            }
            $content .= "------------------------------------------------------"."\r\n";
          }
          // $this->finaldata["allLinkinFile"] = $finaldata;
          file_put_contents("upload/".$filename.".txt",$content);

          foreach ($forUpdateExport as $key => $eachGroup) {
            print_r($eachGroup);
          }

          $this->output->set_header("Content-type: text/plain");
          $this->output->set_header("Content-Disposition: attachment; filename=".$filename.".txt");
          readfile("upload/".$filename.".txt");
          unlink("upload/".$filename.".txt");
        }
    } else{
      header("Location: /backlink");
    }
  }

    public function test_seperate(){
      print_r($_POST);
    }

}
