function goto_caseLinkedit(field){
  var case_id = $(field).parents(".caseindex_btnarea").attr("case_id");
  location.href = "/index/case_linkgroupedit/"+case_id;
}

function delete_case(field){
  var case_name = $(field).parents(".caseindex_btnarea").find("a").html();
  if (confirm("確定要刪除"+case_name+"嗎??刪除了就沒救了喔")) {
    if (confirm("確定確定要刪除??")) {
      var case_id = $(field).parents(".caseindex_btnarea").attr("case_id");
      $.post("/ajax/delete_case",{case_id:case_id},function(result){
        var json = $.parseJSON(result);
        alert(json.alert);
        if (json.status == "success") { location.href = "/"; }
      });
    }
  }
}
