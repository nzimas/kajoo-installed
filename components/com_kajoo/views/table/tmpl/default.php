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

    $app = JFactory::getApplication('site');
    $componentParams = $app->getParams('com_kajoo');
    $link_titles= $componentParams->get('link_titles', 1); 
    $link_images= $componentParams->get('link_images', 1); 
    $show_fields= $componentParams->get('show_fields', 1); 
    $show_partner= $componentParams->get('show_partner', 1); 
    $maxsizedesc= $componentParams->get('maxsizedesc', 35); 
    $show_duration= $componentParams->get('show_duration', 1); 
    $scroll_on_reload= $componentParams->get('scroll_on_reload', 1); 


    
?>
<div class="kajooMainContent">
<?php if($this->items) :
?>


			<?php foreach ($this->items as $item) :?>
			<div class="row-fluid itemKajooMainContent">
			<?php $partner = KajooHelper::getPartnerInfo($item->partner_id);?>
			
			<?php
			if($partner->url==''):
				$thumbMainUrl = 'http://cdn.kaltura.com';
			else:
				$thumbMainUrl = $partner->url;
			endif;
			?>
			
				
					<div class="span3">
						<?php if($link_images):?>
						<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=content&id=' . (int)$item->id); ?>">
<img src="<?php echo $thumbMainUrl;?>/p/<?php echo $partner->partnerid;?>/sp/0/thumbnail/entry_id/<?php echo $item->entry_id;?>/width/520/height/290" onmouseover="KalturaThumbRotator.start(this)" onmouseout="KalturaThumbRotator.end(this)" class="img-polaroid">
						</a>
						<?php else: ?>
<img src="<?php echo $thumbMainUrl;?>/p/<?php echo $partner->partnerid;?>/sp/0/thumbnail/entry_id/<?php echo $item->entry_id;?>/width/520/height/290"  onmouseover="KalturaThumbRotator.start(this)" onmouseout="KalturaThumbRotator.end(this)">
						<?php endif;?>




						
						<div class="iconsKajoo" id="<?php echo $item->entry_id;?>">
							<a href="#" id="kajooID<?php echo $item->entry_id;?>" title="<?php echo $item->name;?>" class="ui-icon ui-icon-heart kajooAddWishlist"></a>
							<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=content&id=' . (int)$item->id); ?>" id="<?php echo $item->name;?>" class="ui-icon ui-icon-video"></a>
						</div>
						
					</div>
					<div class="span5">
						<div class="mainCentKajoo">
							<div class="entryTitle">
								<?php if($link_titles):?>
								<h3><a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=content&id=' . (int)$item->id); ?>">
									<?php echo $item->name;?>
								</a>
								</h3>	
								<?php else:?>
									<h3><?php echo $item->name;?></h3>
								<?php endif;?>
							</div>
							<div class="entryDesc"><?php echo KajooHelper::limit_words($item->description,$maxsizedesc);?></div>
						</div>
					</div>
					<?php if($show_fields):?>
						<div class="span4">
							<div class="fieldsWell">
								<ul class="nav nav-list">
								<?php if($show_partner):?>
									
									<li><span class="entryFieldPartner"><?php echo $partner->name;?></span></li>
								<?php endif; ?>
								
								<?php if($show_duration):?>
									<?php $formated_duration = KajooHelper::formatTime($item->duration);?>
									<li><span class="entryFieldName"><?php echo JText::_('COM_KAJOO_CONTENTS_DURATION');?>:</span> <?php echo $formated_duration;?></li>
								<?php endif; ?>
								
								<?php foreach($this->allFields as $field): ?>
									<?php $value = KajooHelper::getContentFieldValue($item->id,$field->id,$field->type);?>
									<?php if($value): ?>
										<li><span class="entryFieldName"><?php echo $field->name;?>:</span> <?php echo $value;?></li>
									<?php endif;?>
								<?php endforeach;?>
								</ul>
							</div>
						</div>
					<?php endif;?>
			</div>
			<hr>
			<?php endforeach;?>	
		
	<div class="pagination pagination-large pagination-centered">

	    <ul>
	    	<li class="<?php if($this->pagination->pagesStart==$this->pagination->pagesCurrent) echo "disabled";?>"><a href="#" id="firstPag"><i class="icon-first"></i></a></li>
	    	<li class="<?php if($this->pagination->pagesStart==$this->pagination->pagesCurrent) echo "disabled";?>"><a href="#" id="prevPag"><i class="icon-previous"></i></a></li>
		    <?php for($i=1;$i<=$this->pagination->pagesTotal;$i++):?>
		    <?php
		    		$statusPagination = '';
		    	if($this->pagination->pagesCurrent==$i):
		    		$statusPagination = 'active';
		    	endif;
		    ?>
		    
		    	<li class="<?php echo $statusPagination;?>"><a href="#" id="<?php echo $i;?>" class="pageChange"><?php echo $i;?></a></li>
		    <?php endfor;?>
		    <li class="<?php if($this->pagination->pagesTotal==$this->pagination->pagesCurrent) echo "disabled";?>"><a href="#" id="nextPag"><i class="icon-next"></i></a></li>
		    <li class="<?php if($this->pagination->pagesTotal==$this->pagination->pagesCurrent) echo "disabled";?>"><a href="#" id="lastPag"><i class="icon-last"></i></a></li>
	    </ul>
	    
	    	<div class="showingPaginationtext">
				<strong><?php echo JText::_('COM_KAJOO_CONTENTS_TOTALRECORDS');?> <?php echo $this->pagination->total;?></strong>
				<br />
				<?php echo JText::sprintf('COM_KAJOO_CONTENTS_PAGEXOF', $this->pagination->pagesCurrent,$this->pagination->pagesStop); ?>
			</div>
			
			 <div class="clearfix"></div>
	    <div class="shownumPagination">
	    	<select name="limit" id="limitKajoo" class="input-mini kajooInput">
		    	<option value="5" <?php if($this->pagination->limit==5) echo 'selected="selected"';?>>5</option>
		    	<option value="10" <?php if($this->pagination->limit==10) echo 'selected="selected"';?>>10</option>
		    	<option value="20" <?php if($this->pagination->limit==20) echo 'selected="selected"';?>>20</option>
		    	<option value="50" <?php if($this->pagination->limit==50) echo 'selected="selected"';?>>50</option>	
		    	<option value="100" <?php if($this->pagination->limit==100) echo 'selected="selected"';?>>100</option>	    		    		    	
	    	</select>
	    	<input type="hidden" id="totalPages" value="<?php echo $this->pagination->pagesTotal;?>">
	    	<input type="hidden" name="scroll_on_reload" id="scroll_on_reload" value="<?php echo $scroll_on_reload;?>">

	    	 <div class="clearfix"></div>
    	</div>
	   
    </div>
    

 <div class="clearfix"></div>

<?php else: ?>
	<div class="alert alert-info">
    	<?php echo JText::_('COM_KAJOO_CONTENTS_NOITEMS');?>
	</div>

<?php endif; ?>
</div>