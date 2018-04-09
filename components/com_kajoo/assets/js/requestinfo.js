//This is not production quality, its just demo code.
var cookieList = function(cookieName) {
//When the cookie is saved the items will be a comma seperated string
//So we will split the cookie by comma to get the original array
var cookie = $.cookie(cookieName);
//Load the items or a new array if null.
var items = cookie ? cookie.split(/,/) : new Array();

//Return a object that we can use to access the array.
//while hiding direct access to the declared items array

return {
    "add": function(val) {
        //Add to the items.
        items.push(val);
        //Save the items to a cookie.
        //EDIT: Modified from linked answer by Nick see 
        $.cookie(cookieName, items.join(','));
    },
    "remove": function (val) { 
        //EDIT: Thx to Assef and luke for remove.
        indx = items.indexOf(val); 
        if(indx!=-1) items.splice(indx, 1); 
        $.cookie(cookieName, items.join(','));        },
    "clear": function() {
        items = null;
        //clear the cookie.
        $.cookie(cookieName, null);
    },
    "items": function() {
        //Get all the items.
        return items;
    }
  }
}  


$(document).ready(function() {
	

		    var list = new cookieList("wishlist");
		    var items = list.items();
			$.each(items, function(e) {	
			var entryId = $(this+' span.titwishlikid').html();
				$("ul#wishlist2").append("<li class='kajoowit wishitem"+entryId+"' id='"+entryId+"'>"+this+"</li>");
		
			});

$('#reqinfo').validate({
    rules: {
      name: {
        minlength: 2,
        required: true
      },
      company: {
        minlength: 2,
        required: true
      },
      email: {
        required: true,
        email: true
      }
    },
    highlight: function(label) {
    	$(label).closest('.control-group').addClass('error');
    },
    success: function(label) {
    	label
    		.text('OK!').addClass('valid')
    		.closest('.control-group').addClass('success');
    },
    submitHandler: function(form) {
		 
		  	var $inputs = $('#reqinfo :input');
		
		    // not sure if you wanted this, but I thought I'd add it.
		    // get an associative array of just the values.
		    var values = {};
		    $inputs.each(function() {
		        values[this.name] = $(this).val();
		   
		    });
		    var list = new cookieList("wishlist");
		    var items = list.items();
		    values['wishlist'] = items;

		    	var url = "index.php?option=com_kajoo&controller=contents&task=contents.sendRequest&format=raw";
		    	$.ajax({
			        type: "POST",
			        url: url,
			        data: values,
			        cache: false,
			        beforeSend: function(){
			        },                          
			        success: function(data){
			        	alert(data);
			      }                         
		        
		     });
		  return false;
   
    }
  });
  

	
	var list = new cookieList("wishlist");
	var items = list.items();
	$('span#numslist').html(items.length);
	
	$.each(items, function(e) {	
	var entryId = $(this+' span.titwishlikid').html();
		$("ul#wishlist").append("<li class='kajoowit wishitem"+entryId+"' id='"+entryId+"'><a href='#' class='ui-icon ui-icon-circle-close delwlit'></a>"+this+"</li>");

	});

});
