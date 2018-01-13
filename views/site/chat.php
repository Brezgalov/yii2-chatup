<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
?>
<div class="landing-wrapper">
	<div class="chat">
		<div class="chat-header">
			<h2><?= $name ?></h2>
			<div class="users">
				<h3>Users:</h3>
				<?= Html::ul(
						$users, 
						[
							'class' => 'chat-users',
							'item' => function($user, $index) {
							    return Html::tag(
							        'li',
							        $user->username
							    );
							}
						]
					) 
				?>
			</div>
		</div>
		<div class="messages">
			<h3>Messages:</h3>
			<?= Html::ul(
					$messages, 
					[
						'class' => 'chat-users',
						'item' => function($message, $index) {
						    return Html::tag(
						        'li',
						        $this->render('message', ['message' => $message])
						    );
						}
					]
				) 
			?>
			<?php if (!empty($unread)): ?>
				<div class="unread">
					<div class="text-center">
						<h4>Unread Messages:</h4>
					</div>
					<?= Html::ul(
							$unread, 
							[
								'class' => 'chat-users',
								'item' => function($message, $index) {
								    return Html::tag(
								        'li',
								        $this->render('message', ['message' => $message])
								    );
								}
							]
						) 
					?>
				</div>
			<?php endif; ?>
		</div>
		<div class="new-message">
			<?php
				$form = ActiveForm::begin([
					'id' => 'new-message-form',
					'method' => 'POST',
			        'action' => ['site/new-message'],
				]);
				echo $form->field($model, 'chat_id')
					->hiddenInput()
					->label(false);
				echo $form->field($model, 'user_id')
					->hiddenInput()
					->label(false);
				echo $form->field($model, 'text')->textarea();
				echo HTML::submitButton('Send', ['class' => 'send-message-button']);
				ActiveForm::end();
			?>
		</div>
	</div>
</div>