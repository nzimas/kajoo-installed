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
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/validation.js');
$valid = KajooHelper::isValid();

$app =JFactory::getApplication();

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


<?php
$url = JURI::base(); 
$url_ar = parse_url($url);
$url_ar = preg_replace('#^www\.(.+\.)#i', '$1', $url_ar['host']);

?>

<div class="confing_container">


	<legend><?php echo JText::_('COM_KAJOO_TITLE_VALIDATE');?></legend>
	
	
	<?php if($valid[0]):?>	
	    <div class="alert alert-info">
	    	<?php echo JText::sprintf('COM_KAJOO_TITLE_VALIDATE_ALERTVALID', $valid[4]);?>
	    </div>
	<?php else:?>
		 <div class="alert alert-error">
	    	<?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_ALERTVALIDNOT');?>
	    </div>   
	<?php endif;?>    
	    
<div class="control-group">
	<label class="control-label" for="validation"><?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_VALIDATIONKEY');?></label>
	<div class="controls">
		<input type="text" id="validation" placeholder="Validation" name="validation" class="input-xlarge" value="<?php echo $valid[1];?>">
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="license"><?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_LICKEY');?></label>
	<div class="controls">
		<input type="text" id="license" placeholder="License" name="license" class="input-xlarge" value="<?php echo $valid[2];?>">
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="urlVal"><?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_DOMAIN');?></label>
	<div class="controls">
		<input type="text" id="urlVal" placeholder="<?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_DOMAIN');?>" name="urlVal" class="input-xlarge" value="<?php echo $url_ar;?>" disabled>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<button type="submit" id="check" class="btn btn-success"><?php echo JText::_('COM_KAJOO_TITLE_VALIDATE_LICKEYVALIDATEBUTTON');?></button>
	</div>
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
