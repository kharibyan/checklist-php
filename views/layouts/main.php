<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\UserTable;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php
    $this->beginBody();

    NavBar::begin([
        /* 'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl, */
        'options' => [
            'class' => 'navbar-inverse',
            'style' => 'margin-bottom: 0;'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Checklisten', 'url' => ['/checklist/index']],
            ['label' => 'Templates', 'url' => ['/checklist-template/index']],
            ['label' => 'Benutzer Checklisten', 'url' => ['/user-checklists/index']],
            ['label' => 'Checklisten Terminplan', 'url' => ['/schedule/index']],
            Yii::$app->user->isGuest ? (['label' => 'Einloggen', 'url' => ['/site/login']]) : ('<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Abmelden (' . UserTable::getFullUserInfo(Yii::$app->user->identity->id)->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>')
        ],
    ]);

    NavBar::end();
    ?>

    <div>
        <?= $content ?>
    </div>

    <div id="alert-placeholder"></div>

    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert-message">
            <div class="alert-inner-message alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?= Yii::$app->session->getFlash('success') ?></strong>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert-message">
            <div class="alert-inner-message alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?= Yii::$app->session->getFlash('error') ?></strong>
            </div>
        </div>
    <?php endif; ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>