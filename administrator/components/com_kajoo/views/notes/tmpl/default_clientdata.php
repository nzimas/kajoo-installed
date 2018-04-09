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

<?php if($this->clientData):?>

<div class="alert alert-info"><?php echo JText::_('COM_KAJOO_NOTES_INFOCLDATA');?></div>

<table class="table table-striped">
	
	<?php foreach ($this->clientData as $cd): ?>
		<tr>
			
			<td><img src="<?php echo $cd->thumburl;?>" style="height:20px; width:20px;"/></td>

			<td><?php echo $cd->name;?> <i style="font-size:85%; color:#999;"><?php echo $cd->entry_id;?></i></td>
			
			<td>
			<?php if($cd->soldState==0):
					$statusClass = 'notsold';
				elseif($cd->soldState==1):
					$statusClass = 'pending';
				else:
					$statusClass = 'sold';
				endif;
				
			?>
			<span class="iconState <?php echo $statusClass;?>" id="<?php echo $cd->soldId;?>" title="<?php echo $cd->soldState;?>"></span></td>
			<td><button class="btn btn-mini btn-danger deleteContent" id="<?php echo $cd->id;?>"><?php echo JText::_('COM_KAJOO_POSFRONTEND_DELETE');?></button>
			
		</tr>
	<?php endforeach;?>
	
</table>
<ul class="tableLEgendSold">
	<li><span class="notsold legSold"></span> <?php echo JText::_('COM_KAJOO_POSFRONTEND_STATUS_NOTSOLD');?></li>
	<li><span class="pending legSold"></span> <?php echo JText::_('COM_KAJOO_POSFRONTEND_STATUS_PENDING');?></li>
	<li><span class="sold legSold"></span> <?php echo JText::_('COM_KAJOO_POSFRONTEND_STATUS_SOLD');?></li>
		
</ul>

<?php else:?>

<div class="alert"><?php echo JText::_('COM_KAJOO_NOTES_NOCLIENTDATA');?></div>
<?php endif;?>