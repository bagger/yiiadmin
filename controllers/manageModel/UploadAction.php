<?php

class UploadAction extends CAction
{
    public function run()
    {
        if(isset($_GET['type']) && $_GET['type'] == 'image') {
            $dir = YiiBase::getPathOfAlias($this->controller->module->imagesUploadDir);
            $webroot = YiiBase::getPathOfAlias('webroot');

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

                $rel = mb_substr($file,mb_strlen($webroot),mb_strlen($file)-1);

                // copying
                move_uploaded_file($_FILES['file']['tmp_name'], $file);

                echo stripslashes(json_encode(array(
                                'filelink' => $rel,
                                )));

            } else {
                echo stripslashes(json_encode(array(
                                'filelink' => null,
                                'error' => 'File type is bad: ',
                                )));
            }



        } else {
                echo stripslashes(json_encode(array(
                                'filelink' => null,
                                'error' => 'No type is set or type is not image',
                                )));
        }

    }
}
