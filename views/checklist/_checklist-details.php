<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\State;
?>

<div>
    <h2><?= Html::encode("{$checklist->name}") ?></h2>
    <div class="radio-inline">
        <label>
            <input type="radio" name="radioOptions" id="radioOptions1" value="1" checked <?= $checklist->ready ? '' : 'disabled' ?>>
            <?= State::getFullStateInfo(1)->caption ?>
        </label>
    </div>
    <div class="radio-inline">
        <label>
            <input type="radio" name="radioOptions" id="radioOptions2" value="2">
            <?= State::getFullStateInfo(2)->caption ?>
        </label>
    </div>

    <form>
        <div class="form-group">
            <label for="commnet">Kommentar</label>
            <input type="text" class="form-control" id="comment" value="<?= Html::encode("{$checklist->comment}") ?>">
        </div>

        <button type="submit" class="btn btn-primary ">Speichern</button>
    </form>
</div>