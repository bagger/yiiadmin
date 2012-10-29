<?php
    $this->pageTitle=$title;
?>

    <ul class="tools"> 
	<?php if($model->actionAvailable('create')) { ?>
    <li> 
       <?php echo CHtml::link(YiiadminModule::t( 'Создать').' '.$this->module->getObjectPluralName($model, 0),$this->createUrl('manageModel/create',array('model_name'=>get_class($model))), array('class'=>'add-handler focus')); ?>

    </li> 
	<?php } ?>
    </ul> 

<?php  
    $this->widget('zii.widgets.grid.CGridView',$listData);
?>
