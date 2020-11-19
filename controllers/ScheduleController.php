<?php

namespace app\controllers;

use app\models\Checklist;
use app\models\ChecklistItem;
use app\models\ChecklistItemTemplate;
use app\models\ChecklistSchedule;
use app\models\ChecklistTemplate;
use app\models\UserTable;
use Yii;

class ScheduleController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, [
            'create-schedule',
        ])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $scheduleList = ChecklistSchedule::find()
            ->select(['checklist_schedule.*', 't.name AS template_name', 'o.name AS owner_name', 'a.name AS assigned_name'])
            ->joinWith('template t')
            ->joinWith('owner o')
            ->joinWith('assigned a')
            ->orderBy(['last_schedule' => SORT_ASC])
            ->all();

        $userList = UserTable::find()
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $templates = ChecklistTemplate::find()->all();

        return $this->render('index', [
            'scheduleList' => $scheduleList,
            'userList' => $userList,
            'templates' => $templates,
        ]);
    }

    public function actionCreateSchedule()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id'));
            $assignToUserId = intval($request->post('assigned_to_user_id'));

            $startDate = $request->post('start_date', null);
            if (isset($startDate)) {
                $startDate = new \DateTime('start_date');
                $startDate = $startDate->format('Y-m-d');
            }

            return $startDate;

            $endDate = $request->post('end_date', null);
            if (isset($endDate)) {
                $endDate = new \DateTime('end_date');
                $endDate = $endDate->format('Y-m-d');
            }

            $interval = intval($request->post('interval'));
            $intervalUnit = $request->post('interval_unit');

            try {

                $schedule = new ChecklistSchedule();
                $schedule->checklist_template_id = $templateId;
                $schedule->owner_user_id = Yii::$app->user->identity->id;
                $schedule->assign_to_user_id = $assignToUserId;

                if (isset($startDate)) {
                    $schedule->start_date = $startDate;
                }

                if (isset($endDate)) {
                    $schedule->end_date = $endDate;
                }

                $schedule->interval_count = $interval;
                $schedule->interval_unit = $intervalUnit;

                if ($schedule->save()) {
                    if (!isset($startDate)) {
                        $checklist = new Checklist();
                        $checklist->assigned_to_user_id = $assignToUserId;
                        $checklist->checklist_template_id = $templateId;
                        $checklist->name = ChecklistTemplate::find()->where(['id' => $templateId])->one()->name;
                        $checklist->owner_user_id = Yii::$app->user->identity->id;


                        if ($checklist->save()) {
                            $itemTemplates = ChecklistItemTemplate::find()
                                ->where(['checklist_template_id' => $templateId])
                                ->all();

                            foreach ($itemTemplates as $itemTemplate) {
                                $i = new ChecklistItem();
                                $i->checklist_id = $checklist->id;
                                $i->sortorder = $itemTemplate->sortorder;
                                $i->item = $itemTemplate->item;
                                $i->owner_user_id = Yii::$app->user->identity->id;
                                $i->save();
                            }
                        }
                    }
                }

                Yii::$app->session->setFlash('success', 'Die Checkliste wurde erfolgreich zugewiesen');
            } catch (\yii\base\Exception $exception) {
                Yii::$app->session->setFlash('error', 'Beim Zuweisen einer Checkliste ist ein Fehler aufgetreten');
            }

            return $this->redirect(['schedule/index']);
        }
    }
}
