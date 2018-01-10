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

AppAsset::register($this);
$currentUser = Yii::$app->user->getIdentity();
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
	    					<span class="name">Logged in as <?= $currentUser->username ?></span>
	    					<?= 
		        				Html::a(
		        					'Log out', 
		        					['site/logout'], 
		        					[]
		    					) 
	    					?>
	    				</div>
    				<?php endif; ?>
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