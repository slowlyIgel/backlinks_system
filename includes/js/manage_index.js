$("button[name=delete_ingroup]").click(function(){
  var group = $(this).parents("div[manageGroup]").attr("manageGroup");
  var deleteID = $(this).prev("input[inTpyeID]").attr("inTpyeID");
  $.post("/ajax/manage_delete",{"manageFocus":group,"idFocus":deleteID},function(data){
    alert(data);
    location.href = "/manage/index";
  });
});

$("button[name=change_name]").click(function(){
  var group = $(this).parents("div[manageGroup]").attr("manageGroup");
  var renameID = $(this).prevAll("input[inTpyeID]").attr("inTpyeID");
  var rename = $(this).prevAll("input[inTpyeID]").val();
  // 檢查輸入名稱非空值
  $.post("/ajax/manage_rename",{"manageFocus":group,"idFocus":renameID,"nameFocus":rename},function(){
    alert("更改完畢");
    location.href = "/manage/index";
  });
});

$("button[goto]").click(function(){
var area = $(this).attr("goto");
$("div[manageGroup]").hide();
$("div[manageGroup="+area+"]").show();
});

$("button[name=addNewtype]").click(function(){
var addname = $(this).prev("input").val();
if(addname.length > 0){
  var group = $(this).parents("div[manageGroup]").attr("manageGroup");
  $.post("/ajax/manage_add",{"manageFocus":group,"nameFocus":addname},function(){
    alert("新增完畢");
    location.href = "/manage/index";
  });
} else{ alert("請輸入分類名稱"); }
});
