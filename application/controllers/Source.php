<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source extends MY_Controller {

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

				function get_sourcetypeInfo($dbname){
					$ci = &get_instance();
					$ci->db->select()
									 ->from($dbname)
									 ->order_by("auto_typeID");
					$typedata = $ci->db->get()->result_array();
					foreach ($typedata as $key => $value) {
						$alterarray = array();
						foreach ($value as $key2 => $value2) {
							if($key2 == "auto_typeID"){continue;}
							$alterarray[$key2] = $value2;
						}
						$ci->finaldata[$dbname][ $value["auto_typeID"] ] = $alterarray;
						unset($alterarray);
					}
				}
				// 取得共用類別們
				get_sourcetypeInfo("type_source_status");
				get_sourcetypeInfo("type_source_anchor");
				get_sourcetypeInfo("type_source_indexstatus");
				get_sourcetypeInfo("type_source_kpnbuild");
				get_sourcetypeInfo("type_source_lang");
				get_sourcetypeInfo("type_source_level");
				get_sourcetypeInfo("type_source_sitetype");
				get_sourcetypeInfo("type_source_topic");



    }

    public function index(){
      $this->finaldata["page_name"] = "資源站管理";

			// 取得所有資源站資料
			$this->db->select()
							 ->from("source_table")
							 ->order_by("source_id");
			$this->finaldata["all_source"] = $this->db->get()->result_array();

      $this->twig->display("source_index",$this->finaldata);
    }

    public function source_dataedit($id){
      $this->finaldata["page_name"] = "資源站編輯";
      $this->twig->display("source_dataedit",$this->finaldata);
    }
		public function source_dataremark($id){
			$this->twig->display("source_dataremark",$this->finaldata);
		}
		public function source_export(){
			$this->finaldata["page_name"] = "資源站資料匯出";
			$this->twig->display("source_exporttable",$this->finaldata);
		}

		public function source_analysis(){
			$this->finaldata["page_name"] = "資源站分類分析";

			function get_count_each_category($dbname=array()){
				$ci = &get_instance();
				foreach ($dbname as $key => $value) {
					$ci->db->select("COUNT(*),".$value["source"])
								 ->from("source_table")
								 ->group_by($value["source"])
								 ->order_by($value["source"]);

					$groupdata = $ci->db->get()->result_array();
					foreach ($groupdata as $key2 => $resultdata) {
						$ci->finaldata["category_count"][$value["table"]][ $resultdata[ $value["source"] ] ] = $resultdata["COUNT(*)"];
					}
				}
			}


			$dbarray[] = array("source"=>"source_topic","table"=>"type_source_topic");
			$dbarray[] = array("source"=>"source_status","table"=>"type_source_status");
			$dbarray[] = array("source"=>"source_indexstatus","table"=>"type_source_indexstatus");
			$dbarray[] = array("source"=>"source_kpnbuild","table"=>"type_source_kpnbuild");
			$dbarray[] = array("source"=>"source_lang","table"=>"type_source_lang");
			$dbarray[] = array("source"=>"source_anchor","table"=>"type_source_anchor");
			$dbarray[] = array("source"=>"source_sitetype","table"=>"type_source_sitetype");


			get_count_each_category($dbarray);
			unset($dbarray);

			$this->finaldata["all_category"] = array(
				"type_source_topic" => $this->finaldata["type_source_topic"],
				"type_source_status" => $this->finaldata["type_source_status"],
				"type_source_indexstatus" => $this->finaldata["type_source_indexstatus"],
				"type_source_kpnbuild" => $this->finaldata["type_source_kpnbuild"],
				"type_source_lang" => $this->finaldata["type_source_lang"],
				"type_source_anchor" => $this->finaldata["type_source_anchor"],
				"type_source_sitetype" => $this->finaldata["type_source_sitetype"]

			);

			$this->twig->display("source_analysis",$this->finaldata);
		}

}
