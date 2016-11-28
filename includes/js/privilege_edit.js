$("button[goto=checkOldPassword]").click(function(){
  $(this).siblings("div.checkOldPassword").show();
  $(this).hide();
});

$("button[goto=AddNewAccount]").click(function(){
  $(this).siblings("div.AddNewAccount").show();
});

$("button[goto=checkNewPassword]").click(function(){
  var thisis = $(this);
  var oldPW = thisis.prev("input").val();
  var account = thisis.parents("div").find("span").attr("accountID");
  if (oldPW.length > 0) {
    // console.log(account);
    $.post("/ajax/manage_checkoldpw",{"password" : oldPW, "account" : account},function(data){
      if(data =="good"){
        thisis.siblings().hide();
        thisis.siblings("div.checkNewPassword").show();
        thisis.hide();
      } else{alert("密碼錯誤請重新輸入");}
    });
  } else{alert("請輸入密碼");}
});

$("button[name=submitNewPassword]").click(function(){
  var newpw = $(this).siblings("input[name=newpw]").val();
  var newpwconfirm = $(this).siblings("input[name=newpwconfirm]").val();
  if (newpw != newpwconfirm | newpw.length == 0) { alert("密碼不一致！"); return false; }
  $.post("/ajax/manage_changepassword",{"newone" : newpw},function(data){
    alert(data); //還沒寫加密修改進資料庫的部分
  });
});


$("button[name=DeleteThisAccount]").click(function(){
  if(confirm("確定要刪除此帳號??")){
    if (confirm("真的真的要刪除??")) {
      var thisAccount = $(this).prev("span").attr("accountID");
      $.post("/ajax/manage_deleteaccount",{"deleteAccount" : thisAccount},function(){
        alert("已刪除");
        location.href = "/privilege";
      });
    }
  }
});
