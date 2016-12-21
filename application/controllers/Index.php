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
			$this->finaldata["page_name"] = "客戶總覽";
			$this->load->model("case_model");
			$this->finaldata["everycase"] = $this->case_model->case_table_list();

			// 取得最近下外鏈的日期
			$this->load->model("case_model");
			foreach ($this->finaldata["everycase"] as $key => $value) {
				$data = $this->case_model->get_last_submit_time($value["auto_id"]);
				if (!empty($data)) {
					$this->finaldata["everycase"][$key]["submit_time"] = $data["submit_time"];
				}
			}
			$this->twig->display("case_index",$this->finaldata);

	}

	public function case_search(){
		$this->finaldata["page_name"] = "案件搜尋";
		$this->twig->display("case_search",$this->finaldata);
	}
	public function casedata_edit($id){
		$this->finaldata["page_name"] = "案件編輯";
		//案件資料
		$this->db->select("auto_id, case_name, case_address, case_gacode, case_industry, case_level, case_program, case_title, case_description, case_keyword")
						 ->from("case_table")
						 ->where("auto_id",$id);
		$this->finaldata["casedata"] = $this->db->get()->result_array();

		// 案件已下外鏈紀錄
		$this->db->select("backlink_submit_record.backlinkGroup_id, backlink_submit_record.submit_time, type_backlink.Type_name")
						 ->from("backlink_submit_record")
						 ->join("type_backlink","backlink_submit_record.linktype_thisweek = type_backlink.auto_typeID")
						 ->where("backlink_submit_record.case_id",$id)
						 ->where("backlink_submit_record.export",1);
		$this->finaldata["linkRecord"] = $this->db->get()->result_array();
		foreach ($this->finaldata["linkRecord"]  as $key => $eachRecord) {
			$this->finaldata["linkRecord"][$key]["submit_time"] = date("Y-n-d",$eachRecord["submit_time"]);
		}

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

		$this->finaldata["page_name"] = "連結管理";

		$thismonday = strtotime("Monday this Week",time());
		$thissunday = strtotime("Sunday this Week",time());

		// 先確認有沒有新版資料，如果沒有就用舊版的
		$this->db->from("backlink_content_table")
						 ->order_by("group_id_incase")
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
		if (count($AlreadySubmitGroup) > 0) {
			$this->finaldata["thisweekRecord"] = $AlreadySubmitGroup;
		}

		// 舊版外鏈群組資料
		$this->db->select("case_backlink, case_name")
						 ->from("case_table")
						 ->where("auto_id",$case_id);
		$data = $this->db->get();
		if ($data->num_rows() > 0 ) {
			$data = $data->result_array()[0];
		}

		if (empty($new_backlink) && !empty($data["case_backlink"])) {
			// 區分群組
			$this->finaldata["original_data"] = $data["case_backlink"];
			$eachgroup = explode("Seperate%%GROUP%%Here",$data["case_backlink"]);
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
			foreach ($new_backlink as $groupkey => $eachBacklinkGroup) {
				$this->finaldata["group"][ $eachBacklinkGroup["group_id_incase"] ]["remark"] = $eachBacklinkGroup["remark_content"];
				$eachLinkinGroup = explode("Seperate%%EachLink%%Here",$eachBacklinkGroup["backlink_content"]);
				foreach ($eachLinkinGroup as $eachUrlKeyinGroup => $seperate) {
					preg_match_all("/<a([^>]*)>([^<]*)<\/a>/",$seperate,$eachUrl);
					$this->finaldata["group"][ $eachBacklinkGroup["group_id_incase"] ]["urlpart"][$eachUrlKeyinGroup]["keywords"] = $eachUrl[2][0];
					preg_match("/href=\"([^\"]*)\"/",$eachUrl[1][0],$urls);
					$this->finaldata["group"][ $eachBacklinkGroup["group_id_incase"] ]["urlpart"][$eachUrlKeyinGroup]["url"] = $urls[1];
				}
			}
			$this->finaldata["dataversion"] = "new";

		}
		$this->finaldata["case_id"] = $case_id;
		$this->finaldata["case_name"] = $data["case_name"];
		$this->finaldata["groupChinese"] = $this->groupname;
		$this->twig->display("case_linkgroupedit",$this->finaldata);
	}

	public function add_casedata(){
		$this->finaldata["page_name"] = "新增案件";

		$this->twig->display("case_dataadd",$this->finaldata);
	}

	public function case_search_bygroup(){
		$this->finaldata["page_name"] = "分類搜尋";

		$this->twig->display("case_search_bygroup",$this->finaldata);
	}

	public function backlink_record_comeback(){
		$this->finaldata["page_name"] = "歷史紀錄";
		$this->twig->display("backlink_record_comeback",$this->finaldata);
	}
	public function logout(){
		$this->session->sess_destroy();
		header("location: /");

	}
}
