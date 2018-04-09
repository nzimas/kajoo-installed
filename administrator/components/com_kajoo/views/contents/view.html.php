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
class KajooViewContents extends JViewLegacy
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

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		

		
		$partnerModel = JModelLegacy::getInstance('partners', 'KajooModel'); 
		$this->allPartners = $partnerModel->getAllPartners();
		
		$fieldsModel = JModelLegacy::getInstance('fields', 'KajooModel'); 
		$this->allFields = $fieldsModel->getAllFields();
		
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

		JToolBarHelper::title(JText::_('COM_KAJOO_TITLE_CONTENTS'), 'contents.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'content';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('content.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit')) {
			    JToolBarHelper::editList('content.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('contents.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('contents.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else {
                //If this component does not use state then show a direct delete button as we can not trash
//                JToolBarHelper::deleteList('', 'contents.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('contents.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    JToolBarHelper::divider();
		    if ($canDo->get('core.delete')) {
		   //	 JToolBarHelper::deleteList('', 'contents.delete','JTOOLBAR_DELETE');
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_kajoo');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_kajoo&view=contents');

		
		
		$options	= array();
		foreach ($this->allPartners as $key=>$partner):
			$options[]	= JHtml::_('select.option', $partner->id, $partner->name);
		endforeach;
		
		JHtmlSidebar::addFilter(
			JText::_('COM_KAJOO_TITLE_PARTNERS'),
			'filter_partner',
			JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.partner'))
		);
		
		
	$options = array();	
	$categories = KajooHelper::getCategoryListRaw($this->state->get('filter.partner'));

	foreach ($categories->objects as $key=>$category):
		$options[]	= JHtml::_('select.option', $category->id, $category->fullName);
	endforeach;
	    
	 JHtmlSidebar::addFilter(
			JText::_('COM_KAJOO_TITLE_CATEGORY'),
			'filter_category',
			JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.category'))
		);   
	    
	    
		
		$fieldsModel = JModelLegacy::getInstance('fields', 'KajooModel'); 
		$allFields = $fieldsModel->getAllFields();

		
		foreach ($this->allFields as $key=>$field):
			if($field->type==1):
			
			// Levels filter.
				$options	= array();				
				foreach ($field->values as $value):
					$options[]	= JHtml::_('select.option', $value->id, $value->value);
				endforeach;
	
				JHtmlSidebar::addFilter(
					$field->name,
					'filter_'.$field->alias,
					JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.'.$field->alias))
				);
			endif;	
		endforeach;
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

	}
	


}
