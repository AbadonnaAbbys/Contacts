<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * Image model
 *
 * @package common\models
 * @property int $id [int(11)]
 * @property int $contact_id [int(11)]
 * @property string $original_filename [varchar(255)]
 * @property string $file_name [char(32)]
 * @property Contact $contact
 */
class Image extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['original_filename', 'file_name'], 'trim'],
            [['contact_id', 'original_filename', 'file_name'], 'required'],
            ['contact_id', 'exist', 'targetClass' => 'common\models\Contact', 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::class, ['id' => 'contact_id']);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return \Yii::getAlias('@uploads/') . $this->file_name;
    }

    /**
     * @return string
     */
    public function getThumbnailPath()
    {
        return \Yii::getAlias('@thumbnails/') . $this->file_name;
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

        unlink($imagePath . $this->file_name);
        unlink($thumbPath . $this->file_name);

        return true;
    }
}