<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Checklist;
use app\models\ChecklistItem;
use app\models\History;

class ChecklistController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index', 'checklist-details', 'checklist-item-details'],
                'rules' => [
                    [
                        'actions' => ['index', 'checklist-details', 'checklist-item-details'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $checklists = $this->getChecklists(Yii::$app->user->identity->id);

        return $this->render('index', [
            'checklists' => $checklists,
        ]);
    }

    public function actionChecklistDetails($checklistId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklist = Checklist::find()
                ->where(['id' => $checklistId])
                ->andWhere(['assigned_to_user_id' => Yii::$app->user->identity->id]) // only visible for appropriate user
                ->one();

            $checklistItems = $this->getChecklistItems($checklistId);

            $ready = true;
            foreach ($checklistItems as $item) {
                $ready = $ready && ($item->state_id <> 1);
            }

            $checklist->ready = $ready;

            return $this->renderAjax('_checklist-details', [
                'checklist' => $checklist,
            ]);
        }
    }

    public function actionChecklistItemDetails($checklistItemId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklistItem = ChecklistItem::find()
                ->where(['id' => $checklistItemId])
                ->one();

            // check if user can view
            if (Checklist::find()
                ->where(['id' => $checklistItem->checklist_id])
                ->andWhere(['assigned_to_user_id' => Yii::$app->user->identity->id])
                ->one()
            ) {
                $checklistItemHistory = History::find()
                    ->where(['checklist_item_id' => $checklistItemId])
                    ->andWhere(['user_id' => Yii::$app->user->identity->id])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->all();

                return $this->renderAjax('_checklist-item-details', [
                    'checklistItem' => $checklistItem,
                    'checklistItemHistory' => $checklistItemHistory,
                ]);
            }
        }
    }

    private function getChecklists($userId)
    {
        $checklists = Checklist::find()
            ->where(['assigned_to_user_id' => $userId])
            ->andWhere(['<>', 'state_id', 2])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        foreach ($checklists as $checklist) {
            $checklist->checklistItems = $this->getChecklistItems($checklist->id);
        }

        return $checklists;
    }

    private function getChecklistItems($checklistId)
    {
        return ChecklistItem::find()
            ->where(['checklist_id' => $checklistId])
            ->orderBy(['sortorder' => SORT_ASC])
            ->all();
    }
}
