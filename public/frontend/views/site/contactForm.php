<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\Contact */
/* @var $uploadForm \frontend\models\UploadForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$formAction = Url::to(['site/add-contact']);
$deleteContactAction = Url::to(['site/delete-contact']);
$deleteImageAction = Url::to(['image/delete']);

$js = <<<JS

let deleteContact = function() {
    
    let id = $(this).closest('.container-fluid').data('contact-id');
    $.post('$deleteContactAction', {'id': id}, function(res) {
        if (res.success) {
            $(this).unbind('click');
            $.pjax.reload({container: '#contacts'});
        }
    });
};

let deleteImage = function() {
    let id = $(this).data('id');
    $.post('$deleteImageAction', {'id': id}, function(res) {
        if (res.success) {
            $(this).unbind('click');
            $.pjax.reload({container: '#contacts'});
        }
    });
};

$('body').on('click', '.delete-btn', deleteContact)
.on('click', '.edit-btn', function() {
    let container = $(this).closest('.container-fluid');
    let id = container.data('contact-id');
    let name = container.data('contact-name');
    let email = container.data('contact-email');
    let phone = container.data('contact-phone');
    let address = container.data('contact-address');
    let form = $('#form-container');
    
    form.find('input#contact-id').val(id);
    form.find('input#contact-name').val(name);
    form.find('input#contact-email').val(email);
    form.find('input#contact-phone').val(phone);
    form.find('input#contact-address').val(address);
    form.find('button[type="submit"]').text('Update');
    
    $('html, body').animate({
        scrollTop: form.offset().top
    }, 1000);
})
.on('click', '.image-delete-btn', deleteImage);

$("#contact_form").on("pjax:end", function() {
    $.pjax.reload({container:"#contacts"});
});
$.pjax.defaults.timeout = 5000;
JS;

$this->registerJs($js, \yii\web\View::POS_LOAD);
?>
<div id="form-container">
    <div class="row">
        <div class="col-lg-5">
            <?php Pjax::begin(['id' => 'contact_form']) ?>
            <?php $form = ActiveForm::begin([
                                                'options' => [
                                                    'data-pjax' => 1,
                                                    'enctype' => 'multipart/form-data',
                                                ],
                                            ]); ?>

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'phone') ?>

            <?= $form->field($model, 'address') ?>

            <?= $form->field($uploadForm, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' =>
                    'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>

</div>
