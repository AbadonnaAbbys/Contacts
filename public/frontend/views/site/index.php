<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <?php \yii\widgets\Pjax::begin(); ?>

        <?php echo \yii\widgets\ListView::widget(['dataProvider' => $contactsProvider, 'itemView' => '__item-contact']) ?>

        <?php \yii\widgets\Pjax::end(); ?>

        <div>
            <h2>New contact</h2>
            <?php echo $this->render('contactForm', ['model' => $formModel]);?>
        <div>

    </div>
</div>
