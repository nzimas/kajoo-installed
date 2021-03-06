<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class KajooViewReportspdf extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		
    	$this->showThumb = JRequest::getVar('showThumb', 1, 'POST', 'INT');
    	$this->showTitle = JRequest::getVar('showTitle', 1, 'POST', 'INT');
    	$this->showId = JRequest::getVar('showId', 1, 'POST', 'INT');
    	$this->showPartner = JRequest::getVar('showPartner', 1, 'POST', 'INT');
    	$fieldFilters = JRequest::getVar('fieldFilters', null, 'POST', 'ARRAY');
    	$arrayTitles = JRequest::getVar('arrayTitles', null, 'POST', 'ARRAY');
	    	    	
    	
    	 	    	
  
    	$contentModel = JModelLegacy::getInstance('content', 'KajooModel');
    	$fieldModel = JModelLegacy::getInstance('field', 'KajooModel');
    	$partnerModel =JModelLegacy::getInstance('partner', 'KajooModel');
    	
    	$titles = array();
		foreach ($arrayTitles as $key=>$titleId):
			$titles[$key] = $contentModel->getItem($titleId);
			$titles[$key]->partner = KajooHelper::getPartnerInfo($titles[$key]->partner_id);
			foreach ($fieldFilters as $key2=>$fieldId):
				$titles[$key]->fields[$key2] = $fieldModel->getFieldContent($fieldId,$titleId);
			endforeach;
		endforeach;
		$this->titles = $titles;

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', false);

		$user		= JFactory::getUser();

		$canDo		= KajooHelper::getActions();

		JToolBarHelper::title(JText::_('COM_KAJOO_TITLE_CONFIG'), 'field.png');
		
		// If not checked out, can save the item.
		if (($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::preferences('com_kajoo');

			//JToolBarHelper::apply('field.apply', 'JTOOLBAR_APPLY');
			//JToolBarHelper::save('field.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id)) {
			//JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			//JToolBarHelper::cancel('field.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
