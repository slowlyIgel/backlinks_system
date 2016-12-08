function search_case(){
  $("#resultdable").hide();
  $("#resultdable").children("tbody").empty();
  if ($("input[name=case]").val().length > 0) {
    var searchKey = $("input[name=case]").val();
    $.post("/ajax/case_search",{"searchKey":searchKey},function(data){
      var json = $.parseJSON(data);
      if (json.status == "success") {
        $.each($.parseJSON(json.print),function(){
            var resultTR = $("<tr class=\"caseindex_btnarea\" case_id=\""+this.auto_id+"\"></tr>");
            resultTR.append("<td><a href=\"/index/casedata_edit/"+this.auto_id+"\">"+this.case_name+"</td>");
            if (this.submit_time != undefined) {
              resultTR.append("<td>"+this.submit_time+"</td>");
            } else{
              resultTR.append("<td>---</td>");
             }
            resultTR.append("<td>"+this.case_level+"</td>");
            resultTR.append("<td>"+this.case_program+"</td>");
            resultTR.append("<td>"+this.case_industry+"</td>");
            resultTR.append("<td><button type=\"button\" onclick=\"goto_caseLinkedit(this)\">連結管理</button></td>");
            if($("#resultdable").children("thead").attr("privilege") == "have"){
            resultTR.append("<td><button type=\"button\" name=\"button\" onclick=\"delete_case(this)\">刪除</button><br></td>");
            }
            $("#resultdable").children("tbody").append(resultTR);
        });
        $("#resultdable").show();
      } else{alert(json.print);}
    });
  } else{
      alert("請輸入關鍵字"); //待改
  }
}
