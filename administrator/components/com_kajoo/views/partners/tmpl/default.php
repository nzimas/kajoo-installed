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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$app = JFactory::getApplication();

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_kajoo');
$saveOrder	= $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_kajoo&view=partners'); ?>" method="post" name="adminForm" id="adminForm">

<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<table class="table table-striped" id="adminList">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="left" style="width:140px;">
					
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_PARTNERS_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				
				<th class='left'>
				<?php echo JText::_('COM_KAJOO_STATUSPARTNER'); ?>
				</th>
				
				<th class='left'>
				<?php echo JText::_('COM_KAJOO_NUMBER_MEDIA'); ?>
				</th>
				
                <?php if (isset($this->items[0]->state)) { ?>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
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
			$canCreate	= $user->authorise('kajoo.createpartner','com_kajoo');
			$canEdit	= $user->authorise('kajoo.editpartner',	'com_kajoo');
			$canCheckin	= $user->authorise('kajoo.managepartner','com_kajoo');
			$canChange	= $user->authorise('core.edit.state','com_kajoo');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php  if(($item->url=='')):?>
							<span class="label">SaaS</span>
					<?php else: ?>
							<span class="label label-info">Self-hosted</span>
					<?php endif;?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'partners.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=partner.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
				</td>
				<td>
				<?php
				
				$kClient = KajooHelper::getKalturaClient($item->partnerid, $item->administratorsecret, true,$item->url);
				try
				{
					$results = $kClient->media->listAction();
					echo '<span class="label label-success">'.JText::_('COM_KAJOO_PARTNER_CONNECTED').'</span>';
				}
				catch(Exception $ex)
				{
					echo '<span class="label label-important">'.JText::_('COM_KAJOO_PARTNER_NOTCONNECTED').'</span>';
				}
				?>		
				</td>
				<td>
					<?php
					try
					{
						$results = $kClient->media->listAction();

						echo '<span class="badge badge-info">'.$results->totalCount.'</span>';
					}
					catch(Exception $ex)
					{
						echo '-';
					}
					?>
				</td>

                <?php if (isset($this->items[0]->state)) { ?>
				    <td class="center">
					    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'partners.', $canChange, 'cb'); ?>
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

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</form>