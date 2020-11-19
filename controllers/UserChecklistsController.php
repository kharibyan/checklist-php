<?php

namespace app\controllers;

use app\models\Checklist;
use app\models\ChecklistItem;
use app\models\UserTable;
use Yii;

class UserChecklistsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $checklists = Checklist::find()
            ->select(['checklist.*', 'user.name AS user_name'])
            ->joinWith('user')
            ->orderBy(['state_id' => SORT_ASC, 'user_name' => SORT_ASC, 'created_at' => SORT_ASC])
            ->all();

        $userList = UserTable::find()
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'checklists' => $checklists,
            'userList' => $userList,
        ]);
    }

    public function actionChecklistDetails($checklistId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklist = Checklist::find()
                ->select(['checklist.*', 'user.name AS user_name'])
                ->joinWith('user')
                ->where(['checklist.id' => $checklistId])
                ->one();

            if (!isset($checklist))
                return;

            $checklist->checklistItems = ChecklistItem::find()
                ->where(['checklist_id' => $checklistId])
                ->orderBy(['sortorder' => SORT_ASC])
                ->all();

            return $this->renderAjax('_checklist-details', [
                'checklist' => $checklist,
            ]);
        }
    }

    public function actionDeleteChecklist()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $checklistId = intval($request->post('id', 0));

            if ($checklistId <> 0) {
                try {

                    $checklist = Checklist::find()
                        ->where(['id' => $checklistId])
                        ->one();

                    $checklist->delete();

                    ChecklistItem::deleteAll(['checklist_id' => $checklistId]);

                    Yii::$app->session->setFlash('success', 'Das checklist wurde erfolgreich gelöscht');
                    return $this->redirect(['user-checklists/index']);
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Löschen eines checklists ist ein Fehler aufgetreten');
                }
            }
        }
    }
}
