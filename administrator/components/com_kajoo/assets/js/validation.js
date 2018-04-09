$(function() {

	$("button#check").live("click", function(e){

    	var validation = $('input#validation').val();
    	var license = $('input#license').val();
    	var urlVal = $('input#urlVal').val();
    	var colArray = new Array(5);  	

		var urlJSON = "http://www.freebandtech.com/index.php?option=com_fbtechsubscriptions&view=check&format=raw&validation="+validation+"&license="+license+"&url="+urlVal+"&callback=?"; 
		
		$.getJSON(urlJSON,function(res){
			colArray[0] = res.isvalid;
			colArray[1] = validation;
			colArray[2] = license;
			colArray[3] = res.expired;
			colArray[4] = res.exptime;
			colArray[5] = res.domain;
				$.ajax({
			        type: "POST",
			        url: "index.php?option=com_kajoo&controller=configuration&task=configuration.validate&format=raw&tmpl=component",
			        data: { data: colArray },
			        cache: false,
			        beforeSend: function(){
			           $('div#ajaxLoader div#spin').fadeIn();
			        },                          
			        success: function(data){   
			            $('div#ajaxLoader div#spin').fadeOut();
			            location.reload();
			      }                         
			        
			     });

		});
		

	    e.preventDefault();   

	});

});