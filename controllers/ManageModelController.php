<?php
/**
 * ManageModelController 
 * 
 * @uses YAdminController
 * @package yiiadmin
 * @version $id$
 * @copyright 2010 firstrow@gmail.com
 * @author Firstrow <firstrow@gmail.com> 
 * @license BSD
 */

/**
 * Управление данными модели. Вывод, редактирование, удаление.
 **/
class ManageModelController extends YAdminController
{

	public function actions()       {
		return array(    
				'index'  => 'yiiadmin.controllers.manageModel.IndexAction',
				'list'  => 'yiiadmin.controllers.manageModel.ListAction',
				'create'  => 'yiiadmin.controllers.manageModel.CreateAction',
				'update'  => 'yiiadmin.controllers.manageModel.UpdateAction',
				'delete'  => 'yiiadmin.controllers.manageModel.DeleteAction',
				'upload'  => 'yiiadmin.controllers.manageModel.UploadAction',
			    );
	}

	/**
	 * Redirect after editing model data.
	 * 
	 * @param string $model_name 
	 * @param integer $pk 
	 * @access protected
	 * @return void
	 */
	public function redirectUser($model_name,$pk)
	{
		if ($_POST['_save'])
			$this->redirect($this->createUrl('manageModel/list',array('model_name'=>$model_name)));  
		if ($_POST['_addanother'])
		{
			Yii::app()->user->setFlash('flashMessage', YiiadminModule::t('Изменения сохранены. Можете создать новую запись.')); 
			$this->redirect($this->createUrl('manageModel/create',array('model_name'=>$model_name)));
		}
		if ($_POST['_continue'])
			$this->redirect($this->createUrl('manageModel/update',array('model_name'=>$model_name,'pk'=>$pk)));
	}
}
