function startedit(){
  $("input").prop("disabled",false);
  $("select").prop("disabled",false);
}

function submit_casedata(){
  var case_id = $(".case_data").attr("case_id");
  var caseData = {
    "case_id" : case_id,
    "data" : {
      "case_name" : $("input[name=name]").val(),
      "case_address" : $("input[name=address]").val(),
      "case_gacode" : $("input[name=gacode]").val(),
      "case_industry" : $("select[name=industry]").val(),
      "case_level" : $("select[name=level]").val(),
      "case_program" : $("select[name=program]").val(),
      "case_title" : $("input[name=title]").val(),
      "case_description" : $("input[name=description]").val(),
      "case_keyword" : $("input[name=keyword]").val()
    }
  };
  $.post("/ajax/submit_casedata",{casedata:caseData},function(data){
    alert(data);
    location.href = "/index/casedata_edit/"+case_id;
  });
}

function goto_history(){
  var case_id = $(".case_data").attr("case_id");
  location.href = "/index/backlink_record_comeback/"+case_id;
}
