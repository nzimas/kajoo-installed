<?php
/**
 * @version      $Id: default_filters.php 66 2013-04-23 11:22:05Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die;

 if(count($this->allFields)>0):

	
?>

	<div class="well well-small" id="fieldsKajoo">
		<legend><?php echo JText::_('COM_KAJOO_CONTENTS_FILTERS');?></legend>
		
		<?php foreach ($this->allFields as $field):	?>
			
			<?php if($field->type==1 && count($field->values)>0):?>
				<h4><?php echo $field->name;?></h4>
				<select name="<?php echo $field->alias;?>" id="<?php echo $field->alias;?>" class="fieldfilter kajooInput">
						<option value="all"><?php echo JText::_('COM_KAJOO_CONTENTS_FILTERS_ALL');?></option>
					<?php foreach ($field->values as $value):?>
						<option value="<?php echo $value->id;?>"><?php echo $value->value;?></option>
					<?php endforeach;?>
				</select>
			<?php endif;?>
		<?php endforeach;?>
	</div>

<?php endif;?>