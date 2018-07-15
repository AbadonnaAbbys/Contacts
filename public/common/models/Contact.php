<?php


namespace common\models;


use frontend\models\UploadForm;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Contact model
 *
 * @package common\models
 * @property int $id [int(11)]
 * @property int $user_id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $phone [varchar(15)]
 * @property string $email [varchar(255)]
 * @property string $address [varchar(255)]
 */
class Contact extends ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'safe'],
            [['name', 'phone', 'email', 'address'], 'trim'],
            [['name', 'phone', 'email', 'address'], 'required'],
            ['user_id', 'required', 'on' => self::SCENARIO_CREATE],
            ['user_id', 'exist', 'targetClass' => 'common\models\User', 'targetAttribute' => 'id', 'on' => self::SCENARIO_CREATE],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+?[0-9 ]{7,}$/'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['contact_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $imagePath = \Yii::getAlias('@uploads/');
        $thumbPath = \Yii::getAlias('@thumbnails/');

        /**
         * @var $image Image
         */
        foreach ($this->images as $image) {
            unlink($imagePath . $image->file_name);
            unlink($thumbPath . $image->file_name);
        }

        return true;
    }

    /**
     * Save uploaded images
     *
     * @return bool
     */
    public function saveImages()
    {
        $uploadForm = new UploadForm(['contact' => $this]);
        $uploadForm->imageFiles = UploadedFile::getInstances($uploadForm, 'imageFiles');
        $uploadForm->imageFiles = array_slice($uploadForm->imageFiles, 0, $this->vacancyImageCount());

        try {
            $uploadForm->upload();
            foreach ($uploadForm->filename as $k => $v) {
                $image = new Image();
                $image->contact_id = $this->id;
                $image->original_filename = $uploadForm->originalFilename[$k];
                $image->file_name = $v;
                $image->save();
            }

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return int
     */
    public function vacancyImageCount()
    {
        return \Yii::$app->params['imagesPerContact'] - count($this->images);
    }
}