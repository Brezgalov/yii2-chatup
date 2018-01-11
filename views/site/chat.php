<?php 
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
?>
<div class="landing-wrapper">
	<div class="chat">
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
		<div class="messages">
						
		</div>
	</div>
</div>