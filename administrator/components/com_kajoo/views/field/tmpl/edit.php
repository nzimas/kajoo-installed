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
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/field.js');
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

<form action="<?php echo JRoute::_('index.php?option=com_kajoo&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<div class="row">
	<div class="span3">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_KAJOO_LEGEND_FIELD'); ?></legend>
			<ul class="adminformlist">

            			
			
			<li><?php echo $this->form->getLabel('type'); ?>
			<?php echo $this->form->getInput('type'); ?><hr></li>
			
			<li><?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?></li>
			
            
			<li><?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?></li>


            
			<li><?php echo $this->form->getLabel('filtrable'); ?>
			<?php echo $this->form->getInput('filtrable'); ?></li>

            
			<li><?php echo $this->form->getLabel('sitevisible'); ?>
			<?php echo $this->form->getInput('sitevisible'); ?></li>

            
			<li><?php echo $this->form->getLabel('adminvisible'); ?>
			<?php echo $this->form->getInput('adminvisible'); ?></li>

            

            <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li><li><?php echo $this->form->getLabel('checked_out'); ?>
                    <?php echo $this->form->getInput('checked_out'); ?></li><li><?php echo $this->form->getLabel('checked_out_time'); ?>
                    <?php echo $this->form->getInput('checked_out_time'); ?></li>

            </ul>
		</fieldset>
	</div>
	

	
	
	<div class="span3" id="select_list_values_container">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_KAJOO_LEGEND_SELECTVALUES'); ?></legend>
	
					
						
				  
				<div id="field_values">	
					
					<div class="select_values_container">
						<input type="text" class="input-medium" placeholder="<?php echo JText::_('COM_KAJOO_LEGEND_FIELD_LISTVALUE'); ?>" id="value_select_item"><br />
						<button id="btn_addvalue" class="btn btn-info"><?php echo JText::_('COM_KAJOO_LEGEND_FIELD_ADD_VALUESELECT'); ?></button>
					</div>
					
					<ul id="sortable">
					<?php foreach($this->item->values as $key=>$value): ?>
						<li class="ui-state-default" id="value<?php echo $value->id;?>"><i class="icon-move"></i> <?php echo $value->value;?> 
						<button class="delete btn btn-mini btn-danger" id="<?php echo $value->id;?>"> X </button>
						<input type="hidden" value="<?php echo $value->value;?>" name="field_values[]">
						<input type="hidden" value="<?php echo $value->id;?>" name="field_ids[]"></li>
					<?php endforeach; ?>
					
					</ul>
				</div>	
		</fieldset>		
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