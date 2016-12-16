$(document).ready(function(){
  count_totalselect_checkbox();
});

$("input[name=selectexport]").click(function(){
  count_totalselect_checkbox();
});

$("input[name=allselect]").click(function(){
  if(this.checked){ $("input[type=checkbox]").prop("checked",true) ;}
  else { $("input[type=checkbox]").prop("checked",false) ; }
  count_totalselect_checkbox();
});

function showDatabyType(field){
  var type = $(field).html();
  $("tr[case_id][group_id]").hide();
  $("td[name=LinkType]:contains("+type+")").parents("tr[case_id][group_id]").show();
  count_totalselect_checkbox();
}

function showAllTypes(){
  $("tr[case_id][group_id]").show();
  count_totalselect_checkbox();
}

function create_export_file(){
  var datacount = $("tr[case_id][group_id]:visible").has("input:checked").length;
  var seperateint = $("#SeperateToNFile").val();
  if ($.isNumeric(seperateint) && datacount >= seperateint ) {
    var ContentIndex = "<form  action=\"\/export\/backlink_multi\" method=\"post\">";
    ContentIndex += "<input type=\"hidden\" name=\"SeperateTo\" value="+seperateint+">";
  } else if ($.isNumeric(seperateint) && datacount < seperateint) {
    alert("拆檔數大於總比數，請重新輸入");
    return false;
  }  else if (seperateint != "" && !$.isNumeric(seperateint)) {
    alert("請輸入數字");
  }else {
    var ContentIndex = "<form  action=\"\/export\/backlink_one\" method=\"post\">";
  }
  var i = 0;
  $("tr[case_id][group_id]:visible").has("input:checked").each(function(){
    ContentIndex += "<input type=\"hidden\" name=\"ContentIndex["+ i +"][case_id]\" value="+$(this).attr("case_id")+">";
    ContentIndex += "<input type=\"hidden\" name=\"ContentIndex["+ i +"][group_id_incase]\" value="+$(this).attr("group_id")+">";
    ContentIndex += "<input type=\"hidden\" name=\"ContentIndex["+ i +"][submit_time]\" value="+$(this).children("td[time]").attr("time")+">";
    ContentIndex += "<input type=\"hidden\" name=\"ContentIndex["+ i +"][CaseName]\" value="+$(this).children("td[name=caseName]").children("a").html()+">";
    ContentIndex += "<input type=\"hidden\" name=\"ContentIndex["+ i +"][LinkType]\" value="+$(this).children("td[name=LinkType]").attr("type_id")+">";
    i += 1;
  });

  ContentIndex += "<\/form>";
  $(ContentIndex).submit();
}

function count_totalselect_checkbox(){
  var checked = $("input[name=selectexport]:visible:checked").length;
  $("span[name=countselect_checkbox]").html(checked);
}
