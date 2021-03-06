<?php
/**
 * @version      $Id: fields.php 64 2013-04-23 11:14:15Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Fields list controller class.
 */
class KajooControllerFields extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'field', $prefix = 'KajooModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function delete()
	{
		$db =JFactory::getDBO();
		$cid = JRequest::getVar( 'cid', array(0), "", 'array' );

		if (is_array ( $cid ) && count ( $cid ) > 0) {

			foreach($cid as $c):
				//Delete custom values
			   $query = 'DELETE FROM `#__kajoo_field_values` WHERE fieldid = '.$c;
			   $db->setQuery($query);
			   $db->query();
			   
			   $query = 'DELETE FROM `#__kajoo_field_values_entry` WHERE field_id = '.$c;
			   $db->setQuery($query);
			   $db->query();
			   
			endforeach;
		}
		
		
		parent::delete();

	}

}