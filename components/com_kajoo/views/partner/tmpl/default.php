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

<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

        
        
            <li><?php echo 'id'; ?>: 
            <?php echo $this->item->id; ?></li>

        
        
            <li><?php echo 'added'; ?>: 
            <?php echo $this->item->added; ?></li>

        
        
            <li><?php echo 'updated'; ?>: 
            <?php echo $this->item->updated; ?></li>

        
        
            <li><?php echo 'ordering'; ?>: 
            <?php echo $this->item->ordering; ?></li>

        
        
            <li><?php echo 'state'; ?>: 
            <?php echo $this->item->state; ?></li>

        
        
            <li><?php echo 'checked_out'; ?>: 
            <?php echo $this->item->checked_out; ?></li>

        
        
            <li><?php echo 'checked_out_time'; ?>: 
            <?php echo $this->item->checked_out_time; ?></li>

        
        
            <li><?php echo 'name'; ?>: 
            <?php echo $this->item->name; ?></li>

        
        
            <li><?php echo 'partnerid'; ?>: 
            <?php echo $this->item->partnerid; ?></li>

        
        
            <li><?php echo 'administratorsecret'; ?>: 
            <?php echo $this->item->administratorsecret; ?></li>

        
        
            <li><?php echo 'usersecret'; ?>: 
            <?php echo $this->item->usersecret; ?></li>

        

        </ul>
        
    </div>
    <?php if(JFactory::getUser()->authorise('core.edit', 'com_kajoo.partner'.$this->item->id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=partner.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>
<?php else: ?>
    Could not load the item
<?php endif; ?>
