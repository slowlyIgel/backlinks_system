<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source_model extends CI_Model {

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

    public function get_sourcetypeInfo($dbname){
      $this->db->select()
               ->from($dbname)
               ->order_by("auto_typeID");
      $typedata = $this->db->get()->result_array();
      foreach ($typedata as $key => $value) {
        $alterarray = array();
        foreach ($value as $key2 => $value2) {
          if($key2 == "auto_typeID"){continue;}
          $alterarray[$key2] = $value2;
        }
        $data[ $value["auto_typeID"] ] = $alterarray;
        unset($alterarray);
      }
      return $data;
    }

    public function get_source_table_likeindex($source_address = NULL, $source_filter = NULL){

      // 取得所有資源站資料
      $this->db->flush_cache()
               ->start_cache()
               ->select()
               ->from("source_table")
               ->order_by("source_id")
               ->stop_cache();

      if (!empty($source_address)) {
        $this->db->like("source_address",$source_address)
                 ->stop_cache();
        $search_flag = true;
      }
      if (!empty($source_filter) && is_array($source_filter)) {
        $this->db->where($source_filter)
                 ->stop_cache();
        $search_flag = true;
      }
      $all_source = $this->db->get()->result_array();

      // 把最終資料的key值整理成source_id方便之後比對
      foreach ($all_source as $key => $value) {
        $final_allsource[ $value["source_id"] ] = $value;

        if (isset($search_flag)) { //給搜尋頁用的
          $IDlist_this_week[] = $value["source_id"];
        }
      }

      //-------------根據條件取得source_table資訊end-------------------


      if (isset($search_flag) && empty($IDlist_this_week)) { //搜尋而且沒有搜尋到
        return $final_allsource = NULL;
      }
      //  取得本週已勾選的資源站列表
      $this->db->flush_cache()
               ->start_cache()
               ->select("source_id")
               ->from("source_submit_record")
               ->where("submit_time >",$this->thismonday)
               ->where("submit_time <",$this->thissunday)
               ->stop_cache();
      if (!empty($IDlist_this_week)) { //只檢查搜尋出來的ID
          $this->db->where_in("source_id",$IDlist_this_week);
          }

      // 勾選者在所有資源站資料裡標註起來
      $submitdata_thisweek = $this->db->get();
      if ($submitdata_thisweek->num_rows() > 0) {
        foreach ($submitdata_thisweek->result_array() as $value) {
          $final_allsource[ $value["source_id"] ]["already_submit_thisweek"] = true;
        }
      }
      return $final_allsource;
    }


}
