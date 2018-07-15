<?php


namespace frontend\models;


use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    /**
     * @var integer
     */
    public $maxCount;

    /**
     * @var array
     */
    public $originalFilename = [];

    /**
     * @var array
     */
    public $filename = [];

    /**
     * @var Contact
     */
    public $contact;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['imageFiles'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' =>
                \Yii::$app->params['imagesPerContact']],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $newFilename = $this->createFilename($file);

                $filePath = Yii::getAlias('@uploads/');
                $file->saveAs($filePath . $newFilename);

                $thumbnailPath = Yii::getAlias('@thumbnails/');
                $width = Yii::$app->params['thumbnail']['width'];
                $height = Yii::$app->params['thumbnail']['height'];
                $quality = Yii::$app->params['thumbnail']['quality'];

                Image::thumbnail($filePath . $newFilename, $width, $height)
                     ->save($thumbnailPath . $newFilename, ['quality' => $quality]);

                $this->originalFilename[] = $file->name;
                $this->filename[] = basename($newFilename);
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     * @throws \yii\base\Exception
     */
    private function createFilename(UploadedFile $file)
    {
        return \Yii::$app->security->generateRandomString(28) . '.' . $file->extension;
    }
}