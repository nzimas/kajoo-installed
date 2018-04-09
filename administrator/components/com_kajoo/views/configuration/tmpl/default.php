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
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/configuration.js');

$positions = json_decode($this->positions);
$canDo		= KajooHelper::getActions();

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

<form action="<?php echo JRoute::_('index.php?option=com_kajoo'); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<div id="ajaxLoader"><div id="spin"></div></div>



<?php if($canDo->get('kajoo.managecategories')):?>

	<div class="confing_container">
	
	<?php
	$partners = KajooHelper::getPartnersList();
	
	$currentPartner = JRequest::getVar('partnerid', $partners[0]->id, 'GET', 'INT');
	$categories = KajooHelper::getCategoryList($currentPartner);
	?>
		<legend><?php echo JText::_('COM_KAJOO_CONFIG_CATS');?></legend>
		    <div class="alert alert-info">
		    	<?php echo JText::_('COM_KAJOO_POSFRONTEND_ALERT');?>
		    </div>
		    
	
			<div class="span4">
	<div class="well">
	
	<?php
				
				    function drawMenu ($categories,$i,$currentPartner) {
					    echo '<ul class="nav nav-list">';
					   
					    foreach ($categories as $item) {
					        
					        if (isset($item->childs)) 
					        {
					        	echo '<li class="maincategory">' . $item->name.'</li>';
					            drawMenu($item->childs,$i,$currentPartner);
					        }
					        else
					        {
		 echo '<li><a id="'.$item->id.'" class="deleteCat" href="'.JRoute::_('index.php?option=com_kajoo&controller=configuration&task=configuration.deleteCat&partnerid='.$currentPartner.'&format=raw&id='.(int) $item->id).'">' . $item->name.'</a></li>';
					        }
	
					        $i++;
					    }
					    echo "</ul>";
					    
					}
					
					function drawSelect ($categories,$i) {
					    foreach ($categories as $item) {
					        
					        if (isset($item->childs)) 
					        {
					        	echo '<option class="cat" value="'.$item->id.'">' . $item->fullName.'</option>';
					            drawSelect($item->childs,$i);
					        }
					        else
					        {
						       echo '<option class="cat" value="'.$item->id.'">' . $item->fullName.'</option>';
					        }
	
					        $i++;
					    }
			
					    
					}
					
					if($categories):
					?>
					<h3><?php echo JText::_('COM_KAJOO_POSFRONTEND_CURRENTCATS');?></h3>
					<?php
						drawMenu($categories,$i=0,$currentPartner);
						?>
						<hr>
						<h3><?php echo JText::_('COM_KAJOO_CONFIG_ADDCAT');?></h3>
						<label for="catParent"><?php echo JText::_('COM_KAJOO_CONFIG_PARENTCAT');?></label>
						<select class="input-xlarge" name="catParent" id="catParent">
							<option value="0"><?php echo JText::_('COM_KAJOO_CONFIG_MAINCAT2');?></option>
							<?php  drawSelect ($categories,$i);?>				
			
						</select>
						
						<label for="catname"><?php echo JText::_('COM_KAJOO_CONFIG_CATNAME');?></label>
						<input type="text" placeholder="<?php echo JText::_('COM_KAJOO_CONFIG_CATNAME');?>" name="catname" id="catname">
						<input type="hidden" id="partnerid" name="partnerid" value="<?php echo $currentPartner;?>">
						<br>
						<a href="#" id="newcat" class="btn btn-success"><?php echo JText::_('COM_KAJOO_CONFIG_ADDCAT');?></a>
						<?php
					endif;
				    ?>
	
	</div>	   
			</div>
			<div class="span4">
			<div class="well">
			 			    <ul class="nav nav-list">
				    	<li class="nav-header"><?php echo JText::_('COM_KAJOO_POSFRONTEND_AVAILABLEPARTNERS');?></li>
					    <?php foreach($partners as $partner):?> 
					    	<li<?php if($currentPartner==$partner->id) echo ' class="active"';?>><a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=configuration&partnerid='.(int) $partner->id);?>"><?php echo $partner->name;?></a></li>
					    <?php endforeach;?>
				    
				    
				    </ul>
			</div>
			</div>
	 
		    
	</div>	    
	
	<div class="clearfix"></div>
<?php endif;?>

<div class="confing_container">

	<legend><?php echo JText::_('COM_KAJOO_POSFRONTEND');?></legend>
	    <div class="alert alert-info">
	    	<?php echo JText::_('COM_KAJOO_POSFRONTEND_ALERTPOS');?>
	    </div>
	    
	    <div class="controlsConfig">	
	    <a href="<?php echo JRoute::_('index.php?option=com_kajoo&controller=configuration&task=configuration.resetPositions&format=raw'); ?>" class="btn"><?php echo JText::_('COM_KAJOO_POSFRONTEND_RESDEFPOS');?></a>
	    </div>
	<div class="column" id="col1">
	 
	 	
	 	<?php if($positions[0]!='empty'): ?>
	 		<?php foreach($positions[0] as $position):?>
	 		
			 	<div class="portlet<?php if($position!='maincontent') echo " cansort";?>" id="<?php echo $position;?>">
			        <div class="portlet-header"><?php echo $alias[$position]['name'];?></div>
			        <div class="portlet-content">
			        </div>
			    </div>
			    
	 		<?php endforeach;?>
	 	<?php endif;?>

	</div>
	 
	<div class="column main disablecolumn" id="col2">
	 
	 	<?php if($positions[1]!='empty'): ?>
	 		<?php foreach($positions[1] as $position):?>
	 		
			 	<div class="portlet<?php if($position!='maincontent') echo " cansort";?>" id="<?php echo $position;?>">
			        <div class="portlet-header"><?php echo $alias[$position]['name'];?></div>
			        <div class="portlet-content">
			        </div>
			    </div>
			    
	 		<?php endforeach;?>
	 	<?php endif;?>
	 
	</div>
	 
	<div class="column" id="col3">
	 
	 	<?php if($positions[2]!='empty'): ?>
	 		<?php foreach($positions[2] as $position):?>
	 		
			 	<div class="portlet<?php if($position!='maincontent') echo " cansort";?>" id="<?php echo $position;?>">
			        <div class="portlet-header"><?php echo $alias[$position]['name'];?></div>
			        <div class="portlet-content">
			        </div>
			    </div>
			    
	 		<?php endforeach;?>
	 	<?php endif;?>

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
