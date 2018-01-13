<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\User;
use app\models\forms\CreateChatForm;
use app\models\forms\SetStatusForm;
use yii\bootstrap\ActiveForm;
use app\controllers\UserController;

AppAsset::register($this);
$currentUser = Yii::$app->user->getIdentity();
$newChatFrom = new CreateChatForm();
$statusForm = new SetStatusForm();
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
	    					<div class="links">
		    					<span class="name"> 
		    						<?= Html::a(
						                    '@'.$currentUser->username, 
						                    [
						                        'user/profile', 
						                        'id' => $currentUser->id
						                    ]
						                );
					                ?>
		    					</span>
		    					<?= 
			        				Html::a(
			        					'Log out', 
			        					['user/logout'], 
			        					[]
			    					) 
		    					?>
	    					</div>
	    					<div class="status">
	    						<?php 
	    							$form = ActiveForm::begin([
	    								'id' => 'status-form',
	    								'method' => 'POST',
	    								'action' => ['user/status'],
    								]);
    								echo $form->field(
    									$statusForm,
    									'user_id'
									)->hiddenInput([
										'value' => $currentUser->id,
									])->label(false)->error(false);
									echo $form->field(
										$statusForm,
										'status'
									)->input(
										'text',
										[
											'placeholder' => 'Which one are you today?',
											'value' => $currentUser->status,
											'class'=> [
												'with-submit',
												'sidebar-text-input', 
												'form-control'
											],
										]
									)->label(false)->error(false);
									echo HTML::submitButton(
										'>',
										[
											'class' => ['sidebar-submit-button'],
										]
									);
    								ActiveForm::end();
								?>
	    					</div>
	    				</div>
	    				<div class="new-chat">
	    					<h2>New Chat</h2>
	    					<?php 
								$form = ActiveForm::begin([
							        'id' => 'new-chat-form',
							        'method' => 'POST',
							        'action' => ['site/chatup'],
							        'fieldConfig' => [
							            'template' => $newChatFrom->getFieldTemplate(),
							        ],
							    ]); 
							    $contactsAvailable = UserController::getAvailableContactsList(
							    	$currentUser->id
						    	);
							    echo $form->field(
		    						$newChatFrom, 
		    						'users[]'
	    						)->checkboxList(
	    							$contactsAvailable,
	    							[
	    								'class' => ['sidebar-input-area'],
	    							]
    							)->label(false)->error(false); 
	    						echo $form->field(
	    							$newChatFrom,
	    							'name'
    							)->input(
    								'text',
    								[
    									'placeholder' => 'Chat name here',
    									'class'=> [
    										'with-submit',
											'sidebar-text-input', 
											'form-control'
										],
    								]
    							)->label(false)->error(false);
					        	echo Html::submitButton(
					        		'>', 
					        		[
					        			'class' => ['sidebar-submit-button'], 
					        			'name' => 'chatup-button',
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
			    						'item' => function($userChat, $index) use ($currentUser) {
			    							$text = $userChat->chat->name;
			    							$unread = $userChat->countUnread($currentUser->id);
			    							if ($unread) {
			    								$text .= Html::tag(
				    								'span', 
				    								$unread,
				    								[
				    									'class' => ['badge', 'messages']
				    								]
			    								);
			    							}
			    							
										    return Html::tag(
										        'li',
										        Html::a(
						        					$text, 
						        					[
						        						'site/chat', 
						        						'id' => $userChat->chat->id
					        						]
						    					),
						    					['class' => ['line']]
										    );
										},
										'class' => ['sidebar-input-area']
									]
								) 
							?>
	    				</div>
    				<?php else: ?>
	    				<div class="users-list">
		    				<?= Html::ul(
			    					User::find()->all(), 
			    					[
			    						'class' => 'chatup-users',
			    						'item' => function($user, $index) {
										    return Html::tag(
										        'li',
										        $user->username.
										        HTML::tag(
								                    'span', 
								                    '', 
								                    [
								                        'class' => [
								                        	'user-state', 
								                        	'user-'.$user->getState(),
							                        	]
								                    ]
								                )
										    );
										}
									]
								)
							?>
						</div>
    				<?php endif; ?>
    				<div class="developer">
    					&copy; Oleg Brezgalov
    				</div>
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