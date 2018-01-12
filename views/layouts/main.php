<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\models\CreateChatForm;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
$currentUser = Yii::$app->user->getIdentity();
$newChatFrom = new CreateChatForm();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
	    <meta charset="<?= Yii::$app->charset ?>">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <?= Html::csrfMetaTags() ?>
	    <title><?= Html::encode($this->title) ?></title>
	    <?php $this->head() ?>
	</head>
	<body>
		<?php $this->beginBody() ?>

		<div class="container">
			<div class="row main">
				<div class="col-md-3 sidebar">
					<div class="title">
						<?= 
	        				Html::a(
	        					'<h1>Chat Up</h1>', 
	        					['/'], 
	        					['class' => 'brand']
	    					) 
	    				?>
    				</div>
    				<?php if ($currentUser): ?>
	    				<div class="current-user">
	    					<span class="name">
	    						Logged in as 
	    						<?= Html::a(
					                    $currentUser->username, 
					                    [
					                        'site/profile', 
					                        'id' => $currentUser->id
					                    ]
					                );
				                ?>
	    					</span>
	    					<?= 
		        				Html::a(
		        					'Log out', 
		        					['site/logout'], 
		        					[]
		    					) 
	    					?>
	    				</div>
	    				<div class="new-chat">
	    					<?php 
								$form = ActiveForm::begin([
							        'id' => 'new-chat-form',
							        'method' => 'POST',
							        'action' => ['site/chatup'],
							        'fieldConfig' => [
							            'template' => $newChatFrom->getFieldTemplate(),
							        ],
							    ]); 
							    $contactsAvailable = $currentUser->getAvailableContacts();
							    echo $form->field(
		    						$newChatFrom, 
		    						'users[]'
	    						)->checkboxList($contactsAvailable); 
	    						echo $form->field(
	    							$newChatFrom,
	    							'name'
    							);
					        	echo Html::submitButton(
					        		'Chat up with selected users', 
					        		[
					        			'class' => 'btn btn-default', 
					        			'name' => 'chatup-button'
					    			]	
								); 	
								ActiveForm::end(); 
							 ?>
	    				</div>
	    				<div class="chats-list">
	    					<h2>Chats</h2>
	    					<?= Html::ul(
			    					$currentUser->getChats(), 
			    					[
			    						'class' => 'chats-list',
			    						'item' => function($userChat, $index) {
										    return Html::tag(
										        'li',
										        Html::a(
						        					$userChat->chat->name, 
						        					[
						        						'site/chat', 
						        						'id' => $userChat->chat->id
					        						], 
						        					['class' => 'brand']
						    					) 
										    );
										}
									]
								) 
							?>
	    				</div>
    				<?php else: ?>
	    				<div class="users-list">
		    				<?= Html::ul(
			    					User::all(), 
			    					[
			    						'class' => 'chatup-users',
			    						'item' => function($data, $index) {
										    return Html::tag(
										        'li',
										        $data['username']
										    );
										}
									]
								) 
							?>
						</div>
    				<?php endif; ?>
				</div>
				<div class="col-md-9 workspace">
					<?= $content ?>
				</div>
			</div>
		</div>

		<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>