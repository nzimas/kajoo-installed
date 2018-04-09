<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/data-tables/DT_bootstrap.css');
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/data-tables/js/jquery.dataTables.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/data-tables/DT_bootstrap.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/reports.js');


$alias = KajooHelper::getFrontEndPositionDetails();


$app = JFactory::getApplication();

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form'))) {
			Joomla.submitform(task, document.getElementById('field-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>




    <!-- set up the modal to start hidden and fade in and out -->
    <div id="downloadModal" class="modal hide fade">
     <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3><?php echo JText::_('COM_KAJOO_ALERT_CONTENT_DOWNLINK');?></h3>
	</div>
	    <!-- dialog contents -->
	    <div class="modal-body" style="text-align:center;">
	    	<div id="ajaxLoader"><div id="spin"></div></div>
	     	<a href="#" id="btndownload" style="display:none;" target="_blank" class="btn btn-success"><i class="icon-download"></i> <?php echo JText::_('COM_KAJOO_ALERT_CONTENT_DOWNLINKPDF');?></a>
	    </div>
	    <!-- dialog buttons -->
	    <div class="modal-footer">
	    <a href="#" class="btn primary">OK</a>
	    </div>
    </div>


<form action="<?php echo JRoute::_('index.php?option=com_kajoo'); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>




<div class="confing_container">


<div class="row-fluid">
	<div class="span12">
			<div class="well well-small">
			
			<div class="alert alert-info"><?php echo JText::_('COM_KAJOO_ALERT_CONTENT_REPINFO');?></div>
			
					<table cellpadding="0" cellspacing="0" border="0" class="table" id="contentTable">
						<thead>
							
							<tr>
								<th></th>
								<th>#</th>
								<th><?php echo JText::_('COM_KAJOO_TITLE');?></th>	
								<th><?php echo JText::_('COM_KAJOO_CONTENTEDIT_VIDEODESC');?></th>	
								<th><?php echo JText::_('COM_KAJOO_ID');?></th>
								<th><?php echo JText::_('COM_KAJOO_TITLE_PARTNER');?></th>
								<?php foreach ($this->allFields as $field):?>
								<th><?php echo $field->name;?></th>
								<?php endforeach;?>				
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->allContents as $key=>$content):?>
						
							<tr id="<?php echo $content->id;?>" class="rowContent">
								<td class="searchTextReport"><?php echo $content->searchText;?></td>
								<td><img src="<?php echo $content->thumburl;?>" style="height:20px; width:20px;"/></td>
								<td><?php echo $content->name;?></td>
								<td><i style="font-size:85%; color:#999;"><?php echo KajooHelper::limit_words($content->description,6);?>...</i></td>	
								<td><i style="font-size:85%; color:#999;"><?php echo $content->entry_id;?></i></td>
									<td>
									<?php $partner = KajooHelper::getContentPartnerValue($content->partner_id);
										if($partner!=''):
											echo $partner;
										else:
											echo '-';
										endif;
										
									?></td>
								<?php foreach ($this->allFields as $field):?>
									<td>
									<?php $alias = KajooHelper::getContentFieldValue($content->id,$field->id,$field->type);
										
										if($alias!=''):
											echo $alias;
										else:
											echo '-';
										endif;
										
									?></td>
								<?php endforeach;?>
						
							</tr>
							<?php endforeach;?>
						</tbody>
						
					</table>	
					
					<hr>
					<h3><?php echo JText::_('COM_KAJOO_TITLE_CHOSEDCONTENT');?></h3>	
					<div class="alert alert-info"><?php echo JText::_('COM_KAJOO_ALERT_CONTENT_REPINFOSELECTED');?></div>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped" id="tableSelected">
						<thead>
							
							<tr>
								
								<th><input type="checkbox" name="export_thumb" value="1" checked="checked" class="filterExp"> <?php echo JText::_('COM_KAJOO_THUMBNAIL');?></th>
								<th><input type="checkbox" name="export_title" value="1" checked="checked" class="filterExp"> <?php echo JText::_('COM_KAJOO_TITLE');?></th>
								<th><input type="checkbox" name="export_desc" value="1" checked="checked" class="filterExp"> <?php echo JText::_('COM_KAJOO_CONTENTEDIT_VIDEODESC');?></th>	
								<th><input type="checkbox" name="export_showid" value="1" checked="checked" class="filterExp"> <?php echo JText::_('COM_KAJOO_ID');?></th>
								<th><input type="checkbox" name="export_showpartner" value="1" checked="checked" class="filterExp"> <?php echo JText::_('COM_KAJOO_TITLE_PARTNER');?></th>
								<?php foreach ($this->allFields as $field):?>
								<th><input type="checkbox" name="fieldsFilter[]" value="<?php echo $field->id;?>" checked="checked" class="filterExpField"> <?php echo $field->name;?></th>
								<?php endforeach;?>	
								<th></th>			
							</tr>
						</thead>
						<tbody>
						
						</tbody>
					</table>
					<hr>
					<button id="generatePdfButTon" class="btn btn-success"><?php echo JText::_('COM_KAJOO_GENERATEPDF');?></button>


</div>

</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
	</div>    
</form>
