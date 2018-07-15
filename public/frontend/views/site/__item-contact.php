<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/** @var $model \common\models\Contact */
?>

<div class="container-fluid" data-contact-id="<?= $model->id ?>" data-contact-name="<?= Html::encode($model->name) ?>"
     data-contact-email="<?= Html::encode($model->email) ?>" data-contact-phone="<?= Html::encode($model->phone) ?>"
     data-contact-address="<?= Html::encode($model->address) ?>"
     style="margin-bottom: 25px; padding:15px; background-color: #f9f9f9; border: lightgray dashed;">
    <div class="row">
        <div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1 header">
                    <?= $model->getAttributeLabel('name') ?>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-11 col-lg-11">
                    <?= Html::encode($model->name) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1 header">
                    <?= $model->getAttributeLabel('email') ?>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-11 col-lg-11">
                    <?= Yii::$app->formatter->asEmail($model->email) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1 header">
                    <?= $model->getAttributeLabel('phone') ?>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-11 col-lg-11">
                    <?= Html::encode($model->phone) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1 header">
                    <?= $model->getAttributeLabel('address') ?>
                </div>
                <div class="col-xs-8 col-sm-9 col-md-11 col-lg-11">
                    <?= Html::encode($model->address) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php foreach ($model->images as $image):?>
                        <div class="col-xs-6 col-sm-4 col-md-2 col-lg-1">
                            <a href="<?= Url::to(['image/view', 'id' => $image->id])?>" target="_blank" data-pjax="0">
                                <?= Html::img(['image/thumbnail', 'id' => $image->id]) ?>
                            </a>
                            <button class="btn btn-default image-delete-btn" data-id="<?= $image->id ?>"><span
                                        class="glyphicon
                            glyphicon-trash"></span></button>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>

        </div>
        <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
            <button class="btn btn-default delete-btn"><span class="glyphicon glyphicon-trash"></span></button>
            <button class="btn btn-default edit-btn"><span class="glyphicon glyphicon-pencil"></span></button>
        </div>
    </div>
</div>