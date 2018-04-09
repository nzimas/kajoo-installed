<?php
/**
 * @version      $Id: default_partners.php 66 2013-04-23 11:22:05Z freebandtech $
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
	$partners =  KajooHelper::getPartnersList(); ?>
	<?php if(count($partners)>0):?>
		
		<div class="well well-small">
			<legend><?php echo JText::_('COM_KAJOO_CONTENTS_PARTNERS');?></legend>
			
			
		<select id="partner" name="partner" class="kajooInput">
			<option value="all"><?php echo JText::_('COM_KAJOO_CONTENTS_PARTNERS_ALL');?></option>
		<?php foreach($partners as $partner):?>
			<option value="<?php echo $partner->id;?>"><?php echo $partner->name;?></option>
		<?php endforeach;?>
		</select>
			
		</div>

<?php endif;