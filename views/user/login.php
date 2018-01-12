<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->title = 'Chat Up! Login';
?>
<div class="landing-wrapper">
	<div class="login">
		<?php 
			$form = ActiveForm::begin([
		        'id' => 'login-form',
		        'fieldConfig' => [
		            'template' => $model->getFieldTemplate(),
		        ],
		    ]); 
	    ?>    
	        <div class="container-fluid">
	        	<div class="text-center">
	        		<h2>Sign in ChatUp!</h2>
	        	</div>
	        	<?= $form->field($model, 'email')->textInput() ?>
	        	<?= $form->field($model, 'password')->passwordInput() ?>
				<?= 
					$form->field($model, 'rememberMe')->checkbox([
						'template' => $model->getFieldTemplate(),
					]) 
				?>
	        	<div class="row">
	        		<div class="col-md-3 col-md-offset-3 col-sm-12">
	        			<?= 
	        				Html::a(
	        					'SignUp', 
	        					['user/register'], 
	        					['class' => 'btn btn-danger']
	    					) 
	    				?>
	        		</div>
	        		<div class="col-md-3 col-sm-12">
	        			<?= 
				        	Html::submitButton(
				        		'LogIn', 
				        		[
				        			'class' => 'btn btn-primary', 
				        			'name' => 'login-button'
				    			]	
							) 	
						?>
	        		</div>
	        	</div>
	        </div>
	    <?php ActiveForm::end(); ?>
	</div>
</div>