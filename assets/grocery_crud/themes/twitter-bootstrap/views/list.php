<?php
$count_columns = count($columns);
$colspan = $count_columns+1;
?>
<table class="table table-bordered tablesorter table-striped">
    <thead>
        <tr>
            <?php foreach($columns as $column){?>
            <th>
                <div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>"
                    rel="<?php echo $column->field_name?>">
                    <?php echo $column->display_as; ?>
                </div>
            </th>
            <?php }?>
            <?php if(!$unset_delete || !$unset_edit || !empty($actions)){?>
            <th class="no-sorter">
                    <?php echo $this->l('list_actions'); ?>
            </th>
            <?php }?>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($list)){ ?>
        <?php foreach($list as $num_row => $row){ ?>
        <tr class="<?php echo ($num_row % 2 == 1) ? 'erow' : ''; ?>">
            <?php foreach($columns as $column){?>
                <td class="<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>">
                    <div class="text-left"><?php echo ($row->{$column->field_name} != '') ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
                </td>
            <?php }?>
            <?php if(!$unset_delete || !$unset_edit || !empty($actions)){?>
                <td align="center" class="text-center" style="text-align:center;">
                
                    <?php 
                if(!empty($row->action_urls)){
                    foreach($row->action_urls as $action_unique_id => $action_url){ 
                        $action = $actions[$action_unique_id];
                ?>
                        <a href="<?php echo $action_url; ?>" class="" title="<?php echo $action->label?>"><?php 
                            if(!empty($action->image_url))
                            {
                                $title = strip_tags($action->image_url);
                                
                                ?>
                                <!--<img src="<?php echo $action->image_url; ?>" alt="<?php echo $action->label?>" />-->
                                <span class="label-action label-custom callout"><?php echo $action->image_url; ?> <?php echo $action->label?></span>
                                
                                <?php 	
                            }
                        ?></a>		
                <?php }
                }
                ?>
                
                    <?php  if(!$unset_edit): ?>
                        <a href="<?php echo $row->edit_url?>" class="table-action">
                            <span class="label-action label-edit callout"><i class="glyphicon glyphicon-ok"></i> EDIT</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php  if(!$unset_read): ?>
                        <a href="<?php echo $row->read_url?>" class="table-action">
                            <span class="label-action label-view callout"><i class="glyphicon glyphicon-zoom-in"></i> VIEW</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php  if(!$unset_delete): ?>
                        <a href="<?php echo $row->delete_url?>" class="delete-row table-action" data-target-url="<?php echo $row->delete_url?>">
                            <span class="label-action label-delete callout"><i class="glyphicon glyphicon-remove"></i> DELETE</span>
                        </a>
                    <?php endif; ?>
                    
                    
                </td>
            <?php }?>
            </tr>
            <?php } ?>
            <?php }else{ ?>
            <tr>
            	<td colspan="<?=$colspan;?>" align="center">
                <em><?php echo $this->l('list_no_items'); ?></em>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>

