$(document).ready(function(){
    $("p[alreadysubmitgroup_id]").each(function(){
      var selectedOne = $(".eachLinkGroup[group_id="+$(this).attr("alreadysubmitgroup_id")+"]").find("input[name=backlink_tpye][value="+$(this).attr("alreadysubmittype_id")+"]");
      selectedOne.prop("disabled",true);
      selectedOne.next("label").addClass("alreadyclickthisweek");
    });
});

  function showhelp(){
    $(".original").show();
  }
  function addUrl(field){
    var Url = $(field).parents("div.eachLinkGroup").find(".eachUrl:first-child").html();
    var Urldiv = $("<div class=\"eachUrl\">");
    Urldiv.append(Url);
    Urldiv.find("input").val("");
    $(field).parents(".eachLinkGroup").children(".GroupContent").append(Urldiv);
  }
  function removeUrl(field){
    $(field).parents(".eachUrl").remove();
  }
  function addGroup(){
    var nextgroup_num = parseInt($(".eachLinkGroup").last().attr("group_id")) + 1;
    if (nextgroup_num < 30) {
      var Groupdiv = $(".eachLinkGroup:first").html();
      var newGroup = $("<div class=\"eachLinkGroup\" group_id="+(nextgroup_num)+">");
      newGroup.html(Groupdiv);
      newGroup.find(".eachUrl:not(:first-child)").remove();
      newGroup.find(".groupname_chinese").remove();
      newGroup.find("input[type=checkbox],input[type=checkbox]~label").remove();
      newGroup.find("input").val("");
      newGroup.find("textarea").val("");
      newGroup.find("input:checked").prop("disabled",true);
      newGroup.children(".GroupContent").before("<button type=\"button\" onclick=\"removegroup(this)\">刪除此群組</button><br>");
      $(".everyGroup").append(newGroup);
      sort();
    } else {
      alert("已達群組數上限!!!想要增加群組去跟管理員說!!!");
    }
  }
  function linkgroup_submit(){
    var allGroupStorge = [];
    $(".eachLinkGroup").each(function(){
      var eachGroupContent = "";
      $(this).children(".GroupContent").children(".eachUrl").each(function(){
        eachGroupContent += "<a href=\""+$(this).children("input[name=url]").val()+"\" ";
        eachGroupContent += "title=\""+$(this).children("input[name=keywords]").val()+"\">";
        eachGroupContent += $(this).children("input[name=keywords]").val()+"</a>";
        if (!$(this).is($(".eachUrl:last-child"))) {
          eachGroupContent += "Seperate%%EachLink%%Here";
        }
      });
      var eachGroupStorge = {
        "case_id" : $("#case_id").attr("case_id"),
        "group_id_incase" : $(this).attr("group_id"),
        "backlink_content" : eachGroupContent,
        "remark_content" : $(this).find("textarea[name=remark]").val(),
      };
      allGroupStorge.push(eachGroupStorge);
    });
    var case_id = $("#case_id").attr("case_id");
    console.log(allGroupStorge);
      $.post("/ajax/upload_linkgroupcontent/"+case_id,{allGroupStorge},function(data){
        alert("good");
        location.href = "/index/case_linkgroupedit/"+case_id;
      });
  }
  function submit_original(){
    var original_data = $("textarea[name=original]").val();
    var case_id = $("#case_id").attr("case_id");
      $.post("/ajax/upload_newtypelink_oldver/"+case_id,{change:original_data},function(){
        alert("good~~");
        location.href = "/index/case_linkgroupedit/"+case_id;
      });
  }
  function removegroup(field){
    $(field).parents(".eachLinkGroup").remove();
  }
$("input[name=backlink_tpye]").click(function(){
  var type_name = $(this).next("label").html();
  var type_value = $(this).val();
  var group_id = $(this).parents(".eachLinkGroup").attr("group_id");
  var group_name = $(this).parents(".eachLinkGroup").find($("label.groupname_chinese")).html();
  if ($(this).is(":checked")) {
    $("#submit_preview").append("<tr submit_id="+group_id+"_"+type_value+"><td group_id="+group_id+">"+group_name+"</td><td type_id="+type_value+">"+type_name+"</td><tr>");
  } else{
    $("#submit_preview").find("tr[submit_id="+group_id+"_"+type_value+"]").remove();
  }
});

  function submit_thisweek(){
    if ($("input[name=backlink_tpye]:checked").length > 0 ) {
      var case_id = $("#case_id").attr("case_id");
      var thisweekRecord = "[";
      $("tr[submit_id]").each(function(){
        thisweekRecord += '{"case_id":'+case_id+',"backlinkGroup_id":'+$(this).children("td[group_id]").attr("group_id")+',"linktype_thisweek":'+$(this).children("td[type_id]").attr("type_id")+'}';
        if (!$(this).is($("tr[submit_id]").last())) {
          thisweekRecord += ",";
        }
      });
      thisweekRecord += "]";
      $.post("/ajax/submit_thisweek_record",{everyRecord:thisweekRecord},function(){
        location.href = "/backlink/thisweek";
      });
    } else {
      alert("你還沒選膩");
    }
  }
