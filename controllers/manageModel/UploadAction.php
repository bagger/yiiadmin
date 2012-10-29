<?php

class UploadAction extends CAction
{
	public function run()
	{

		if(isset($_GET['type']) && $_GET['type'] == 'image') {
			$dir = YiiBase::getPathOfAlias($this->controller->module->imagesUploadDir);

			$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

			if ($_FILES['file']['type'] == 'image/png'
					|| $_FILES['file']['type'] == 'image/jpg'
					|| $_FILES['file']['type'] == 'image/gif'
					|| $_FILES['file']['type'] == 'image/jpeg'
					|| $_FILES['file']['type'] == 'image/pjpeg')
			{
				// setting file's mysterious name
				$fname = md5(date('YmdHis')).'.jpg';
				$file = $dir.'/'.$fname;

				// copying
				move_uploaded_file($_FILES['file']['tmp_name'], $file);

				echo stripslashes(json_encode(array(
						'filelink' => $file
				)));

			} else {
				echo stripslashes(json_encode(array(
						'filelink' => null
				)));
			}



		}

	}
}
