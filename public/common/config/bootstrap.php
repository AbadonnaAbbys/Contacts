<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@uploads', Yii::getAlias('@common') . '/uploads');
Yii::setAlias('@thumbnails', Yii::getAlias('@common') . '/uploads/thumbnails');
