<?php
/**
 * @version      $Id: view.raw.php 66 2013-04-23 11:22:05Z freebandtech $
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

jimport('joomla.application.component.view');

/**
 * View class for a list of Kajoo.
 */
class KajooViewTable extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = JFactory::getApplication();
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_kajoo');


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		
		$fieldsModel = JModelLegacy::getInstance('fields', 'KajooModel'); 

		$this->allFields = $fieldsModel->getAllFields();
		$this->postData = $_POST;

        
		parent::display($tpl);
	}


	    	
}
