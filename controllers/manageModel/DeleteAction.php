<?php

class DeleteAction extends CAction
{
	/**
	 * Создание новой записи.
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$model_name=(string)$_GET['model_name']; 
		$model=$this->controller->module->loadModel($model_name)->findByPk($_GET['pk']);

		if ($model!==null)
		{
			$model->delete();
			$this->controller->redirect($this->controller->createUrl('manageModel/list',array('model_name'=>$model_name)));
		}
	}
}
