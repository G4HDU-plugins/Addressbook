
$("#myAddrModal").on("hide.bs.modal", function(e) {
//$("#addressbookPage").show();
      // $("#bodyModal").hide();
      // $('#addrLoader').show(); 
      // $("#bodyModal").text("");  
   
});
$("#myAddrModalx").on("show.bs.modal", function(e) {
  //  $("#ajaxSpinner").show();
  //  $("#addressbookPage").hide();
     //  $("#bodyModal").text("");  
      // $("#bodyModal").hide();
      // $('#addrLoader').show(); 
   //    <img src="images/ajax-loader.gif" id="aload"/>
   
});
$( document ).ready(function() {
    var myStylesLocation = "/e107_plugins/addressbook/css/addressbook.css";
    $(".addressBookRow").on('click', function(event){
        event.stopPropagation();
        event.stopImmediatePropagation();
        var editURL=$("#modallink").attr('href');
        var editID = $(this).attr('class').replace('addressBookRow editID', ''); // get the id of the reord
        $.get(myStylesLocation, function(cssTo){
            $('<style type="text/css"></style>')
                .html(cssTo)
                .appendTo("head");
        }); // end get myStylesLocation
        $('#myAddrModal').modal('show');
        $("#modalContent").hide(0);
        $("#ajaxSpinner").show(0);
                 
        $("#modalContent").load(editURL+editID,function(){
            $("#ajaxSpinner").fadeOut(1000,function(){
                $("#modalContent").fadeIn(1500);
                });  // end ajax spinner    
       });  // end modalContent load
    });  // end addressbookrow click
});  // end document ready
