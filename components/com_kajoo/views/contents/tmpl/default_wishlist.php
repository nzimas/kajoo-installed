<?php
/**
 * @version      $Id: default_wishlist.php 66 2013-04-23 11:22:05Z freebandtech $
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
		$params = JComponentHelper::getParams( 'com_kajoo' );
		$emailManager = $params->get( 'emailManager','' );
?>

<div class="well well-small">
	<legend><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST');?> <span id="numslist" class="badge badge-info">0</span></legend>
	<ul class="" id="wishlist">
	
	</ul>
	<button class="btn btn-small" id="clearWishtlist"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_CLEAR');?></button>
	<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=requestinfo',false);?>" class="btn btn-info btn-small"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_REQINFO');?></a>
</div>


     
    <!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel"><?php echo JText::_('COM_KAJOO_TITLE_WISHLIST_REQINFO');?></h3>
    </div>
 </div>