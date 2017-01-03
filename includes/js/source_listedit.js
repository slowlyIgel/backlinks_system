function delete_source(){
  $("button[name=delete]").click(function(){
    var dataID = $(this).parents("tr[source_id]").attr("source_id");
    if (confirm("確定刪除"+$(this).parents("tr[source_id]").find("a").html()+"嗎??")) {
      if (confirm("真的確定刪除???")) {
        $.post("/ajax_source/delete_source",{"id" : dataID},function(data){
          if (data.length == 0) {
            location.reload();
          }
        });
      }
    }
  });
}




function addto_exportlist(){
  $("button[name=export]").click(function(){
    var exportIDs = [];
    $("input[name=wanttoexport]:checked").each(function(){
      exportIDs.push($(this).parents("tr[source_id]").attr("source_id"));
    });
    $.post("/ajax_source/export_list_thisweek",{"export_list":exportIDs},function(data){
      if (data.length == 0) {
        location.href = "/source/source_export";
      }
    });
  });
}
