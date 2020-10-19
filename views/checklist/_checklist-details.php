<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\State;
?>

<div>
    <h2><?= Html::encode("{$checklist->name}") ?></h2>

    <form action="<?= Yii::$app->urlManager->createUrl(['checklist/save-checklist-changes']) ?>" method="post">

        <input type="hidden" name="id" value="<?= Html::encode("{$checklist->id}") ?>" />

        <div class="form-group">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="state_id" id="radioOptions1" value="1" checked>
                    <?= State::getFullStateInfo(1)->caption ?>
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="state_id" id="radioOptions2" value="2" <?= $checklist->ready ? '' : 'disabled' ?>>
                    <?= State::getFullStateInfo(2)->caption ?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="commnet">Kommentar</label>
            <input type="text" class="form-control" id="comment" name="comment" value="<?= Html::encode("{$checklist->comment}") ?>">
        </div>

        <button type="submit" class="btn btn-primary ">Speichern</button>
    </form>
</div>