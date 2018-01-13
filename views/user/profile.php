<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
	use app\models\forms\UserProfileForm;

	$this->title = 'Chat Up! '.$user->username.' profile';
	$currentUserId = Yii::$app->user->id;
?>
<div class="landing-wrapper">
	<div class="profile">
		<div class="head">
			<h2>
				Profile: <?= $user->username ?>
			</h2> 
			<h4>
				Status: <?= $user->getState() ?>
			</h4>
		</div>
		<div class="body">
			<?php 
				if ($user->id == $currentUserId): 
				$profileForm = new UserProfileForm();
			?>
				<div class="user-form container-fluid">
					<?php 
						$form = ActiveForm::begin([
							'id' => 'user-profile-form',
							'method' => 'POST',
							'action' => ['user/save-profile'],
						]);
						echo $form->field(
							$profileForm, 
							'user_id'
						)->hiddenInput([
							'value' => $user->id,
						])->label(false);
					?>
						<div class="row">
							<div class="col-md-6">
								<h3>Base data</h3>
								<?php 
									echo $form->field(
										$profileForm, 
										'status'
									)->textInput([
										'value' => $user->status
									]);
									echo $form->field(
										$profileForm, 
										'username'
									)->textInput([
										'value' => $user->username
									]);
									echo $form->field(
										$profileForm, 
										'email'
									)->textInput([
										'value' => $user->email
									]);
								?>
							</div>
							<div class="col-md-6">
								<h3>Password</h3>
								<?php 
									echo $form->field(
										$profileForm, 
										'previous_password'
									)->input('password');
									echo $form->field(
										$profileForm, 
										'password'
									)->input('password')->label('New Password');
									echo $form->field(
										$profileForm, 
										'password_repeat'
									)->input('password');
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?= 
						        	Html::submitButton(
						        		'Save', 
						        		[
						        			'class' => 'btn btn-primary', 
						        			'name' => 'login-button'
						    			]	
									) 	
								?>
							</div>
						</div>
					<?php ActiveForm::end();?>
				</div>
			<?php else: ?>
				<div class="user-data">
					<table class="table table-responsive table-bordered">
						<tr>
							<td>
								<b>User note:</b>
							</td>
							<td>
								<?= $user->status ?>
							</td>
						</tr>
						<tr>
							<td>
								<b>User email:</b>
							</td>
							<td>
								<?= $user->email ?>
							</td>
						</tr>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>