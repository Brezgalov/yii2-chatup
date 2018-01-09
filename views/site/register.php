<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->title = 'Chat Up! Sign Up!';
?>
<div class="landing-wrapper">
	<div class="register">
		<?php 
			$form = ActiveForm::begin([
				'id' => 'register-form',
				'fieldConfig' => [
					'template' => $model->getFieldTemplate(),
				],
			]);
		?>
			<div class="container-fluid">
	        	<div class="text-center">
	        		<h2>Sign Up for ChatUp!</h2>
	        	</div>
	        	<?= $form->field($model, 'username')->textInput() ?>
	        	<?= $form->field($model, 'email')->textInput() ?>
	        	<?= $form->field($model, 'password')->passwordInput() ?>
				<?= $form->field($model, 'password_repeat')->passwordInput() ?>
	        	<div class="text-center">
	        			<?= 
				        	Html::submitButton(
				        		'SignUp', 
				        		[
				        			'class' => 'btn btn-danger', 
				        			'name' => 'register-button'
				    			]	
							) 	
						?>
	        	
	        	</div>
	        </div>
	    <?php ActiveForm::end(); ?>
	</div>
</div>