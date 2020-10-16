<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\State;
?>


<div>
    <form action="<?= Yii::$app->urlManager->createUrl(['checklist/save-item-changes']) ?>" method="post">
        <input type="hidden" name="id" value="<?= Html::encode("{$checklistItem->id}") ?>" />
        <h2><?= Html::encode("{$checklistItem->item}") ?></h2>
        <div class="form-group" id="radioStateId">
            <div class="radio-inline">
                <label>
                    <input type="radio" name="state_id" id="radioOptions1" value="1" <?= $checklistItem->state_id == 1 ? 'checked' : '' ?>>
                    <?= State::getFullStateInfo(1)->caption ?>
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="state_id" id="radioOptions2" value="2" <?= $checklistItem->state_id == 2 ? 'checked' : '' ?>>
                    <?= State::getFullStateInfo(2)->caption ?>
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    <input type="radio" name="state_id" id="radioOptions3" value="3" <?= $checklistItem->state_id == 3 ? 'checked' : '' ?>>
                    <?= State::getFullStateInfo(3)->caption ?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="commnet">Kommentar</label>
            <input type="text" class="form-control" id="comment" name="comment" value="<?= Html::encode("{$checklistItem->comment}") ?>" <?= $checklistItem->state_id == 3 ? 'required' : '' ?>>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

    <?php if (!empty($checklistItemHistory)) : ?>
        <h2><small>Verlauf</small></h2>
        <ul class="list-group">
            <?php foreach ($checklistItemHistory as $history) : ?>
                <li class="list-group-item checkered">
                    <?php echo Html::encode("{$history->created_at}") . " - " . State::getFullStateInfo($history->state_id_new)->caption;
                    if (isset($history->comment)) {
                        echo " - " . Html::encode("{$history->comment}");
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>