function search_case(){
  $("#result").empty();
  if ($("input[name=case]").val().length > 0) {
    var searchKey = $("input[name=case]").val();
    $.post("/ajax/case_search",{"searchKey":searchKey},function(data){
      var json = $.parseJSON(data);
      if (json.status == "success") {
        $.each($.parseJSON(json.print),function(){
          var link = $("<a href=\"\"></a>");
          link.attr("href","/index/casedata_edit/"+this.auto_id);
          link.html(this.case_name);

          var groupedit = $("<a href=\"\"></a>");
          groupedit.attr("href","/index/case_linkgroupedit/"+this.auto_id);
          groupedit.html("修改外鏈群組");
          $("#result").append(link);
          $("#result").append(groupedit);

        });
      } else{alert(json.print);}
    });
  } else{
      alert("請輸入關鍵字"); //待改
  }
}
