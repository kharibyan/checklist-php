<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Checklisten Terminplan';
$this->registerJsFile('js/schedule.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-default" data-toggle="modal" href="#modalNewSchedule">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Termin Hinzufügen
            </button>
            <button type="button" id="btnMoveUp" class="btn btn-default">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Fälligkeit Prüfen
            </button>
        </div>
    </div>
    <div class="row">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Template</th>
                    <th>Owner</th>
                    <th>Asigned To</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Last Schedule</th>
                    <th>Interval</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scheduleList as $schedule) : ?>
                    <tr>
                        <td><?= Html::encode("{$schedule->template_name}") ?></td>
                        <td><?= Html::encode("{$schedule->owner_name}") ?></td>
                        <td><?= Html::encode("{$schedule->assigned_name}") ?></td>
                        <td><?= Html::encode("{$schedule->start_date}") ?></td>
                        <td><?= Html::encode("{$schedule->end_date}") ?></td>
                        <td><?= Html::encode("{$schedule->last_schedule}") ?></td>
                        <td><?= Html::encode("{$schedule->interval_count}") ?></td>
                        <td><?= Html::encode("{$schedule->interval_unit}") ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNewSchedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Neuen Termin Hinzufügen</h4>
            </div>
            <form action="<?= Yii::$app->urlManager->createUrl(['schedule/create-schedule']) ?>" method="post" id="formNewSchedule">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="checklist_template_id">Template wählen</label>
                        <select class="form-control" id="checklist_template_id" required="required" name="checklist_template_id" required="required">
                            <option></option>
                            <?php foreach ($templates as $template) : ?>
                                <option value="<?= $template->id ?>"><?= Html::encode("{$template->name}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assigned_to_user_id">Benutzer wählen</label>
                        <select class="form-control" id="assigned_to_user_id" name="assigned_to_user_id" required="required">
                            <option></option>
                            <?php foreach ($userList as $user) : ?>
                                <option value="<?= $user->id ?>"><?= Html::encode("{$user->name}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Anfangsdatum</label>
                        <input class="form-control" type="date" id="start_date" name="start_date" style="height:auto;" value="">
                    </div>

                    <div class="form-group">
                        <label for="end_date">Enddatum</label>
                        <input class="form-control" type="date" id="end_date" name="end_date" style="height:auto;" value="">
                    </div>

                    <div class="form-group">
                        <label for="interval">Interval</label>
                        <input class="form-control" type="number" min="1" id="interval" name="interval" value="1" required="required">
                    </div>

                    <div class="form-group">
                        <label for="interval_unit">Interval Unit</label>
                        <select class="form-control" id="interval_unit" name="interval_unit" required="required">
                            <option value="day">Tag</option>
                            <option value="week">Woche</option>
                            <option value="month">Monat</option>
                            <option value="year">Jahr</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="foo" type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Hinzufügen</button>
                </div>
            </form>
        </div>
    </div>
</div>