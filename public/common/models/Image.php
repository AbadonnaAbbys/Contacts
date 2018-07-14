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
            ['file_name', 'string', 'length' => 32],
            ['original_filename', 'string', 'length' => [5, 255]],
        ];
    }

}