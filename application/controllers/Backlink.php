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

		$this->db->select("backlink_submit_record.case_id, backlink_submit_record.backlinkGroup_id, backlink_submit_record.linktype_thisweek, backlink_submit_record.export, case_table.case_name, case_table.case_backlink")
						 ->from("backlink_submit_record")
						 ->join("case_table","case_table.auto_id = backlink_submit_record.case_id")
						 ->where("backlink_submit_record.submit_time >",$lastmonday)
						 ->where("backlink_submit_record.submit_time <",$lastsunday);
		$data= $this->db->get()->result_array();
		foreach ($data as $key => $value) {
			$findRemark = explode("Seperate%%GROUP%%Here",$value["case_backlink"]);
			foreach ($findRemark as $key2 => $value2) {
				list($groupPart[],$reallyRemark[]) = explode("Seperate%%REMARK%%Here",$value2);
			}
			unset($data[$key]["case_backlink"]);
			$data[$key]["case_backlinkRemark"] = $reallyRemark[ $data[$key]["backlinkGroup_id"] ];
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
