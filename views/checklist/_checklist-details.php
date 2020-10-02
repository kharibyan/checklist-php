<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div>
    <ul>
        <li>
            <?= Html::encode("{$checklist->name}") ?>
        </li>
        <li>
            <?= Html::encode("{$checklist->comment}") ?>
        </li>
        <li>
            Ready to close? : <?= Html::encode(boolval($checklist->ready) ? 'true' : 'false') ?>
        </li>
    </ul>
</div>