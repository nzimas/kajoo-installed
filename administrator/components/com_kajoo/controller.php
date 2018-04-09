<?php
/**
 * @version      $Id: controller.php 66 2013-04-23 11:22:05Z freebandtech $
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

class KajooController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JControllerLegacy		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		

		// Load the submenu.
		KajooHelper::addSubmenu(JRequest::getCmd('view', 'partners'));

		$view		= JRequest::getCmd('view', 'partners');
        JRequest::setVar('view', $view);

		parent::display();

		return $this;
	}
}
