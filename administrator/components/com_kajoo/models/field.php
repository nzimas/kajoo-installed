<?php
/**
 * @version      $Id: field.php 66 2013-04-23 11:22:05Z freebandtech $
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

jimport('joomla.application.component.modeladmin');

/**
 * Kajoo model.
 */
class KajooModelfield extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_KAJOO';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Field', $prefix = 'KajooTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_kajoo.field', 'field', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kajoo.edit.field.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function deleteValue($id)
	{
		$db = JFactory::getDbo();
		$query = "DELETE FROM `#__kajoo_field_values` WHERE id = ".(int)$id;
		$db->setQuery($query);
		$db->query();
	} 
	
	protected function getFieldValues($idValue)
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT * FROM `#__kajoo_field_values` WHERE fieldid = '.(int)$idValue.' ORDER BY ordering');
		$values = $db->loadObjectList();
		return $values;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			$item->values = $this->getFieldValues($item->id);
		}
		return $item;
	}

	function insertFieldValue($value,$fieldId,$order)
	{

		$db = JFactory::getDbo();
		$data =new stdClass();
		$data->fieldid = $fieldId;
		$data->value = $value;
		$data->ordering = $order;
		$db->insertObject( '#__kajoo_field_values', $data);
		echo $id = $db->insertid();
	}
	function updateFieldValue($value,$fieldId,$order,$idValue)
	{
		$db = JFactory::getDbo();
		$data =new stdClass();
		$data->id = $idValue;
		$data->fieldid = $fieldId;
		$data->value = $value;
		$data->ordering = $order;
		$db->updateObject( '#__kajoo_field_values', $data,'id');
	}
	
	
	public function getFieldContent($fieldId, $contentId)
	{
		$db = JFactory::getDbo();
		
		$returnValue = array();
		$returnValue['fieldId'] = $fieldId;
		
		
		$query  = 'SELECT type,name FROM `#__kajoo_fields` WHERE id = '.$fieldId;
		$db->setQuery($query);
		$fieldgen = $db->loadObject();
		
		$returnValue['fieldName'] = $fieldgen->name;
		
		$query  = 'SELECT value FROM `#__kajoo_field_values_entry` WHERE field_id = '.$fieldId.' AND content_id = '.$contentId;
		$db->setQuery($query);
		$value = $db->loadResult();
		
		if($fieldgen->type==2):
			if($value):
				$returnValue['value'] = $value;
				return $returnValue;
			else:
				$returnValue['value'] = '-';
				return $returnValue;
			endif;
		else:	
			if($value):		
				$query  = 'SELECT value FROM `#__kajoo_field_values` WHERE id = '.$value;
				$db->setQuery($query);
				$value = $db->loadResult();
				$returnValue['value'] = $value;
				return $returnValue;
			else:
				$returnValue['value'] = '-';
				return $returnValue;
			endif;

			
		endif;
	
	}
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__kajoo_fields');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

}