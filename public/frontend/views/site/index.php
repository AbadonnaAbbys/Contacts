<?php

/* @var $this yii\web\View */
/* @var $contactsProvider yii\data\ActiveDataProvider */
/* @var $model \frontend\models\Contact */
/* @var $uploadForm \frontend\models\UploadForm */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <?php \yii\widgets\Pjax::begin(["id" => "contacts"]); ?>

        <?php echo \yii\widgets\ListView::widget(['dataProvider' => $contactsProvider, 'itemView' => '__item-contact']) ?>

        <?php \yii\widgets\Pjax::end(); ?>

        <div>
            <h2>New contact</h2>
            <?php echo $this->render('contactForm', ['model' => $model, 'uploadForm' => $uploadForm]);?>
        <div>

    </div>
</div>
