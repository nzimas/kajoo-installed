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

		


?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'partner.cancel' || document.formvalidator.isValid(document.id('partner-form'))) {
			Joomla.submitform(task, document.getElementById('partner-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_kajoo&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="partner-form" class="form-validate">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<div class="fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_KAJOO_LEGEND_PARTNER'); ?></legend>

	
			
		    <div class="alert alert-info">
		    <?php echo $this->escape(JText::_('KAJOO_INFOPARNER'));?>
		    </div>	
		    		
			<ul class="adminformlist">

            
			<li><?php echo $this->form->getLabel('id'); ?>
			<?php echo $this->form->getInput('id'); ?></li>

            
			<li><?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?></li>

            
			<li><?php echo $this->form->getLabel('partnerid'); ?>
			<?php echo $this->form->getInput('partnerid'); ?></li>

            
			<li><?php echo $this->form->getLabel('administratorsecret'); ?>
			<?php echo $this->form->getInput('administratorsecret'); ?></li>

            
			<li><?php echo $this->form->getLabel('usersecret'); ?>
			<?php echo $this->form->getInput('usersecret'); ?></li>
			
			<li><?php echo $this->form->getLabel('url'); ?>
			<?php echo $this->form->getInput('url'); ?></li>

            
			<li><?php echo $this->form->getLabel('defaultPlayer'); ?>
			<?php echo $this->form->getInput('defaultPlayer'); ?></li>
			
            <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li>

                    
            </ul>
		</fieldset>
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