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
class KajooViewUpload extends JViewLegacy
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

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		$partnersModel = JModelLegacy::getInstance('partners', 'KajooModel'); 
		$this->partners = $partnersModel->getAllOkPartners();

		
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



if(!$canDo->get('kajoo.uploadtopartner')):
JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_kajoo&view=contents',false), JText::_('COM_KAJOO_PERMISSION_UPLOAD'), 'error' );
endif;
		
		JToolBarHelper::title(JText::_('COM_KAJOO_TITLE_UPLOAD'), 'field.png');

		// If not checked out, can save the item.
		if (($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

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
