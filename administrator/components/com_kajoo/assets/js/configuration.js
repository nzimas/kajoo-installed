$(function() {
    $( ".column" ).sortable({
        connectWith: ".column",
       // items:".cansort",
        update:function( event, ui )
        {
	     	saveOrder(); 
        }
    });
    $( ".disablecolumn" ).draggable({
        disabled: true
    });
    
    

    $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
        .find( ".portlet-header" )
            .addClass( "ui-widget-header ui-corner-all" )
            .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
            .end()
        .find( ".portlet-content" );

    $( ".portlet-header .ui-icon" ).click(function() {
        $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
        $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
    });

    $( ".column" ).disableSelection();
});

function saveOrder() {
	var colArray = {}
    $(".column").each(function(index, value){
        var colid = value.id;

        // Get the order for this column.
        var order = $('#' + colid).sortable("toArray");
        
        if(order.length==0)
        {
	        order = 'empty';
        }
        
        colArray[index] = order;
        
    });


	$.ajax({
        type: "POST",
        url: "index.php?option=com_kajoo&controller=configuration&task=configuration.setOrderPositions&format=raw&tmpl=component",
        data: { data: colArray },
        cache: false,
        beforeSend: function(){
           $('div#ajaxLoader div#spin').fadeIn();
        },                          
        success: function(data){   
            $('div#ajaxLoader div#spin').fadeOut();
      }                         
        
     });
       
}



$(function() {

	$("a.deleteCat").live("click", function(e){
		var catId = $(this).attr("id");
    

	   var retVal = confirm("Do you want delete this category");
	   if( retVal == true ){
	     	
		  
		  return true;
	   }else{


		  return false;
	   }
	    e.preventDefault();   

	});

});


$(function() {

	$("a#newcat").live("click", function(e){
		var catId = $(this).attr("id");
		var partnerid = $('input#partnerid').val();
		var parent = $('select#catParent option:selected').val(); 
		var name = $('input#catname').val(); 
		if(name=='')
		{
			alert("Write a name");
			return false;
		}
		else{
			var url = "option=com_kajoo&controller=content&task=configuration.addCategory&format=raw&tmpl=component&parent="+parent+"&partnerid="+partnerid+"&name="+name;
			$.ajax({
	            type: "POST",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();
		            location.reload();

	          }                         
	            
	         });
			return true;
		}
	    e.preventDefault();   

	});

});