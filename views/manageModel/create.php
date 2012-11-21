<?php
    $this->pageTitle=$title;

    $cs=Yii::app()->getClientScript();
    if($this->module->wysiwygType == 'redactor') { 
        $cs->registerCssFile($this->module->assetsUrl.'/redactor/redactor/css/redactor.css');
        $cs->registerScript('redactor-jq', '
                var glob_imageUpload = "'.CHtml::normalizeUrl(array('manageModel/upload','type'=>'image')).'";
                ',CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->module->assetsUrl.'/redactor/redactor/redactor.js');
        $cs->registerScriptFile($this->module->assetsUrl.'/redactor_setup/redactor_setup.js');
    } else {
	    $cs->registerScriptFile($this->module->assetsUrl.'/tinymce/jscripts/tiny_mce/tiny_mce.js');
	    $cs->registerScriptFile($this->module->assetsUrl.'/tinymce/jscripts/tiny_mce/jquery.tinymce.js'); 
	    $cs->registerScriptFile($this->module->assetsUrl.'/tinymce_setup/tinymce_setup.js');
    }


    foreach ($model->rules() as $rule)
    {
        // Атрибуты поиска нас не интерисуют.
        if ($rule['on']!='search')
            $attr_string.=$rule[0].',';
    }
    
    // TODO: unset primaryKey;
    $attributes=array_filter(array_unique(array_map('trim',explode(',',$attr_string))));
?>

<?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>get_class($model).'-id-form',
    ));
?>

<div class="container-flexible">
        <?php if($model->hasErrors()): ?>
            <p class="errornote"><?php echo YiiadminModule::t('Пожалуйста, исправьте ошибки, указанные ниже.'); ?></p>
        <?php endif; ?>
        <div class="form-container">
        <div>
        <fieldset class="module wide">
        <?php  
            foreach ($attributes as $attribute): 
                if( $model->tableSchema->columns[$attribute]->isPrimaryKey===true)
                 continue;
                if( !$model->attributeActionAvailable($attribute,'update'))
                 continue;
        ?>
        <div class="row <?php if($model->getError($attribute)) echo 'errors'; ?>">
            <div>
                <div class="column span-4"><?php echo $form->labelEx($model,$attribute); ?></div>
                <div class="column span-flexible">
                    <?php echo $this->module->createWidget($form,$model,$attribute); ?>
                    <ul class="errorlist"><li><?php echo $form->error($model,$attribute); ?></li></ul>
                    <!-- <p class="help">Enter the same password as above, for verification.</p>-->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </fieldset>
                    
<div class="module footer">
    <ul class="submit-row">
    <?php if (!$model->isNewRecord && $model->actionAvailable('delete')): ?>
        <li class="left delete-link-container">
            <?php echo CHtml::link(YiiadminModule::t('Удалить'),$this->createUrl('manageModel/delete',array(
                    'model_name'=>get_class($model),
                    'pk'=>$model->primaryKey,
                )),
                array(
                    'class'=>'delete-link',
                    'confirm'=>YiiadminModule::t('Удалить запись ID ').$model->primaryKey.'?',
            )); ?>
        </li> 
    <?php endif; ?>
        <li class="submit-button-container">
            <input type="submit" value="<?php echo YiiadminModule::t('Сохранить');?>" class="default" name="_save">
        </li>
        <?if ($model->actionAvailable('create')) { ?>
        <li class="submit-button-container">
            <input type="submit" value="<?php echo YiiadminModule::t('Сохранить и создать новую запись');?>" name="_addanother">
        </li>
        <? } ?>
        <li class="submit-button-container">
            <input type="submit" value="<?php echo YiiadminModule::t('Сохранить и редактировать');?>" name="_continue">
        </li> 
    </ul
    ><br clear="all">
</div>
 
        </div>
    </div>
</div>
<?php
$this->endWidget();
?>
