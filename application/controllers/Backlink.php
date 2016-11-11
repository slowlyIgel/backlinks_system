<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backlink extends MY_Controller {

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
				$this->db->from("type_backlink");
				$backlink_type = $this->db->get()->result_array();
				$this->finaldata["backlink_type"] = $backlink_type;

    }

	public function index()
	{
		$lastmonday = strtotime("Monday last Week",time());
		$lastsunday = strtotime("Monday this Week",time());
		$this->db->select("backlink_submit_record.submit_time, backlink_submit_record.case_id, backlink_submit_record.backlinkGroup_id,backlink_submit_record.linktype_thisweek, backlink_submit_record.export, case_table.case_name, case_table.case_industry")
						 ->from("backlink_submit_record")
	 				 	 ->join("case_table","case_table.auto_id = backlink_submit_record.case_id")
	 				 	 ->where("backlink_submit_record.submit_time >",$lastmonday)
	 				 	 ->where("backlink_submit_record.submit_time <",$lastsunday);
		$data = $this->db->get()->result_array();
		foreach ($data as $key => $eachsubmit) {
			// 取得備註
			$this->db->flush_cache()
							 ->select("remark_content")
							 ->from("backlink_content_table")
		 				 	 ->where("case_id",$eachsubmit["case_id"])
		 				 	 ->where("group_id_incase",$eachsubmit["backlinkGroup_id"]);
			$data[$key]["remark"] = $this->db->get()->result_array()[0]["remark_content"];
			if ($eachsubmit["export"]) {
				$data[$key]["export"] = "是";
			} else{
				$data[$key]["export"] = "否";
			}
			$data[$key]["submit_time_date"] = date("Y-n-d",$eachsubmit["submit_time"]);
		}
		$this->finaldata["everySubmitRecord"] = $data;
		$this->twig->display("backlink_all",$this->finaldata);

	}

	public function nonexport()
	{
		$lastmonday = strtotime("Monday last Week",time());
		$this->db->select("backlink_submit_record.submit_time, backlink_submit_record.case_id, backlink_submit_record.backlinkGroup_id,backlink_submit_record.linktype_thisweek, backlink_submit_record.export, case_table.case_name, case_table.case_industry")
						 ->from("backlink_submit_record")
						 ->join("case_table","case_table.auto_id = backlink_submit_record.case_id")
						 ->where("backlink_submit_record.export", 0)
						 ->where("backlink_submit_record.submit_time <",$lastmonday);
		$data = $this->db->get()->result_array();
		foreach ($data as $key => $eachsubmit) {
			// 取得備註
			$this->db->flush_cache()
							 ->select("remark_content")
							 ->from("backlink_content_table")
							 ->where("case_id",$eachsubmit["case_id"])
							 ->where("group_id_incase",$eachsubmit["backlinkGroup_id"]);
			$data[$key]["remark"] = $this->db->get()->result_array()[0]["remark_content"];
			if ($eachsubmit["export"]) {
				$data[$key]["export"] = "是";
			} else{
				$data[$key]["export"] = "否";
			}
			$data[$key]["submit_time_date"] = date("Y-n-d",$eachsubmit["submit_time"]);
		}
		$this->finaldata["everySubmitRecord"] = $data;
		$this->twig->display("backlink_all",$this->finaldata);

	}


	public function thisweek()
	{
		$thismonday = strtotime("Monday this Week",time());
		$thissunday = strtotime("Sunday this Week",time());
		$this->db->select("backlink_submit_record.submit_time, backlink_submit_record.case_id, backlink_submit_record.backlinkGroup_id,backlink_submit_record.linktype_thisweek, backlink_submit_record.export, case_table.case_name, case_table.case_industry")
						 ->from("backlink_submit_record")
						 ->join("case_table","case_table.auto_id = backlink_submit_record.case_id")
						 ->where("backlink_submit_record.submit_time >",$thismonday)
						 ->where("backlink_submit_record.submit_time <",$thissunday);
		$data = $this->db->get()->result_array();
		foreach ($data as $key => $eachsubmit) {
			// 取得備註
			$this->db->flush_cache()
							 ->select("remark_content")
							 ->from("backlink_content_table")
							 ->where("case_id",$eachsubmit["case_id"])
							 ->where("group_id_incase",$eachsubmit["backlinkGroup_id"]);
			$data[$key]["remark"] = $this->db->get()->result_array()[0]["remark_content"];
			if ($eachsubmit["export"]) {
				$data[$key]["export"] = "是";
			} else{
				$data[$key]["export"] = "否";
			}
			$data[$key]["submit_time_date"] = date("Y-n-d",$eachsubmit["submit_time"]);
		}
		$this->finaldata["everySubmitRecord"] = $data;
		$this->twig->display("backlink_all",$this->finaldata);

	}


	public function testtime(){
		$monday = strtotime("Monday last Week",time());
		echo $monday."<br>";
		$lastmonday = date("Y-n-d H:i:s",strtotime("Monday last Week",time()));
		echo $lastmonday."<br>";
		$sunday = strtotime("Sunday last Week",time());
		echo $sunday."<br>";
		$lastSunday = date("Y-n-d H:i:s",strtotime("Monday this Week",time()));
		echo $lastSunday;

	}
}
