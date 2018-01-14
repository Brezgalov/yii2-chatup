<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="landing-wrapper">
    <div class="error">
        <h2>Whoops!</h2>
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
        <div class="text-center">
            <?= 
                Html::a(
                    "OK! I promise to go back and play nice.", 
                    Yii::$app->request->referrer,
                    [
                        'class' => ['back-btn'],
                    ]
                ); 
            ?>
        </div>
    </div>
</div>
