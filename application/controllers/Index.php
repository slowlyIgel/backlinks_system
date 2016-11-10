<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

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
    }

	public function index()
	{
		print_r($this->session->admin);

			$this->db->select("auto_id,case_name, case_industry, case_program, case_level")
							 ->from("case_table")
							 ->order_by("auto_id","DESC");
			$this->finaldata["everycase"] = $this->db->get()->result_array();

			// 共用的產業分類  應該提出待改
			$this->db->select("auto_industryID, industry_name")
							 ->from("type_industry");
			$industry_tpye = $this->db->get()->result_array();

			// 共用的等級分類  應該提出待改
			$this->db->from("type_level");
			$level_tpye = $this->db->get()->result_array();

			// 共用的方案分類  應該提出待改
			$this->db->from("type_program");
			$program_type = $this->db->get()->result_array();


			// 取得最近下外鏈的日期
			foreach ($this->finaldata["everycase"] as $key => $value) {
				$this->db->select("case_id, submit_time")
								 ->from("backlink_submit_record")
								 ->order_by("submit_time","DESC")
								 ->limit(1)
								 ->where("case_id",$value["auto_id"]);
				 $data[] = $this->db->get()->result_array();
			}
			foreach ($data as $key => $value) {
				if (!empty($value)) {
					$this->finaldata["everycase"][$key]["submit_time"] = date("Y-n-d",$value[0]["submit_time"]);
				}
			}

			foreach ($this->finaldata["everycase"] as $key => $value) {
				$levelkey = array_search($value["case_level"],array_column($level_tpye,"auto_levelID"));
				$programkey = array_search($value["case_program"],array_column($program_type,"auto_programID"));
				$industrykey = array_search($value["case_industry"],array_column($industry_tpye,"industry_name"));
				$this->finaldata["everycase"][$key]["case_program"] = $program_type[$programkey]["program_name"];
				$this->finaldata["everycase"][$key]["case_level"] = $level_tpye[$levelkey]["level_name"];
				$this->finaldata["everycase"][$key]["case_industry"] = $industry_tpye[$industrykey]["industry_name"];

			}
			$this->twig->display("case_index",$this->finaldata);

	}

	public function case_search(){
		$this->twig->display("case_search");
	}
	public function casedata_edit($id){
		//案件資料
		$this->db->select("auto_id, case_name, case_address, case_gacode, case_industry, case_level, case_program")
						 ->from("case_table")
						 ->where("auto_id",$id);
		$this->finaldata["casedata"] = $this->db->get()->result_array();

		// 案件已下外鏈紀錄
		$this->db->select("backlink_submit_record.backlinkGroup_id, backlink_submit_record.submit_time, type_backlink.BacklinkType_name")
						 ->from("backlink_submit_record")
						 ->join("type_backlink","backlink_submit_record.linktype_thisweek = type_backlink.auto_backlinkID")
						 ->where("backlink_submit_record.case_id",$id)
						 ->where("backlink_submit_record.export",1);
		$this->finaldata["linkRecord"] = $this->db->get()->result_array();
		foreach ($this->finaldata["linkRecord"]  as $key => $eachRecord) {
			$this->finaldata["linkRecord"][$key]["submit_time"] = date("Y-n-d",$eachRecord["submit_time"]);
		}

		// 共用的產業分類
		$this->db->select("auto_industryID, industry_name")
						 ->from("type_industry");
		$this->finaldata["industry_tpye"] = $this->db->get()->result_array();

		// 共用的等級分類
		$this->db->from("type_level");
		$this->finaldata["level_tpye"] = $this->db->get()->result_array();


		// 共用的方案分類  應該提出待改
		$this->db->from("type_program");
		$this->finaldata["program_type"] = $this->db->get()->result_array();

		$this->finaldata["case_id"] = $id;
		$this->twig->display("case_dataedit",$this->finaldata);
	}

	// public function case_linkgroupedit($case_id){
	// 	// 所有外鏈群組資料
	// 	$this->db->select("case_backlink, case_name")
	// 					 ->from("case_table")
	// 					 ->where("auto_id",$case_id);
	// 	$data = $this->db->get()->result_array();
	// 	// 可下外鏈種類資料
	// 	$this->db->from("type_backlink");
	// 	$backlink_type = $this->db->get()->result_array();
	// 	$this->finaldata["backlink_type"] = $backlink_type;
	// 	$this->finaldata["original_data"] = $data[0]["case_backlink"];
	//
	// 	// 區分群組
	// 	$eachgroup = explode("Seperate%%GROUP%%Here",$data[0]["case_backlink"]);
	// 	foreach ($eachgroup as $groupkey => $everyUrlinGroup) {
	// 		preg_match_all("/<a([^>]*)>([^<]*)<\/a>/",$everyUrlinGroup,$eachUrl[$groupkey]);
	// 		foreach ($eachUrl[$groupkey][2] as $eachUrlKeyinGroup => $eachUrlValueinGroup) {
	// 			$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["keywords"] = $eachUrlValueinGroup;
	// 			preg_match("/href=\"([^\"]*)\"/",$eachUrl[$groupkey][1][$eachUrlKeyinGroup],$urls);
	// 			preg_match("/title=\"([^\"]*)\"/",$eachUrl[$groupkey][1][$eachUrlKeyinGroup],$titles);
	// 			$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["url"] = $urls[1];
	// 			$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["title"] = $titles[1];
	// 		}
	// 		list($UrlPartinGroup[$groupkey],$RemarkPartinGroup[$groupkey]) = explode("Seperate%%REMARK%%Here",$everyUrlinGroup);
	// 		$this->finaldata["group"][$groupkey]["remark"] = $RemarkPartinGroup[$groupkey];
	// 	}
	// 	$this->finaldata["case_id"] = $case_id;
	// 	$this->finaldata["case_name"] = $data[0]["case_name"];
	// 	$this->finaldata["groupChinese"] = $this->groupname;
	// 	$this->twig->display("case_linkgroupedit",$this->finaldata);
	// }
	public function case_linkgroupedit($case_id){
		$thismonday = strtotime("Monday this Week",time());
		$thissunday = strtotime("Sunday this Week",time());

		// 先確認有沒有新版資料，如果沒有就用舊版的
		$this->db->from("backlink_content_table")
						 ->where("case_id",$case_id);
		$new_backlink = $this->db->get()->result_array();
		// 選取這週勾選了那些群組和類型的資料
		$this->db->select("backlinkGroup_id, linktype_thisweek")
						 ->from("backlink_submit_record")
						 ->order_by("backlinkGroup_id")
						 ->where("case_id",$case_id)
						 ->where("submit_time >",$thismonday)
						 ->where("submit_time <",$thissunday);
		$AlreadySubmitGroup = $this->db->get()->result_array();
		// 所有外鏈群組資料
		$this->db->select("case_backlink, case_name")
						 ->from("case_table")
						 ->where("auto_id",$case_id);
		$data = $this->db->get()->result_array();
		// 可下外鏈種類
		$this->db->from("type_backlink");
		$backlink_type = $this->db->get()->result_array();

		$this->finaldata["backlink_type"] = $backlink_type;
		$this->finaldata["original_data"] = $data[0]["case_backlink"];

		if (empty($new_backlink)) {
			// 區分群組
			$eachgroup = explode("Seperate%%GROUP%%Here",$data[0]["case_backlink"]);
			foreach ($eachgroup as $groupkey => $everyUrlinGroup) {
				preg_match_all("/<a([^>]*)>([^<]*)<\/a>/",$everyUrlinGroup,$eachUrl[$groupkey]);
				foreach ($eachUrl[$groupkey][2] as $eachUrlKeyinGroup => $eachUrlValueinGroup) {
					$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["keywords"] = $eachUrlValueinGroup;
					preg_match("/href=\"([^\"]*)\"/",$eachUrl[$groupkey][1][$eachUrlKeyinGroup],$urls);
					$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["url"] = $urls[1];
				}
			}
			$this->finaldata["dataversion"] = "old";

		} else {
			// print_r($new_backlink);
			echo "test";
			foreach ($new_backlink as $groupkey => $eachBacklinkGroup) {
				$this->finaldata["group"][$groupkey]["remark"] = $eachBacklinkGroup["remark_content"];
				$eachLinkinGroup = explode("Seperate%%EachLink%%Here",$eachBacklinkGroup["backlink_content"]);
				foreach ($eachLinkinGroup as $eachUrlKeyinGroup => $seperate) {
					preg_match_all("/<a([^>]*)>([^<]*)<\/a>/",$seperate,$eachUrl);
					$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["keywords"] = $eachUrl[2][0];
					preg_match("/href=\"([^\"]*)\"/",$eachUrl[1][0],$urls);
					$this->finaldata["group"][$groupkey]["urlpart"][$eachUrlKeyinGroup]["url"] = $urls[1];
				}
			}
			$this->finaldata["dataversion"] = "new";

			foreach ($AlreadySubmitGroup as $key => $record) {
				$typekey = array_search($record["linktype_thisweek"],array_column($backlink_type,"auto_backlinkID"));
				$AlreadySubmitGroup[$key]["linktype_thisweek_name"] = $backlink_type[$typekey]["BacklinkType_name"];
			}
			$this->finaldata["thisweekRecord"] = $AlreadySubmitGroup;

		}
		$this->finaldata["case_id"] = $case_id;
		$this->finaldata["case_name"] = $data[0]["case_name"];
		$this->finaldata["groupChinese"] = $this->groupname;
		$this->twig->display("case_linkgroupedit",$this->finaldata);
	}

	public function add_casedata(){
		// 共用的產業分類
		$this->db->select("auto_industryID, industry_name")
						 ->from("type_industry");
		$this->finaldata["industry_tpye"] = $this->db->get()->result_array();

		// 共用的等級分類
		$this->db->from("type_level");
		$this->finaldata["level_tpye"] = $this->db->get()->result_array();

		// 共用的方案分類  應該提出待改
		$this->db->from("type_program");
		$this->finaldata["program_type"] = $this->db->get()->result_array();

		$this->twig->display("case_dataadd",$this->finaldata);
	}
	public function logout(){
		$this->session->sess_destroy();
		header("location: /");

	}
}
