function loadData()
{

	  var liHasClass = false;
	  $('#navigationCategories li').each(function (e) {
	    if($(this).attr('class') == 'active') {
	      liHasClass = true;
	    }
	    
	  });
	var numCatsSel = $('#navigationCategories li.active').length;
	if(numCatsSel==0)
	{
		firstLoadCat();
	}
	if(numCatsSel>1)
	{
		$('#navigationCategories li#all').removeClass('active');
	}
	
	var searchText = $('input#tableSearchButton').val();
	var partnerValue = $('select#partner').val();
	
	// Get the categories
	var categoriesArray = ['all'];
	$('#navigationCategories  li.active').each(function (e) {
  		var id =  $(this).attr("id");
  		categoriesArray[e] = id;
	});


	//Get the fields
	var fieldsArray = ['none'];
	$('#fieldsKajoo > select').each(function (e) {
  		var fieldId =  $(this).attr("id");
  		var valueField = $(this).val();
  		var valuesArray = [];
  		valuesArray[0] = fieldId;
  		valuesArray[1] = valueField;
  		fieldsArray[e] = valuesArray;

	});
	
	//Get pagination

	var limit = $('select#limitKajoo').val();
	
	var limitstart = (selectedPage*limit)-limit;

	var url = "index.php?option=com_kajoo&view=table&format=raw";
	var data = { 'searchText' : searchText, 
				 'partnerValue' : partnerValue, 
				 'categories' : categoriesArray,
				 'limit' : limit,
				 'selectedPage': selectedPage,
				 'limitstart':limitstart,
				 'filters' : fieldsArray
				 }
console.log(data);
	$.ajax({
        type: "POST",
        url: url,
        data: data,
        cache: false,
        beforeSend: function(){
            $(".kajooInput").prop('disabled', true);
           
            $('div#tableData').html('<div id="loadingKajoo"></div>');
        },                          
        success: function(data){
        	$(".kajooInput").prop('disabled', false);

        	$('div#tableData').html('');
            $(data).hide().appendTo('div#tableData').fadeIn('fast');
            totalPages = $('input#totalPages').val();
	
            $('.kajooAddWishlist').live("click", function(e){
				var entryId = $(this).parent().attr("id");
				var list = new cookieList("wishlist"); 

				var n = $("ul#wishlist li.wishitem"+entryId).length;
				var numlist = $("ul#wishlist li").length;

				if(n==0)
				{
					var partnerName = $("#partner :selected").text();
					var title = $('.kajooAddWishlist#kajooID'+entryId).attr('title');
					title = title+' <span class="titwishlikid">'+partnerName+'</span>';
					
					
					list.add(title); 								
					var items = list.items();
					$('span#numslist').html(items.length);
					var dataAppend = "<li class='kajoowit wishitem"+entryId+"' id='"+entryId+"'><a href='#' class='ui-icon ui-icon-circle-close delwlit'></a>"+title+"</li>";
					$(dataAppend).hide().appendTo("ul#wishlist").fadeIn('fast');
					

				}
	
				toggleSubmitMoreInfo();
				e.preventDefault();
				
			});
				  var scroll_on_reload = $('input#scroll_on_reload').val();
				  if(scroll_on_reload==1)
				  {
					$.scrollTo('#topColsKajoo',400);	  
				  }

      }                         
        
     });
}

function loadCats()
{
	var partnerValue = $('select#partner').val();
	var url = "index.php?option=com_kajoo&view=categories&format=raw&partnerid="+partnerValue;
	var data = { 'catId' : 11 }

	$.ajax({
        type: "POST",
        url: url,
        data: data,
        cache: false,
        beforeSend: function(){
            $(".kajooInput").prop('disabled', true);
           

        },                          
        success: function(data){
        	$(".kajooInput").prop('disabled', false);

        	$('div#catContainer').html('');
            $(data).hide().appendTo('div#catContainer').fadeIn('fast');
            
            
		  	$("#navigationCategories").attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
		
			$("#navigationCategories").treeview({
				collapsed: true,
				persist: "cookie",
				animated: "fast",
				unique: false
			});
			
			$('#navigationCategories li').each(function (e) {
		  		var id =  $(this).attr("id");
		  		//$(this).addClass ($.cookie('kajoo_cat_'+id));
			});
			firstLoadCat();

			$(function () {
				$('a[rel=panel]').click
				(
				    function (e) 
				    {
					    unselectAllCats();
				        var hasclass = $(this).parent().hasClass('active');
				        var id =  $(this).parent().attr("id");
				        
			
				        if(hasclass)
				        {
					       $(this).parent().removeClass ('active');
					       //$.cookie("kajoo_cat_"+id, '');
				        }
				        else
				        {
			   		        if(id=='all')
					        {
						        unselectAllCats();
					        }
				        
					       $(this).parent().addClass ('active');
					       //$.cookie("kajoo_cat_"+id, 'active');
				        }
				        loadData();
			
				        e.preventDefault(); 
				    }
				    
				);
			});
			
			
			$(function () {
				$("a#unselectAllCats").click(function(e) {
					unselectAllCats();
					loadData();
					e.preventDefault();
				});
			
			});
			
			$(function () {
				$("a#selectAllCats").click(function(e) {
					selectAllCats();
					loadData();
					e.preventDefault();
				});
			
			});


			
			
        }                         
        
     });

}
function firstLoadCat()
{
	$('#navigationCategories li#all').addClass('active');
}
function unselectAllCats()
{
	$('#navigationCategories  li').each(function (e) {
		   $(this).removeClass ('active');
		   var id =  $(this).attr("id");
		  // $.cookie("kajoo_cat_"+id, '');
		    
	});
}

