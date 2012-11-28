<?php

class YiiAdminFileValidator extends CFileValidator
{

	/**
	 * Set the attribute and then validates using {@link validateFile}.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object, $attribute)
	{

		if($this->maxFiles > 1)
		{
			$files=$object->$attribute;
			if(!is_array($files) || !isset($files[0]) || !$files[0] instanceof YiiAdminUploadedFile)
				$files = YiiAdminUploadedFile::getInstances($object, $attribute);
			if(array()===$files)
				return $this->emptyAttribute($object, $attribute);
			if(count($files) > $this->maxFiles)
			{
				$message=$this->tooMany!==null?$this->tooMany : Yii::t('yii', '{attribute} cannot accept more than {limit} files.');
				$this->addError($object, $attribute, $message, array('{attribute}'=>$attribute, '{limit}'=>$this->maxFiles));
			}
			else
				foreach($files as $file)
					$this->validateFile($object, $attribute, $file);
		}
		else
		{
			$file = $object->$attribute;
			if(!$file instanceof YiiAdminUploadedFile)
			{
				$file = YiiAdminUploadedFile::getInstance($object, $attribute);
				if(null===$file)
					return $this->emptyAttribute($object, $attribute);
			}
			$this->validateFile($object, $attribute, $file);
            $object->$attribute = $file->__toString();
		}
	}

}
