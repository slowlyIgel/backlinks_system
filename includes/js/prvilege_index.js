$("button[name=changeprivilege]").click(function(){
  var everyAccount = [];
  $(this).siblings("div[total_privilege]").each(function(){
    var account = parseInt($(this).find("span[accountID]").attr("accountID"));
    var new_total_privilege = 0;
    $(this).children("input:checked").each(function(){
      new_total_privilege += parseInt($(this).val());
    });
      var eachAccount = {"admin_id":account,"total_privilege":new_total_privilege};
      everyAccount.push(eachAccount);
  });
  console.log(everyAccount);
  $.post("/ajax/privilege_upload",{"privilege":everyAccount},function(data){
    location.href ="/privilege";
  });
});
