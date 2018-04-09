<?php
/**
 * @version      $Id: contents.php 66 2013-04-23 11:22:05Z freebandtech $
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
class KajooModelcontents extends JModelList
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
                'name', 'a.name',
                'state', 'a.state',
                'added', 'a.added',
                'updated', 'a.updated',
                'entry_id', 'a.entry_id',
                'partner_id', 'a.partner_id',
                'name', 'a.name',
                'description', 'a.description',

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
		

		// Load the custom fields state.
		
		$partner = $app->getUserStateFromRequest($this->context.'.filter.partner', 'filter_partner', '', 'string');
		$this->setState('filter.partner', $partner);
		
		$category = $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string');
		$this->setState('filter.category', $category);
		
		$custom_filters = $this->getAdminFiltrableFields();
		foreach ($custom_filters as $key=>$filter):
			$custom[$key] = $app->getUserStateFromRequest($this->context.'.filter.'.$filter->alias, 'filter_'.$filter->alias);
			$this->setState('filter.'.$filter->alias, $custom[$key]);			
		endforeach;

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_kajoo');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.entry_id', 'asc');
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
	 
	public function filterKaltura($partnerId,$search)
	// Returns all the fields filtered iin the API
	{
	
		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = KalturaPlayableEntryOrderBy::CREATED_AT_DESC;
		
		$search = trim($search);
		
		$filter->freeText = $search;
		if($this->getState('filter.category')):
		$filter->categoriesIdsMatchOr = $this->getState('filter.category');
		endif;	
		
		// Connect to Kaltura API

		$PartnerInfo = KajooHelper::getPartnerInfo($partnerId);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		try
		{
				$params = JComponentHelper::getParams( 'com_kajoo' );
				$maxresultsquery = $params->get( 'maxresultsquery',100 );
				$pager = new KalturaFilterPager();
				$pager->pageSize = $maxresultsquery;
				$results = $kClient->media->listAction($filter,$pager);
		
			
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Error retrieving content in API'));
		}
		
		$filter_search = array();
		foreach ($results->objects as $object):
			$filter_search[] = $object->id;
		endforeach;
		
		$results->filter_search = $filter_search;
		$results->filter_search_text = "'". implode("', '", $results->filter_search) ."'";
		
		return $results;
		
	}
	
	public function filterCustomFields()
	{

		$filter_custom_fields = array();
		$allFields = $this->getAdminFiltrableFields();
		foreach($allFields as $key=>$field):
			$aliasPost = $this->getState('filter.'.$field->alias);
			
			if($aliasPost!=''):
				$filter_custom_fields[$key]['id'] = $field->id;
				$filter_custom_fields[$key]['alias'] = $field->alias;
				$filter_custom_fields[$key]['value'] = $aliasPost;
			endif;

		endforeach;
		
		return $filter_custom_fields;

	}
	public function getAdminFiltrableFields() {
		$db		= $this->getDbo();
        $query = 'select * from #__kajoo_fields';
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
   public function dataCustFilter()
    {
   	 //Get in an array all the kentry ids that must be filtered
    	$db		= $this->getDbo();
        $filters = $this->filterCustomFields();
        $filtered = array();
        if(count($filters)>0):
	        $query = 'SELECT distinct content_id FROM `#__kajoo_field_values_entry`';
	        $query .= ' WHERE';
	
	        $i = 0;
	        foreach ($filters as $filter) {
	            $filter_val = $filter['value'];
	            if ($filter_val and ($filter_val != '')):
	                if($i==0):
	                    $query .= ' content_id IN (SELECT content_id FROM `#__kajoo_field_values_entry` WHERE value = "'.$filter_val.'")';
	                else:
	                    $query .= ' AND content_id IN (SELECT content_id FROM `#__kajoo_field_values_entry` WHERE value = "'.$filter_val.'")';
	                endif;
	            $i++;
	            endif;
	            
	        }
	     
	        $db->setQuery($query);
	        $filtered['results'] = $db->loadColumn();
	        $filtered['filters'] = 1;
	        return $filtered;
	      else:
	      $filtered['filters'] = 0;  
	      return $filtered;
	      endif; 
    }
    function getFilteredQuery()
    {
    	$filterQuery = array();
    	$filtdata = $this->dataCustFilter();
	
    	if($filtdata['filters']):
	    	//Build the query for custom filters
	        if(count($filtdata['results'])>0):
	            $i = 0;
	            foreach ($filtdata['results'] as $d):
	                $filtdata['results'][$i] = '"'.$d.'"';
	            $i++;    
	            endforeach;
	
	            $ids = join(',',$filtdata['results']); 
	            $filterQuery['query'] = '('.$ids.')';
	          
	            $filterQuery['type'] = 'filter';
	
	        else:
	        	$filterQuery['query'] = 'noquery';
	            $filterQuery['type'] = 'noresult';
	        endif;    
	    else:
	    	$filterQuery['query'] = 'noquery';
	    	$filterQuery['type'] = 'nofilter';
	    endif;   

        return $filterQuery;
        

    }
    function delete()
    {

	    
	    $db = JFactory::getDBO();
	    $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
	    
	    foreach ($cid as $c):
	    	$query  = 'SELECT * FROM `#__kajoo_content` WHERE id = '.(int)$c;
			$db->setQuery($query);
			$dbContent = $db->loadObject();
			$PartnerInfo = KajooHelper::getPartnerInfo($dbContent->partner_id);
			$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true);
			
			try
			{
				$results = $kClient->media->delete($dbContent->entry_id);
			}
			catch(Exception $ex)
			{
				echo "cannot delete in kaltura";
			}
			
			
	    endforeach;
	

	  
	    JArrayHelper::toInteger($cid);
	    if (count( $cid )) {
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__kajoo_content'
			. ' WHERE id IN ( '. $cids .' )';
			
			$db->setQuery( $query );
			if (!$db->query()) {
				echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
			}
			
			$query = 'DELETE FROM #__kajoo_field_values_entry'
			. ' WHERE content_id IN ( '. $cids .' )';
			
			$db->setQuery( $query );
			if (!$db->query()) {
				echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
			}
			
			
		}

	}
	protected function getListQuery()
	{
		
		//0.
		$db	= $this->getDbo();
        $query2 = 'select min(id) from #__kajoo_partners WHERE state = 1';
        $db->setQuery($query2);
        $minid = $db->loadResult();
        $partnerId = $this->getState('filter.partner');
        if(!$partnerId)
        	$partnerId = $minid;
		//If auto sync content
		//$PartnerInfo = KajooHelper::syncPartner($partnerId);


		// 1. Filter in Kaltura
		
		$filter_search = JRequest::getString('filter_search','','POST','STRING');
		if(isset($filter_search)!=''):
			$filtered_results = $this->filterKaltura($partnerId,$filter_search);
		endif;
		
		
		
		//2. Filter fields in database
		$custom_fields = $this->getFilteredQuery();	
		
		
		
		
		//3. Do the search
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*,fv.value as field_value, fv.field_id as field_id'
			)
		);
		$query->from('`#__kajoo_content` AS a');


            // Join over the users for the checked out user.
            $query->select('uc.name AS editor');
            $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
            $query->join('LEFT', '#__kajoo_field_values_entry AS fv ON fv.content_id=a.id');
            


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
                $query->where('( a.entry_id LIKE '.$search.' )');
			}
		}
        
        $query->where('( a.partner_id = '.$partnerId.' )');
        $query->where('(a.entry_id IN ('.$filtered_results->filter_search_text.'))');

        if($custom_fields['type']=='filter'):

	        $query->where('( a.id IN '.$custom_fields['query'].' )');
	    elseif($custom_fields['type']=='noresult'):
        	$query->where('a.id = 0');

        else: //nofilter
        
        endif;
        $query->group('a.id');
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
	public function getAllContents()
	{

		$db	= $this->getDbo();
		$query  = 'SELECT * FROM `#__kajoo_content`';
		$db->setQuery($query);
		$rel = $db->loadObjectList();
		return $rel;
	}
}
