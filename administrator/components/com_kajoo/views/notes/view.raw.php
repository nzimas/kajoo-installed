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
 * View class for a list of Kajoo.
 */
class KajooViewNotes extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		
		$this->clients		= $this->get('Clients');
		
		$clientId = JRequest::getInt('clientId', 0, 'GET');
		if($clientId!=0):
			$this->clientData	= $this->get('ClientData');
		endif;
		
		
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
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'kajoo.php';

		$state	= $this->get('State');
		$canDo	= KajooHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_KAJOO_TITLE_NOTES'), 'notes.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'note';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			   // JToolBarHelper::addNew('note.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit')) {
			   // JToolBarHelper::editList('note.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			   // JToolBarHelper::divider();
			   // JToolBarHelper::custom('notes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			   // JToolBarHelper::custom('notes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else {
                //If this component does not use state then show a direct delete button as we can not trash
               // JToolBarHelper::deleteList('', 'notes.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			   // JToolBarHelper::divider();
			   // JToolBarHelper::archiveList('notes.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	// JToolBarHelper::custom('notes.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			   // JToolBarHelper::deleteList('', 'notes.delete','JTOOLBAR_EMPTY_TRASH');
			   // JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			   // JToolBarHelper::trash('notes.trash','JTOOLBAR_TRASH');
			   // JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_kajoo');
		}


	}
}
