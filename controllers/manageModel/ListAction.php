<?php

class ListAction extends CAction
{

	/**
	 * Вывод списка записей модели.
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$model_name=(string)$_GET['model_name']; 
		$model=$this->controller->module->loadModel($model_name);

		if (isset($_GET[get_class($model)]))
			$model->attributes=$_GET[get_class($model)];

		$this->controller->breadcrumbs=array(
				$this->controller->module->getModelNamePlural($model),
				);

		if (method_exists($model,'adminSearch')) {
			$data1=$model->adminSearch();
		} else {
			$data1=array();
		}

		$url_prefix = 'Yii::app()->createUrl("yiiadmin/manageModel/';

		$actionColumn = array(
			'class'=>'YiiAdminButtonColumn',
			'viewButtonOptions'=>array(
				'style'=>'display:none;',
			),
		);

		$actionColumn['deleteButtonUrl'] = $url_prefix.'delete",array("model_name"=>"'.get_class($model).'","pk"=>$data->primaryKey))';
		$actionColumn['viewButtonUrl'] = $url_prefix.'view",array("model_name"=>"'.get_class($model).'","pk"=>$data->primaryKey))';
		$actionColumn['updateButtonUrl'] = $url_prefix.'update",array("model_name"=>"'.get_class($model).'","pk"=>$data->primaryKey))';

		$actionColumn['template'] = '';
		if($model->actionAvailable('update'))
			$actionColumn['template'] .= '{update}';
		if($model->actionAvailable('delete'))
			$actionColumn['template'] .= '{delete}';
		if($model->actionAvailable('view'))
			$actionColumn['template'] .= '{view}';

		$data2=array(
			'id'=>'objects-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$this->controller->module->filter? $model : null,
			'itemsCssClass'=>'table',
			'enablePagination'=>true,
			'pagerCssClass'=>'pagination',
			'selectableRows'=>2,
			'pager'=>array(
				'cssFile'=>false,
				'htmlOptions'=>array('class'=>'pagination'),
				'header'=>false,
				),
			'template'=>' 
			<div class="module changelist-results">{items}</div>
			<div class="module pagination">{pager}{summary}<br clear="all"></div> 
			',
			'columns'=>array(
				$actionColumn,
			),
		); 

		$listData=array_merge_recursive($data1,$data2); 

		if (Yii::app()->request->isAjaxRequest)
		{
			$this->controller->widget('zii.widgets.grid.CGridView',$listData); 
			Yii::app()->end();
		}

		$this->controller->render('list_objects',array(
					'title'=>$this->controller->module->getModelNamePlural($model),
					'model'=>$model, 
					'listData'=>$listData, 
					));
	}
}
