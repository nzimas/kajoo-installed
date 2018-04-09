$(function () {

	   // Select List field
       $("div#field_values #sortable").sortable({
            revert: true
        });
       
       $("div#field_values button#btn_addvalue").click(function(e) {
	   		var value_select = $("input#value_select_item").val();
	   		if(value_select=='')
	   		{
		   		alert("Write something");
		   		$("input#value_select_item").focus();

	   		}
	   		else
	   		{
				e.preventDefault();
			    var $li = $("<li class='ui-state-default'>").html('<i class="icon-move"></i> '+value_select+' <button id="0" class="delete btn btn-mini btn-danger"> X </button><input type="hidden" value="'+value_select+'" name="field_values[]"><input type="hidden" value="-1" name="field_ids[]">');
			    $("div#field_values #sortable").append($li);
			    $("div#field_values #sortable").sortable('refresh');
			    $("input#value_select_item").focus();
			    $("input#value_select_item").val("");
	   		}


		});


		$("div#field_values #sortable button.delete").live("click", function(e){
			var id = $(this).attr("id");

			if(id==0)
			{
				$(this).parent().fadeOut('fast');
				$("div#field_values #sortable").sortable('refresh');
			}
			else
			{							
				$.ajax({
		            type: "GET",
		            url: "index.php",
		                 data: "option=com_kajoo&controller=field&task=field.deletevalue&format=raw&id=" + id,
		            cache: false,                          
		            success: function(data){   
		         
		            	$('li#value'+id).fadeOut('fast');
		            	$("div#field_values #sortable").sortable('refresh');   
		          }                         
		            
		         });  
			
			}
				
		    e.preventDefault();
		   

		});

 	  var sel_val = $("#jform_type option:selected").val();
	  if(sel_val==1){
		$('div#select_list_values_container').fadeIn('fast');
	  }
			  
		$('#jform_type').change(function() {
		  	  var sel_val = $("#jform_type option:selected").val();
			  if(sel_val==2){
				$('div#select_list_values_container').fadeOut('fast');	  
			  }
			  else{
				$('div#select_list_values_container').fadeIn('fast');
			  }
		});
		

});