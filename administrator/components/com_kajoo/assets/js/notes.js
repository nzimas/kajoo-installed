$(function () {
	$("#addClientBtn").live("click", function(e){
		
		var $inputs = $('#addClient :input');
		var values = {};
		    $inputs.each(function() {
		        values[this.name] = $(this).val();
		   
		    });
		
		    if(values['name']=='')
		    {
			    alert("Write a name");
		    }
		    else
		    {
			    
		    
			$.ajax({
	            type: "POST",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.addClient&format=raw&tmpl=component",
	            data: values,
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            loadClients();
	          }                         
	            
	         });
	         }
	         
	e.preventDefault(); 
	});

});


$(function () {
	$("#toolbar-new button").live("click", function(e){
		
	addClientMode();	
		         
	e.preventDefault(); 
	});

});


$(function () {
	$("#editClientBtn").live("click", function(e){

		var $inputs = $('#addClient :input');
		var values = {};
		    $inputs.each(function() {
		        values[this.name] = $(this).val();
		   
		    });
		
		    if(values['name']=='')
		    {
			    alert("Write a name");
		    }
		    else
		    {
			    
		    
			$.ajax({
	            type: "POST",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.editClient&format=raw&tmpl=component",
	            data: values,
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            addClientMode();
		            loadClients();
	          }                         
	            
	         });
	         }
	         
	e.preventDefault(); 
	});

});


$(function () {
	$(".deleteClient").live("click", function(e){
		var ClientId = $(this).attr("id");

			$.ajax({
	            type: "GET",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.deleteClient&format=raw&tmpl=component&id="+ClientId,
	            data: '',
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            loadClients();
		            addClientMode();
		   
		            
		            }                     
	            
	         });
	         
	e.preventDefault(); 
	});

});

$(function () {
	$("#cancelClientBtn").live("click", function(e){
			addClientMode();
	         e.preventDefault(); 
	});

});

$(function () {
	$(".addContent").live("click", function(e){
			var values = {};
			values['idClient'] = $('div#clientAddEdit form input#idClient').val();
			values['idContent'] = $(this).attr('id');
		
			$.ajax({
	            type: "POST",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.assignContent&format=raw&tmpl=component",
	            data: values,
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            loadClientData(values['idClient']);
		            loadClients();
	          }                         
	            
	         });
	        e.preventDefault(); 
	});

});

$(function () {
	$(".deleteContent").live("click", function(e){
			var values = {};
			values['idClient'] = $('div#clientAddEdit form input#idClient').val();
			values['idContent'] = $(this).attr('id');
		
			$.ajax({
	            type: "POST",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.deleteContent&format=raw&tmpl=component",
	            data: values,
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            loadClientData(values['idClient']);
		            loadClients();
	          }                         
	            
	         });
	        e.preventDefault(); 
	});

});



$(function () {
	$(".iconState").live("click", function(e){
			var values = {};
			values['idClient'] = $('div#clientAddEdit form input#idClient').val();
			values['idStatus'] = $(this).attr('id');
			values['soldStatus'] = $(this).attr('title');
			$.ajax({
	            type: "POST",
	            url: "index.php?option=com_kajoo&controller=notes&task=notes.changeState&format=raw&tmpl=component",
	            data: values,
	            cache: false,
	            beforeSend: function(){
	            },                          
	            success: function(data){   
		            loadClientData(values['idClient']);
		            loadClients();
	          }                         
	            
	         });
	        e.preventDefault();  
	});

});


function addClientMode()
{
			$('#titEdAdd').html('Add new client');
			$('#editClientBtn').attr('id', 'addClientBtn');
			$('#addClientBtn').attr('id', 'addClientBtn');
			$('div#clientAddEdit form input#idClient').val('');
			$('#addClientBtn').html('Add Client');
	         $('div#clientAddEdit form input').val('');
	         $("table#tableClients tr").removeClass("activeRow");
	         $('div#clientAddEdit form textarea').val('');
	         $("div#clientDataWrapper").fadeOut();
	         $("div#clientAddEdit").fadeIn();
}
function editClientMode(ClientId)
{
		$('#titEdAdd').html('Edit Client');
		$('#addClientBtn').html('Update Client');
		$('#addClientBtn').attr('id', 'editClientBtn');
		$("table#tableClients tr").removeClass("activeRow");
		$("table#tableClients tr#"+ClientId).addClass("activeRow");
		$("div#clientDataWrapper").fadeIn();
		$("div#clientAddEdit").fadeIn();
		
		$.getJSON('index.php?option=com_kajoo&controller=notes&task=notes.getClient&format=raw&tmpl=component&id='+ClientId, function(data) {
			
			$.each(data, function(key, val) {
				
				$('div#clientAddEdit form input#idClient').val(val.id);
				$('div#clientAddEdit form input#name').val(val.name);
				$('div#clientAddEdit form input#company').val(val.company);
				$('div#clientAddEdit form input#position').val(val.position);
				$('div#clientAddEdit form input#email').val(val.email);
				$('div#clientAddEdit form input#phone').val(val.phone);
				$('div#clientAddEdit form textarea#observations').val(val.observations);
				
			});
		 });


}


$(function () {
	$("#addClientBtnSlide").live("click", function(e){
			addClientMode();
	        e.preventDefault();  
	});

});



$(function () {
	$(".rowClient").live("click", function(e){
		var ClientId = $(this).attr("id");
		
		var isActive = $(this).hasClass('activeRow');
		if(isActive)
		{
			addClientMode();
		}
		else
		{
			editClientMode(ClientId);
		}
		
		
		loadClientData(ClientId);
    
		e.preventDefault(); 
	});

});


function loadClients()
{
	var url = "index.php?option=com_kajoo&view=notes&format=raw&layout=default_clients";

	$.ajax({
        type: "POST",
        url: url,
        cache: false,
        beforeSend: function(){
	        $('div#ajaxLoader div#spin').fadeIn();
        },                          
        success: function(data){
        	$('div#ajaxLoader div#spin').fadeOut();
        	$('div#clientsCont').html(data);
            
       
            $('#tableClients').dataTable( {
		        "bStateSave": true,
		        "bJQueryUI": true,
		        "bSort": false,
		        "bAutoWidth": true
		    });
            
		    
		    $(".popoverObs").popover({ title: 'Look! A bird!',trigger:'hover',placement:'left'});

          
      }                         
        
     });
     		
}

function loadClientData(ClientId)
{
	var url = "index.php?option=com_kajoo&view=notes&format=raw&layout=default_clientdata&clientId="+ClientId;

	$.ajax({
        type: "POST",
        url: url,
        cache: false,
        beforeSend: function(){
	        $('div#ajaxLoader div#spin').fadeIn();
        },                          
        success: function(data){
        	$('div#ajaxLoader div#spin').fadeOut();
        	$('div#clientData').html(data);

      }                         
        
     });
     		
}


$(document).ready(function() {
	loadClients();
	$('#toolbar-new button').attr('onclick', '');
	
	        $('#tableContents').dataTable( {
		        "bStateSave": true,
		        "bJQueryUI": true,
		        "bSort": false,
		        "bAutoWidth": true
		    });

	
});