<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $images;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'safe'],
            [['name', 'phone', 'email', 'address'], 'trim'],
            [['user_id', 'name', 'phone', 'email', 'address'], 'required'],
            ['user_id', 'exist', 'targetClass' => 'common\models\User', 'targetAttribute' => 'id'],
            ['email', 'email'], // TODO: Add email exit for current user validation
            ['phone', 'match', 'pattern' => '/^\+?[0-9 ]{7,}$/'],
        ];
    }
}
