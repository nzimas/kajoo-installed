<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */


// no direct access
defined('_JEXEC') or die;

?>

	<table class="table table-striped dataTable" id="tableClients">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_KAJOO_NOTES_NAME');?></th>
				<th><?php echo JText::_('COM_KAJOO_NOTES_COMPANY');?></th>
				<th><?php echo JText::_('COM_KAJOO_NOTES_POSITION');?></th>
				<th><?php echo JText::_('COM_KAJOO_NOTES_EMAIL');?></th>	
				<th><?php echo JText::_('COM_KAJOO_NOTES_PHONE');?></th>
				<th></th>
				<th></th>						
			</tr>
		</thead>
		<tbody>
			<?php 
			
			foreach ($this->clients as $key=>$client):?>
			<tr id="<?php echo $client->id;?>" class="rowClient">
				<td><?php echo $client->id;?></td>
				<td><strong><?php echo $client->name;?></strong></td>
				<td><?php echo $client->company;?></td>
				<td><?php echo $client->position;?></td>
				<td><?php echo $client->email;?></td>
				<td><?php echo $client->phone;?></td>
				<td><a href="#" class="btn btn-mini comments btn-info popoverObs" rel="popover" data-content="<?php echo $client->observations;?>" data-original-title="<?php echo JText::_('COM_KAJOO_NOTES_OBSERVATIONS');?>"><?php echo JText::_('COM_KAJOO_NOTES_OBSERVATIONS');?></a></td>
				<td><button id="<?php echo $client->id;?>" class="btn btn-danger btn-mini deleteClient"><?php echo JText::_('COM_KAJOO_NOTES_DELETECLIENT');?></button></td>
			</tr>
			<?php endforeach;?>
		</tbody>
		
	</table>
	
