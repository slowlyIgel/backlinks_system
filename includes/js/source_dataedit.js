var data = {};

$("input,select").change(function(){
  var name = "source_"+$(this).attr("name");
  data[name] = $(this).val();
});





$("button[name=submit]").click(function(){
  if ($.isEmptyObject(data)) {
    alert("沒有修改任何東西");
    return false;
  }
  var editdata = {
    "source_id" : $("div.source_data_area").attr("source_id"),
    "changedata" : data
  };

  $.post("/ajax_source/source_dataedit/"+$("div.source_data_area").attr("source_id"),{"sourcedata":editdata},function(data){
    alert(data);
    location.reload();
  })
});
