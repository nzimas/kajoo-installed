<?php
/**
 * @version      $Id: default_searchbox.php 66 2013-04-23 11:22:05Z freebandtech $
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
?>

<div class="well well-small">
	<legend><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?></legend>
	<input class="kajooInput" id="tableSearchButton" type="text" placeholder="<?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?>..." data-provide="typeahead" data-items="4">
	<br>
	
	<button id="searchBtn" class="btn btn-success" type="button"><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCH');?></button>
	<button id="searchBtnclear" class="btn" type="button"><?php echo JText::_('COM_KAJOO_CONTENTS_SEARCHCLEAR');?></button>
</div>