// LOAD TABS FOR ADMIN PAGE
// LOAD TAB 1 (Students)
$(document).ready(function(){
  $("#showdiv1").hide();
$( "#button1" ).on( "click", function() {
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").show();
  });

  // LOAD TAB 2 (Facilitators)
  $("#showdiv2").hide();
  $("#button2").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv2").show();
});
  
  // LOAD TAB 3 (Allotted-Hours)
  $("#showdiv3").hide();
  $("#button3").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv3").show();
});
  
  // LOAD TAB 4 (PASSWORDS)
  $("#showdiv4").hide();
  $("#button4").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv4").show();
});
  
  // LOAD TAB 5 (HOLIDAYS)
  $("#showdiv5").hide();
  $("#button5").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv5").show();
});
  // LOAD TAB 6 (GLOBALS)
  $("#showdiv6").hide();
  $("#button6").click(function(){
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv6").show();
});
      // LOAD TAB 7 (EVENTS)
  $("#showdiv7").hide();
  $("#button7").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv7").show();
});
      });