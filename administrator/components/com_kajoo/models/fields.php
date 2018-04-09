<?php
/**
 * @version      $Id: fields.php 66 2013-04-23 11:22:05Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Kajoo records.
 */
class KajooModelfields extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'type', 'a.type',
                'name', 'a.name',
                'alias', 'a.alias',
                'filtrable', 'a.filtrable',
                'sitevisible', 'a.sitevisible',
                'adminvisible', 'a.adminvisible',

            );
        }

        parent::__construct($config);
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_kajoo');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */

	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__kajoo_fields` AS a');


            // Join over the users for the checked out user.
            $query->select('uc.name AS editor');
            $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
            
		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


            // Filter by published state
            $published = $this->getState('filter.state');
            if (is_numeric($published)) {
                $query->where('a.state = '.(int) $published);
            } else if ($published === '') {
                $query->where('(a.state IN (0, 1))');
            }
            

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
                $query->where('( a.name LIKE '.$search.' )');
			}
		}
        


		//Filtering type
		$filter_type = JRequest::getVar("filter_type");
		if ($filter_type) {
			$query->where("a.type = '".$filter_type."'");
		}

		//Filtering filtrable
		$filter_filtrable = JRequest::getVar("filter_filtrable");
		if ($filter_filtrable) {
			$query->where("a.filtrable = '".$filter_filtrable."'");
		}

		//Filtering sitevisible
		$filter_sitevisible = JRequest::getVar("filter_sitevisible");
		if ($filter_sitevisible) {
			$query->where("a.sitevisible = '".$filter_sitevisible."'");
		}

		//Filtering adminvisible
		$filter_adminvisible = JRequest::getVar("filter_adminvisible");
		if ($filter_adminvisible) {
			$query->where("a.adminvisible = '".$filter_adminvisible."'");
		}        

		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
           $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
	public function getAllFields()
	{
		try
		{
			$db = JFactory::getDBO();
			$query  = 'SELECT * FROM `#__kajoo_fields`';    	
	    	$query .= ' WHERE state = 1';
	    	$query .= ' ORDER BY ordering';
			$db->setQuery($query);
			$fields = $db->loadObjectList();
		}
		catch(Exception $ex)
		{
			die("Error retrieving fields");
		}
		
		foreach ($fields as $key=>$field):
			$query  = 'SELECT * FROM `#__kajoo_field_values`';    	
	    	$query .= ' WHERE fieldid = '.$field->id;
	    	$query .= ' ORDER BY ordering';
			$db->setQuery($query);
			$fields[$key]->values = $db->loadObjectList();
		endforeach;
		
		return $fields;
	}
}
