<?php

class YiiAdminUploadedFile extends CUploadedFile
{

    protected $_savedPath;
	static private $_files;

    public function __construct($name,$tempName,$type,$size,$error)
    {
        parent::__construct($name,$tempName,$type,$size,$error);
        $this->_savedPath = '/assets/uploaded/'.md5(uniqid()).($this->getExtensionName()? '.'.$this->getExtensionName() : '');
        $this->saveAs(Yii::getPathOfAlias('webroot').$this->_savedPath);
    }

	/**
	 * Processes incoming files for {@link getInstanceByName}.
	 * @param string $key key for identifiing uploaded file: class name and subarray indexes
	 * @param mixed $names file names provided by PHP
	 * @param mixed $tmp_names temporary file names provided by PHP
	 * @param mixed $types filetypes provided by PHP
	 * @param mixed $sizes file sizes provided by PHP
	 * @param mixed $errors uploading issues provided by PHP
	 */
	protected static function collectFilesRecursive($key, $names, $tmp_names, $types, $sizes, $errors)
	{
		if(is_array($names))
		{
			foreach($names as $item=>$name)
				self::collectFilesRecursive($key.'['.$item.']', $names[$item], $tmp_names[$item], $types[$item], $sizes[$item], $errors[$item]);
		}
		else
			self::$_files[$key] = new YiiAdminUploadedFile($names, $tmp_names, $types, $sizes, $errors);
	}

	public function __toString()
	{
		return $this->_savedPath;
	}

    /**
     * Initially processes $_FILES superglobal for easier use.
     * Only for internal usage.
     */
    protected static function prefetchFiles()
    {
        self::$_files = array();
        if(!isset($_FILES) || !is_array($_FILES))
            return;

        foreach($_FILES as $class=>$info)
            self::collectFilesRecursive($class, $info['name'], $info['tmp_name'], $info['type'], $info['size'], $info['error']);
    }

	public static function getInstanceByName($name)
	{
		if(null===self::$_files)
			self::prefetchFiles();

		return isset(self::$_files[$name]) && self::$_files[$name]->getError()!=UPLOAD_ERR_NO_FILE ? self::$_files[$name] : null;
	}

    public static function getInstance($model, $attribute)
    {
        return self::getInstanceByName(CHtml::resolveName($model, $attribute));
    }

    

}
