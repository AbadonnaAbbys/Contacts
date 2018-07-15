<?php

namespace frontend\services;

use frontend\models\Contact;

/**
 * Class ContactService
 */
class ContactService
{
    /**
     * @param Contact $contact
     *
     * @return \frontend\models\UploadForm
     */
    public static function prepareImages(Contact $contact) {
        $maxImagesCount = \Yii::$app->params['imagesPerContact'];
        $imagesCount = count($contact->images);

        $uploadForm = new \frontend\models\UploadForm(['maxCount' => $maxImagesCount - $imagesCount]);
        return $uploadForm;
    }
}