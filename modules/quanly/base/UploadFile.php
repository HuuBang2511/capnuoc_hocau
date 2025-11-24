<?php

namespace app\modules\quanly\base;

use yii\base\Model;

class UploadFile extends Model
{
    public $fileupload;
    public $imageupload;
    public $type;

    public function rules()
    {
        return [
            [['fileupload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,docx,xlxs,jpeg,png,jpg,tif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 20],
            [['imageupload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 3],
            [['type'], 'string'],
        ];
    }

    public function uploadFile($path, $file){
        if(file_exists($path)){
            $file->saveAs($path.$file->baseName . '.' . $file->extension);
            return true;
        }
        else{
            mkdir($path, 0777, true);
            $file->saveAs($path.$file->baseName . '.' . $file->extension);
            return true;
        }
    }
}