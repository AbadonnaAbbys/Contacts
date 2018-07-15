<?php


namespace frontend\models;

use common\models\Contact as CommonContact;

/**
 * Class Contact
 *
 * @package frontend\models
 */
class Contact extends CommonContact
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        $query = parent::find();
        $query->andWhere(['user_id' => \Yii::$app->user->id]);

        return $query;
    }
}