<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$formAction = Url::to(['site/add-contact']);
$deleteAction = Url::to(['site/delete-contact']);

$js = <<<JS
let beforeSubmitContact = function(evt){
    
    console.log('before submit');
    
    evt.preventDefault();
    evt.stopImmediatePropagation();

    let form = $(this);
    let action = form.attr('action');
    let data = form.serialize();
    
    $.post(action, data, function(res) {
        $('#form-container').replaceWith(res.html);
        $.pjax.reload({container: '#p0'});
        $('.delete-btn').on('click', deleteContact);
    });

    return false;
};

// let submitContact = function(evt) {
//    
//     console.log('submit');
//    
//     evt.preventDefault();
//     evt.stopImmediatePropagation();
// };

let deleteContact = function() {
    
    console.log('delete');
    
    let id = $(this).closest('.container-fluid').data('contact-id');
    $.post('$deleteAction', {'id': id}, function(res) {
        if (res.success) {
            $(this).unbind('click');
            $.pjax.reload({container: '#p0'});
        } else {
            alert(res.error);
        }
    });
};

$('body').on('click', '.delete-btn', deleteContact)
.on('beforeSubmit', '#contact-form', beforeSubmitContact);
// .on('submit', '#contact-form', submitContact);
JS;

$this->registerJs($js, \yii\web\View::POS_LOAD);
?>
<div id="form-container">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'contact-form',
                'action' => ['site/create-contact'],
            ]); ?>

            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'phone') ?>

            <?= $form->field($model, 'address') ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
