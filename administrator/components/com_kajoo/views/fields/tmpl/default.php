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

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_kajoo');
$saveOrder	= $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_kajoo&view=fields'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>







	<div id="filter-bar" class="btn-toolbar">

        
		<div class="btn-group pull-right">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true);?>
			</select>
		</div>





		</div>
	<div class="clearfix"> </div>

	<table class="table table-striped" id="adminList">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>

				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_FIELDS_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>

				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_FIELDS_TYPE', 'a.type', $listDirn, $listOrder); ?>
				</th>				
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_FIELDS_FILTRABLE', 'a.filtrable', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_FIELDS_SITEVISIBLE', 'a.sitevisible', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_FIELDS_ADMINVISIBLE', 'a.adminvisible', $listDirn, $listOrder); ?>
				</th>


                <?php if (isset($this->items[0]->state)) { ?>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->ordering)) { ?>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'fields.saveorder'); ?>
					<?php endif; ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :

			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_kajoo');
			$canEdit	= $user->authorise('core.edit',			'com_kajoo');
			$canCheckin	= $user->authorise('core.manage',		'com_kajoo');
			$canChange	= $user->authorise('core.edit.state',	'com_kajoo');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

	
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'fields.', $canCheckin); ?> 
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=field.edit&id='.(int) $item->id); ?>">
					<strong><?php echo $this->escape($item->name); ?></strong></a> <span class="muted">[<?php echo $this->escape($item->alias); ?>]</span>
				<?php else : ?>
					<strong><?php echo $this->escape($item->name); ?></strong> [<?php echo $this->escape($item->alias); ?>]
				<?php endif; ?>
				</td>
				
							<td>
					<?php echo ($item->type == 1)?'<span class="label label-info">'.JText::_('COM_KAJOO_SELECTLIST').'</span>':'<span class="label">'.JText::_('COM_KAJOO_TEXTFIELD').'</span>'; ?>
				</td>
				<td>
					<?php echo ($item->filtrable == 1)?'<span class="label label-success">'.JText::_('COM_KAJOO_YES').'</span>':'<span class="label label-warning">'.JText::_('COM_KAJOO_NO').'</span>'; ?>
				</td>
				<td>
					<?php echo ($item->sitevisible == 1)?'<span class="label label-success">'.JText::_('COM_KAJOO_YES').'</span>':'<span class="label label-warning">'.JText::_('COM_KAJOO_NO').'</span>'; ?>
				</td>
				<td>
					<?php echo ($item->adminvisible == 1)?'<span class="label label-success">'.JText::_('COM_KAJOO_YES').'</span>':'<span class="label label-warning">'.JText::_('COM_KAJOO_NO').'</span>'; ?>
				</td>


                <?php if (isset($this->items[0]->state)) { ?>
				    <td class="center">
					    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'fields.', $canChange, 'cb'); ?>
				    </td>
                <?php } ?>
                <?php if (isset($this->items[0]->ordering)) { ?>
				    <td class="order">
					    <?php if ($canChange) : ?>
						    <?php if ($saveOrder) :?>
							    <?php if ($listDirn == 'asc') : ?>
								    <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							    <?php elseif ($listDirn == 'desc') : ?>
								    <span><?php echo $this->pagination->orderUpIcon($i, true, 'fields.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							    <?php endif; ?>
						    <?php endif; ?>
						    <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
					    <?php else : ?>
						    <?php echo $item->ordering; ?>
					    <?php endif; ?>
				    </td>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
                <?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>


	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>	
</form>