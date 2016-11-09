<?
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
		var $test;
		var $groupname;
		var $finaldata;
        public function __construct($config = array())
        {
                parent::__construct($config);
									$this->groupname = array("群組一","群組二","群組三","群組四","群組五",
																					"群組六","群組七","群組八","群組九","群組十",
																					"群組十一","群組十二","群組十三","群組十四","群組十五",
																					"群組十六","群組十七","群組十八","群組十九","群組二十",
																					"群組二一","群組二二","群組二三","群組二四","群組二五",
																					"群組二六","群組二七","群組二八","群組二九","群組三十");
									$this->finaldata["groupChinese"] = $this->groupname;
        }


}
?>
