<?
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
		var $test;
		var $groupname;
		var $finaldata;
        public function __construct($config = array())
        {
                parent::__construct($config);

								$this->groupname = array("一","二","三","四","五",
																				"六","七","八","九","十",
																				"十一","十二","十三","十四","十五",
																				"十六","十七","十八","十九","二十",
																				"二一","二二","二三","二四","二五",
																				"二六","二七","二八","二九","三十");
								$this->finaldata["groupChinese"] = $this->groupname;
        }


}
?>
