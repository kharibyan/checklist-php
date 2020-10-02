<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div>
    <ul>
        <li>
            <?= Html::encode("{$checklistItem->item}") ?>
        </li>
        <li>
            <?= Html::encode("{$checklistItem->state_id}") ?>
        </li>
        <li>
            <?= Html::encode("{$checklistItem->comment}") ?>
        </li>
        <li>History :
            <ul>
                <?php foreach ($checklistItemHistory as $history) : ?>
                    <li>
                        <?= Html::encode("{$history->created_at}") ?>
                    </li>
                    <li>
                        <?= Html::encode("{$history->state_id_new}") ?>
                    </li>
                    <li>
                        <?= Html::encode("{$history->comment}") ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
</div>