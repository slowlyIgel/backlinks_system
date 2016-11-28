function change_toWrite(){
  $("div.write_area").show();
  $("div.import_area").hide();
}
function change_toImport(){
  $("div.write_area").hide();
  $("div.import_area").show();
}
function add_anthorcase(field){
  var add_area = $(".each_newcase:first-child").html();
  var newarea = $("<div class=\"each_newcase\">");
  newarea.html(add_area);
  newarea.prepend("<button type=\"button\" onclick=\"removecase(this)\" style=\"float:right;\">刪除此案件</button>");
  $(field).before(newarea);
}

function removecase(field){
  $(field).parents(".each_newcase").remove();
}

function submitnewcase(){
  var totalNewCase = [];
  var falseFlag = false;
  $(".each_newcase").each(function(){

    if ($("input[mark]").is(":checked")) {
      var checkchild = "input[type=text]";
    } else{ var checkchild = "input[type=text], select";}

    $(this).children(checkchild).each(function(){
      if ($(this).val().length == 0 ) {
        alert("請填入所有資訊");
        $(this).focus();
        falseFlag = true;
        return false;
      }
    });
    if (falseFlag) {
      return false;
    }
    var NewCase = {
      "case_name" : $(this).children("input[name=case_name]").val(),
      "case_address" : $(this).children("input[name=address]").val(),
      "case_gacode" : $(this).children("input[name=gacode]").val(),
      "case_industry" : $(this).children("select[name=industy]").val(),
      "case_level" : $(this).children("select[name=level]").val(),
      "case_program" : $(this).children("select[name=program]").val()
    };
    totalNewCase.push(NewCase);
  });
  if (falseFlag) {
    return false;
  }
  $.post("/ajax/add_newcase",{newcasedata:totalNewCase},function(data){
    alert(data);
    location.href = "/index/add_casedata";
  });

}

function test(){
  $('#fileUploadForm').ajaxForm({
          beforeSubmit: ShowRequest,
          success: SubmitSuccesful,
          error: AjaxError
        });
}
function ShowRequest(formData, jqForm, options) {
        var queryString = $.param(formData);
        // alert('BeforeSend method: \n\nAbout to submit: \n\n' + queryString);
        // 先如此這般檢查副檔名...
        var extention = $("input[name=casedata]").val().split(".");
        if(extention[extention.length - 1] != "csv"){
          alert("請上傳csv檔!!");
          return false;
        } else { return true; }
      }

      function AjaxError() {
        alert("An AJAX error occured.");
      }

      function SubmitSuccesful(responseText, statusText) {
        alert("SuccesMethod:\n\n" + responseText);
        location.href = "/";
      }
