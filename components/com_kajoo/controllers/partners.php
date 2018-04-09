<?php
/**
 * @version      $Id: partners.php 66 2013-04-23 11:22:05Z freebandtech $
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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Partners list controller class.
 */
class KajooControllerPartners extends KajooController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Partners', $prefix = 'KajooModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}