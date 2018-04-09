<?php
/**
 * @version      $Id: default.php 66 2013-04-23 11:22:05Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/requestinfo.js');
		$params = JComponentHelper::getParams( 'com_kajoo' );
		$emailManager = $params->get( 'emailManager','' );
?>

<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents'); ?>" class="btn btn-small"><i class="icon-chevron-left"></i> <?php echo JText::_('COM_KAJOO_ITEMCONTENT_PREVPAGE');?></a>

<div class="infoDetail_container">

<h2><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_REQINFO');?></h2>

    <?php if($emailManager==''):?>
    	<?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_CONFIGUREMAIL');?>
    <?php else: ?>
    	<ul class="" id="wishlist2"></ul>
    	<hr>
 <form class="form-horizontal" id="reqinfo">
		    	<div class="control-group">
			    	<label class="control-label" for="name"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_NAME');?></label>
				    <div class="controls">
				    	<input type="text" id="name" name="name" placeholder="Your name">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="company"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_COMPANY');?></label>
				    <div class="controls">
				    	<input type="text" id="company" name="company" placeholder="Company">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="position"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_POSITION');?></label>
				    <div class="controls">
				    	<input type="text" id="position" name="position" placeholder="Position">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="email"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_EMAIL');?></label>
				    <div class="controls">
				    	<input type="text" id="email" name="email" placeholder="Email">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="phone"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_PHONE');?></label>
				    <div class="controls">
				    	<input type="text" id="phone" name="phone" placeholder="Phone">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="mobile"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_MOBILE');?></label>
				    <div class="controls">
				    	<input type="text" id="mobile" name="mobile" placeholder="Mobile">
				    </div>
			    </div>
			    
			    <div class="control-group">
			    	<label class="control-label" for="comments"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_COMMENTS');?></label>
				    <div class="controls">
				    	<textarea rows="3" name="comments" id="comments"></textarea>
				    </div>
			    </div>
			    
			    
			   	<button type="submit" id="submitForm" class="btn btn-success"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_SUBMITFORM');?></button>
			
	    </form>
	    
	    <?php endif;?>
</div>