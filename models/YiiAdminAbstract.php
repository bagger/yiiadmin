<?php

abstract class YiiAdminAbstract extends CActiveRecord
{

    protected $_attributeType = array();

	public function actionAvailable($action,$pk=false)
	{
		$action_prop = 'admin_denyAction'.ucfirst($action);
		if(isset($this->{$action_prop}) && $this->{$action_prop}) return false;

        if($pk) { 
            $action_prop .= 'Idx';
            if(isset($this->{$action_prop}) && $this->{$action_prop}) {
                $idx_list = (array)$this->{$action_prop};
                if(in_array($pk,$idx_list)) return false;
            }
        }


		return true;
	}

	public function attributeActionAvailable($attr,$action)
	{
		$action_prop = 'admin_deny'.ucfirst($attr).'Action'.ucfirst($action);
		if(isset($this->{$action_prop}) && $this->{$action_prop}) return false;
		return true;
	}

	public function actionAttributes($action)
	{
		$method = 'attributes'.ucfirst($action);
		if(!method_exists($this,$method)) return $this->tableSchema->columns;
		$attrs = $this->{$method}();
		$ret = array();
		foreach($attrs as $attr) {
			if(isset($this->tableSchema->columns[$attr])) $ret[] = $this->tableSchema->columns[$attr];
		}
		return $ret;
	}

	public function adminSearch()
	{
		$ret = array();
		$cols = $this->actionAttributes('list');
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
        foreach($ret as $idx=>$el) {
            if($type = $this->_getAttributeType($el[0])) {        
                unset($ret[$idx]);
            }
        }

        foreach($this->_getAttributeTypes() as $name=>$type) {
            $type = array_merge(array($name),$type);
            $ret[] = $type;
        }

        return $ret;
    }

    public function _getAttributeTypes() 
    {
        return $this->_attributeType;
    }
    public function _getAttributeType($name) 
    {
        return isset($this->_attributeType[$name]) ? $this->_attributeType[$name] : null;
    }

    public function _setAttributeType($name,$type) 
    {
        if(!is_array($type)) $type=(array)$type;
        $this->_attributeType[$name] = $type;
    }

}
