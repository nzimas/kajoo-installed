$(function() {

 $('.addPartner').click(function() {
  return !$('#select1 option:selected').remove().appendTo('#select2');
 });
 $('.removePartner').click(function() {
  return !$('#select2 option:selected').remove().appendTo('#select1');
 });


 $('.addField').live("click", function(){
 	var id = $(this).attr("id");
 	return !$('#selectField1'+id+' option:selected').remove().appendTo('#selectField2'+id);
 });
 $('.removeField').live("click", function(){
 	var id = $(this).attr("id");
	return !$('#selectField2'+id+' option:selected').remove().appendTo('#selectField1'+id);
 });



});



/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}




$("button#generatePdfButTon").live("click", function(e){
var configid = $(this).attr("id");
var partnerid = $('input#embedpartnerId').val();
var entryid = $('input#embedentryId').val();
var url = "option=com_kajoo&controller=reports&task=reports.getInfo&format=raw&tmpl=component";


//Get Custom filters
var fieldFilters = [];
$("form#field-form input.filterExpField:checked").each(function(){
 var input = $(this); // This is the jquery object of the input, do what you will
 fieldFilters.push(input.val());
});

  //Get export_thumb show
  if($("input:checkbox[name=export_thumb]").attr("checked"))
  {
	  var showThumb = 1;
  }
  else
  {
	  var showThumb = 0;
  }
  
  //Get export_title show
  if($("input:checkbox[name=export_title]").attr("checked"))
  {
	  var showTitle = 1;
  }
  else
  {
	  var showTitle = 0;
  }
  
  //Get export_showid show
  if($("input:checkbox[name=export_showid]").attr("checked"))
  {
	  var showId = 1;
  }
  else
  {
	  var showId = 0;
  }
  
  //Get export_showpartner show
  if($("input:checkbox[name=export_showpartner]").attr("checked"))
  {
	  var showPartner = 1;
  }
  else
  {
	  var showPartner = 0;
  }

//Get titles
var arrayTitles = [];
$("table#tableSelected tbody tr.titleSelected").each(function(){
	var idTitle = $(this).attr("id");
	arrayTitles.push(idTitle);
});

if(arrayTitles.length==0)
{
	alert("Please select at least one title");
}
else
{

	$.ajax({
        type: "POST",
        url: "index.php?option=com_kajoo&view=reportspdf&format=raw&tmpl=component",
        data: { showThumb: showThumb, showTitle: showTitle, showId:showId, showPartner:showPartner,fieldFilters:fieldFilters,arrayTitles:arrayTitles },
        cache: false,
        beforeSend: function(){
            $('div#ajaxLoader div#spin').fadeIn();
            $("#downloadModal").modal('show');
            $("#btndownload").hide();
            
        },                          
        success: function(data){   

            $("#btndownload").fadeIn();
            $("#btndownload").attr("href",data);
            
            
            $('div#ajaxLoader div#spin').fadeOut();
      }                         
        
     });
}

e.preventDefault();


});


    // ensure modal is only shown on page load
    $(function() {
	    // wire up the buttons to dismiss the modal when shown
	    $("#downloadModal").bind("show", function() {
		    $("#downloadModal a.btn").click(function(e) {
		    // hide the dialog box
		    $("#downloadModal").modal('hide');
	    });
    });
     
    // remove the event listeners when the dialog is hidden
    $("#downloadModal").bind("hide", function() {
	    // remove event listeners on the buttons
	    $("#downloadModal a.btn").unbind();
    });
     
    // finally, wire up the actual modal functionality and show the dialog
    $("#downloadModal").modal({
	    "backdrop" : "static",
	    "keyboard" : true,
	    "show" : false // this parameter ensures the modal is shown immediately
	    });
    });

/* Table initialisation */
$(document).ready(function() {

	
	var oTable = $('#contentTable').dataTable( {
        "bSortClasses": false,
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		},
		"aoColumnDefs": [        
            { "bVisible": false, "aTargets": [ 0 ] }
        ]
		
    });
   

    $("#contentTable tbody tr").live("click", function(e){

        if ( $(this).hasClass('row_selected') ) {
            $(this).removeClass('row_selected');
            var idRow = $(this).attr('id');        
            $('#tableSelected tbody tr.'+idRow).remove();
        }
        else {
            $(this).addClass('row_selected');
            var htmlRow = $(this).html();
            var idRow = $(this).attr('id');
            $('#tableSelected').append('<tr class="'+idRow+' titleSelected" id="'+idRow+'">'+htmlRow+'<td><a href="javascript:void(0);" class="btn btn-danger delCon">Delete</a></td></tr>');
          
        }
        
    });
    
    $("#tableSelected tbody tr a.delCon").live("click", function(e){
	    var idRow = $(this).parent().parent().attr('class');
	    $("#contentTable tbody tr#"+idRow).removeClass('row_selected');
	    $(this).parent().parent().remove();
        
    }); 
     
var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      $(this).width($originals.eq(index).width())
    });
    return $helper;
};
     
$("#tableSelected tbody").sortable({
    helper: fixHelperModified
    
}).disableSelection();     
     
     
    /* Add a click handler for the delete row */
    $('#delete').click( function() {
        var anSelected = fnGetSelected( oTable );
        if ( anSelected.length !== 0 ) {
            oTable.fnDeleteRow( anSelected[0] );
        }
    } );
     
    /* Init the table */
    oTable = $('#contentTable').dataTable( );
    
    
    /* Get the rows which are currently selected */
	function fnGetSelected( oTableLocal )
	{
	    return oTableLocal.$('tr.row_selected');
	}
	
});