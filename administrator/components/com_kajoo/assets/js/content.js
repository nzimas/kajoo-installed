$(function () {
	$('div#ajaxLoader span').fadeIn();
	$("#embedarea").focus(function() {
	    var $this = $(this);
	    $this.select();
	
	    // Work around Chrome's little problem
	    $this.mouseup(function() {
	        // Prevent further mouseup intervention
	        $this.unbind("mouseup");
	        return false;
	    });
	});

		$("a.change").live("click", function(e){
		var configid = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var entryid = $('input#embedentryId').val();
		var url = "option=com_kajoo&controller=content&task=content.getEmbedPlayer&format=raw&tmpl=component&uiConf="+configid+"&partnerid="+partnerid+"&entryid="+entryid;
	
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#kajooEmbed').html(data);
		             var content = data.replace(/(\r\n|\n|\r)/gm,"");
		             content = content.toString().replace(/\t/g, '').split('\r\n');
		            $('textarea#embedarea').val(content);
		            $('div#ajaxLoader div#spin').fadeOut();
	          }                         
	            
	         });
     

	    e.preventDefault();
	   

		});
		
		
		$("a.setDefault").live("click", function(e){
		var configid = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.setDefaultPlayer&format=raw&tmpl=component&uiConf="+configid+"&partnerid="+partnerid;
		$("table.tablePlayers tr").removeClass("currentPlayer");
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $("table.tablePlayers tr#player"+configid).addClass("currentPlayer");
		            alert(data);
		            $('div#ajaxLoader div#spin').fadeOut();
	          }                         
	            
	         });
     

	    e.preventDefault();
	   

		});
		
});

$(function () {
	$("a.convertflavor").live("click", function(e){
	
		var flavorParamId = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var entryid = $('input#embedentryId').val();
		var url = "option=com_kajoo&controller=content&task=content.convert&format=raw&tmpl=component&flavorParamId="+flavorParamId+"&partnerid="+partnerid+"&entryid="+entryid;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();
		            $('div.convert_container#'+flavorParamId).html('<span class="spin1"></span>');

	          }                         
	            
	         });
	
	e.preventDefault(); 
	});

});


$(function () {
	$("a.getTimethumb").click(function(e) {
		
		var entryid = $('input#embedentryId').val();
		var partnerid = $('input#embedpartnerId').val();
		GenerateThumb(entryid,partnerid);
		e.preventDefault(); 
	});

});



function GenerateThumb(entryid,partnerid)
{
	var videotime = $("input#videotime").val();
	var serviceurl = $("input#serviceurl").val();
	
	var url = "option=com_kajoo&controller=content&task=content.changeThumbnail&format=raw&tmpl=component&videotime="+videotime+"&entryid="+entryid+"&partnerid="+partnerid+"&serviceurl="+serviceurl;
	$.ajax({
        type: "GET",
        url: "index.php",
             data: url,
        cache: false,
        beforeSend: function(){
            $('div#ajaxLoader div#spin').fadeIn();
        },                          
        success: function(data){   
            $('div#ajaxLoader div#spin').fadeOut();
    
	        var json = $.parseJSON(data);

	        var thumbJson = serviceurl+'/p/'+json.partnerId+'/sp/0/thumbnail/entry_id/'+json.entryId+'/width/120/height/90';

            $("#thumbreview").attr("src",json.urlthumb);
            $("img.collectionthumbs").removeClass("defaultThumb");
           
            
            var htmlAppendImage = '<li class="ui-widget-content ui-corner-tr" id="'+json.id+'"> \
            <img class="collectionthumbs" id="thumb'+json.id+'" src="'+json.urlthumb+'" style="height:80px;" /> \
            <a id="'+json.id+'" href="#" title="Set as default" class="ui-icon ui-icon-star">Set as default</a> \
            <a id="'+json.id+'" href="#" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a> \
			</li>';
			
			$(htmlAppendImage).hide().appendTo('#gallerythumbs').fadeIn('slow');
      }                         
        
     });

 
}


$(function () {
	$("a.setdefault").live("click", function(e){
	
		var thumbAssetId = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.setDefaultthumb&format=raw&tmpl=component&thumbAssetId="+thumbAssetId+"&partnerid="+partnerid;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();	
		            
		            $("img.collectionthumbs").removeClass("defaultThumb");
		            $("img#thumb"+thumbAssetId).addClass("defaultThumb");
		            var newSRC = $("img#thumb"+thumbAssetId).attr("src");
		            $("#thumbreview").attr("src",newSRC);
		    

	          }                         
	            
	         });
	
	e.preventDefault(); 
	});

});


$(function () {
	$("a.reconvertflavor").live("click", function(e){
	
		var flavorAssetId = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.reconvert&format=raw&tmpl=component&flavorAssetId="+flavorAssetId+"&partnerid="+partnerid;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();
		            $('div.convert_container#'+flavorAssetId).html('<span class="spin1"></span>');
		            

	          }                         
	            
	         });
	
	e.preventDefault(); 
	});

});


$(function () {
	$("a.deleteflavor").live("click", function(e){
	
		var flavorAssetId = $(this).attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.delete&format=raw&tmpl=component&flavorAssetId="+flavorAssetId+"&partnerid="+partnerid;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();
		            $('div.convert_container#'+flavorAssetId).html('<span class="spin1"></span>');
		            

	          }                         
	            
	         });
	
	e.preventDefault(); 
	});

});



$(function () {	
	
	$('#myTabsContent a').click(function (e) {
	    var currenttab = $(this).attr("href");
	    $('input#currenttab').val(currenttab);

    });

 });




function deleteImage( $item ) {
    $item.fadeOut(function() {
        $item.find( "a.ui-icon-trash" ).remove();

    });
}


function setDefaultThumbnail( $item ) {
        var thumbAssetId = $item.attr("id");
        var itemId = $('input#itemId').val();
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.setDefaultthumb&format=raw&tmpl=component&thumbAssetId="+thumbAssetId+"&partnerid="+partnerid+"&itemId="+itemId;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();	

		            
		            var newSRC = $("img#thumb"+thumbAssetId).attr("src");
		            $("#thumbreview").attr("src",newSRC);
		           
		            
		            alert("Default thumbnail set");

	          }                         
	            
	         });

}

function deleteImage( $item ) {
    $item.fadeOut(function() {
        var thumbAssetId = $item.attr("id");
		var partnerid = $('input#embedpartnerId').val();
		var url = "option=com_kajoo&controller=content&task=content.deleteThumb&format=raw&tmpl=component&thumbAssetId="+thumbAssetId+"&partnerid="+partnerid;
			$.ajax({
	            type: "GET",
	            url: "index.php",
	                 data: url,
	            cache: false,
	            beforeSend: function(){
		            $('div#ajaxLoader div#spin').fadeIn();
	            },                          
	            success: function(data){   
		            $('div#ajaxLoader div#spin').fadeOut();	

	          }                         
	            
	         });
    });
}


        






$(function () {	
        $( "ul.gallerythumbs > li" ).live("click", function(event){
            var $item = $( this ),
                $target = $( event.target );
 
            if ( $target.is( "a.ui-icon-trash" ) ) {
                deleteImage( $item );
            } else if ( $target.is( "a.ui-icon-star" ) ) {
                setDefaultThumbnail( $item );
            }
 
            return false;
        }); 
});        