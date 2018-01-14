<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

	$this->registerJsFile(
        '@web/js/convert-UNIX-timestamp.js',
        [
            'depends' => [\yii\web\JqueryAsset::className()]
        ]
    );
    $this->registerJsFile(
        '@web/js/chat.js',
        [
            'depends' => [\yii\web\JqueryAsset::className()]
        ]
    );

	$this->title = 'Chat Up! '.$name;
?>
<div class="landing-wrapper">
	<div class="chat">
		<div class="chat-header">
			<div class="title-wrapper">
				<h2><?= $name ?></h2>
			</div>
			<div class="users noselect">
				<h3>Users:</h3>
				<?= Html::ul(
						$users, 
						[
							'class' => ['chat-users'],
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
			<?= Html::ul(
					$messages, 
					[
						'class' => [
							'chat-messages'
						],
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
					<div class="separator">
						<h4>Unread Messages:</h4>
					</div>
					<?= Html::ul(
							$unread, 
							[
								'class' => ['chat-messages-unread'],
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
				echo $form->field($model, 'text')
					->textarea()
					->label(false);
				echo HTML::submitButton(
					'Send', 
					[
						'class' => ['send-message-button']
					]
				);
				ActiveForm::end();
			?>
		</div>
	</div>
</div>