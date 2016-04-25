<?php
use yii\widgets\ActiveForm;
?>


<h1> Add new article </h1>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
	<?= $form->field($model, 'articleTitle')->textInput(['autofocus' => true]) ?>
	
	<?= $form->field($model, 'articleContent')->textArea(['rows' => '6']) ?>
	
       <?= $form->field($model, 'imageFile')->fileInput() ?>
    
        <button class="btn btn-sm btn-primary">Submit</button>

<?php ActiveForm::end() ?>
