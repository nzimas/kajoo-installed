<?php
/**
 * @version      $Id: default.php 66 2013-04-23 11:22:05Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined('_JEXEC') or die;

?>

<?php $configuration = KajooHelper::getConfig();
	
	$positions = json_decode($configuration[0]->value);

	function getCols($positions)
	{

		$cols = 1;
		foreach ($positions as $key=>$position):
			
			if($position!='empty'):
				
				if($key!=1):
					$cols++;
				endif;
			endif;
		endforeach;

		return $cols;
	}


$numCols = getCols($positions);

if($numCols==1):
	$mainSpan = 12;
	$secSpan = 12;
	
elseif($numCols==2):
	$mainSpan = 8;
	$secSpan = 4;
	
else:
	$mainSpan = 6;
	$secSpan = 3;
	$secSpan = 3;
endif;
	
?>

<div class="colsKajoo row-flexible" id="topColsKajoo">

	<?php if($positions[0]!='empty'): ?>
		<div class="span<?php echo $secSpan;?>">
			<?php foreach ($positions[0] as $position):?>
				<?php echo $this->loadTemplate($position); ?>		
			<?php endforeach;?>
		</div>	
	<?php endif;?>
	
	
	
		<div class="span<?php echo $mainSpan?> ">
			<?php if($positions[1]!='empty'): ?>
				<?php foreach ($positions[1] as $position):?>
					<?php echo $this->loadTemplate($position); ?>		
				<?php endforeach;?>
			<?php endif;?>
			<?php echo $this->loadTemplate('maincontent'); ?>
		</div>	


	<?php if($positions[2]!='empty'): ?>
		<div class="span<?php echo $secSpan;?>">
			<?php foreach ($positions[2] as $position):?>
				<?php echo $this->loadTemplate($position); ?>		
			<?php endforeach;?>
		</div>	
	<?php endif;?>

</div>
<div class="clearfix"></div>
