<?php


namespace common\models;


use yii\db\ActiveRecord;

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
            [['name', 'phone', 'email', 'address'], 'trim'],
            [['user_id', 'name', 'phone', 'email', 'address'], 'required'],
            ['user_id', 'exist', 'targetClass' => 'common\models\User', 'targetAttribute' => 'id'],
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
}