function selectAllCats()
{
		$('#navigationCategories li').each(function (e) {
			   $(this).addClass ('active');
			   var id =  $(this).attr("id");
			 //  $.cookie("kajoo_cat_"+id, 'active');
			    
		});
}

function toggleSubmitMoreInfo()
{
	var numlist = $("ul#wishlist li").length;
	if(numlist==0)
	{
		$('a#reqinfobtnkjoo').fadeOut('fast');
		$('button#clearWishtlist').fadeOut('fast');
		
	}
	else
	{
		$('a#reqinfobtnkjoo').fadeIn('fast');
		$('button#clearWishtlist').fadeIn('fast');					
	}	
}


$(function () {
	$('a.filtkid').live("click", function(e){
		var id =  $(this).parent().attr("id");
		$('input#tableSearchButton').val(id);
		selectAllCats();
		loadData();
		e.preventDefault();
	});
});




$(function () {
	$('a.delwlit').live("click", function(e){
		var id =  $(this).parent().attr("id");
		var title = $('.kajooAddWishlist#kajooID'+id).attr('title');
		title = title+' <span class="titwishlikid">'+id+'</span>';
		var list = new cookieList("wishlist"); 
		list.remove(title);
		var items = list.items();
		$('span#numslist').html(items.length);

		 $(this).parent().remove();
		toggleSubmitMoreInfo();    
		e.preventDefault();
	});
});

$(function () {
	$('.fieldfilter').change(function(e) {
		toggleFilters();
		loadData();
		e.preventDefault(); 
	});
});

function toggleFilters()
{
	var listFilter = new cookieList("filterlist");
	listFilter.clear();
	var listFilter = new cookieList("filterlist");
	$('#fieldsKajoo > select').each(function (e) {
  		var fieldId =  $(this).attr("id");
  		var valueField = $(this).val();
  		var field = fieldId+':'+valueField;
  		listFilter.add(field);

	});
}

/* Search Button */
$(function () {
	$("button#searchBtn").click(function(e) {
  		 var valueSearch = $('input#tableSearchButton').val();
		 $.cookie("kajoo_valueSearch", valueSearch);
		loadData();
	});

});



$(function(){
	var code =null;
    $('input#tableSearchButton').keypress(function(e)
    {
        code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13){
	        var valueSearch = $('input#tableSearchButton').val();
	        $.cookie("kajoo_valueSearch", valueSearch);
	        loadData();
        };
       
    });

});

$(function () {
	$("button#searchBtnclear").click(function(e) {
  		 $('input#tableSearchButton').val('');
		 $.cookie("kajoo_valueSearch", '');
		loadData();
	});

});



$(function () {
	$("button#clearWishtlist").click(function(e) {
  		 var list = new cookieList("wishlist");
  		 list.clear();
  		   $('ul#wishlist li').fadeOut('slow', function() {
  		   	$(this).remove();
		    $("ul#wishlist").html('');
		    $('span#numslist').html('0');
		    toggleSubmitMoreInfo();
		  });
		  
	});

});

$(function () {
	$("a.pageChange").live("click", function(e){
  		 
  		 var isActive = $(this).parent().hasClass('active');
  		 if(!isActive)
  		 {
  		 	 selectedPage = $(this).attr("id");
			 loadData();
		 }
		 e.preventDefault(); 
	});

});
$(function () {
	$('select#limitKajoo').live("change", function(e){
		selectedPage = 1;
		loadData();
	});
});
$(function () {
	$("a#nextPag").live("click", function(e){
  		 if(selectedPage<totalPages)
  		 {
  		 	 selectedPage = selectedPage+1;
			 loadData();
		 }
		 e.preventDefault(); 
	});

});

$(function () {
	$("a#prevPag").live("click", function(e){
  	
  		 if(selectedPage>1)
  		 {
  		 	 selectedPage = selectedPage-1;
			 loadData();
		 }
		 e.preventDefault(); 
	});

});

$(function () {
	$("a#firstPag").live("click", function(e){

  		 if(selectedPage>1)
  		 {
  		 	 selectedPage = 1;
			 loadData();
		 }
		 e.preventDefault(); 
	});

});

$(function () {
	$("a#lastPag").live("click", function(e){
  		 

  		 if(selectedPage<totalPages)
  		 {
  		 	 selectedPage = totalPages;
			 loadData();
		 }
		 e.preventDefault(); 
	});

});






$(function () {
	$('#partner').change(function(e) {
		var partnerId = $(this).val();
		$.cookie("kajoo_partner", partnerId);
		loadData();
		loadCats();
		e.preventDefault(); 
	});
});

$(function () {
	$('.fieldfilter').change(function(e) {
		var fieldId = $(this).attr("id");
		var optionValue = $(this).val();
		loadData();
		e.preventDefault(); 
	});
});




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
   
  	$('input#tableSearchButton').val($.cookie('kajoo_valueSearch'));
  	$('select#partner').val($.cookie('kajoo_partner'));
  	
  	
	
	var listFields = new cookieList("filterlist");

		var itemsFilter = listFields.items();
		$.each(itemsFilter, function(e) {
			var fieldF = this.split(":");
			var idF = fieldF[0];
			var valF = fieldF[1];
			
			$('select#'+idF).val(valF);

		});	

	
	var list = new cookieList("wishlist");
	var items = list.items();
	$('span#numslist').html(items.length);
	
	$.each(items, function(e) {	
	var entryId = $(this+' span.titwishlikid').html();
		$("ul#wishlist").append("<li class='kajoowit wishitem"+entryId+"' id='"+entryId+"'><a href='#' class='ui-icon ui-icon-circle-close delwlit'></a>"+this+"</li>");

	});
	limit = 0;
	selectedPage = 1;
	selectAllCats();
	loadData();
	firstLoadCat();
	loadCats();
	
	toggleSubmitMoreInfo();
});
