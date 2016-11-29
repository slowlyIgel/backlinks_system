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
  var account = thisis.parents("div[accountID]").attr("accountID");
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
  var thisadmin = $(this).parents("div[accountID]").attr("accountID");
  if(thisadmin.length == 0){alert("帳號錯誤"); return false;}
  if (newpw != newpwconfirm | newpw.length == 0) { alert("密碼不一致！"); return false; }
  $.post("/ajax/manage_changepassword",{"newone" : newpw, "thisadmin" :thisadmin},function(data){
    alert(data);
    location.href = "/privilege/edit";
  });
});


$("button[name=DeleteThisAccount]").click(function(){
  if(confirm("確定要刪除此帳號??")){
    if (confirm("真的真的要刪除??")) {
      var thisAccount = $(this).parents("div[accountID]").attr("accountID");
      $.post("/ajax/manage_deleteaccount",{"deleteAccount" : thisAccount},function(){
        alert("已刪除");
        location.href = "/privilege/edit";
      });
    }
  }
});


$("button[name=submitNewAccount]").click(function(){
  var newAccountName = $(this).siblings("input[name=newaccountname]").val();
  var newAccountpw = $(this).siblings("input[name=newaccountpw]").val();
  var newaccountpwConfirm = $(this).siblings("input[name=newaccountpwconfirm]").val();
  if (newAccountpw != newaccountpwConfirm) { alert("密碼不一致"); return false; }
  if(newAccountName.length == 0 | newAccountpw.length == 0 | newaccountpwConfirm.length == 0){alert("請輸入資料"); return false;}
  var newprivilege = 0;
  $(this).siblings("input[name=pagePrvilege]:checked").each(function(){
    newprivilege += parseInt($(this).val());
  });
  $.post("/ajax/manage_addAccount",{"accountName":newAccountName, "accountPW":newAccountpw, "accountPrivilege":newprivilege},function(data){
    alert(data);
    location.href = "/privilege/edit";
  });
});
