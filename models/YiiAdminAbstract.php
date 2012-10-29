<?php

abstract class YiiAdminAbstract extends CActiveRecord
{

	public function actionAvailable($action)
	{
		$action_prop = 'admin_deny'.ucfirst($action);
		if(isset($this->{$action_prop}) && $this->{$action_prop}) return false;
		return true;
	}

	public function adminSearch()
	{
		$ret = array();
		$cols = $this->tableSchema->columns;
		foreach($cols as $name=>$data) {
			if($data->dbType == 'text' || $data->dbType == 'blob') {
				$ret[] = array('name'=>$data->name,'value'=>'mb_substr(strip_tags($data->'.$data->name.'),0,100)."..."');
			} else {
				$ret[] = $data->name;
			}
		}
		return array(
			'columns'=>$ret
		);
		return array(
			'columns'=> array_keys($this->tableSchema->columns)
		);
	}

	public function attributeWidgets()
	{
		$ret = array();
		$cols = $this->tableSchema->columns;
		foreach($cols as $name=>$data) {
			if($data->dbType == 'date') {
				$ret[] = array($data->name,'calendar','options'=>array('dateFormat'=>'yyyy-mm-dd'));
			}
			if($data->dbType == 'text') {
				$ret[] = array($data->name,'textArea');
			}
			if(in_array($data->dbType,array('boolean','tinyint(1)','bool'))) {
				$ret[] = array($data->name,'boolean');
			}
		}
		return $ret;
	}

}
