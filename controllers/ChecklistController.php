<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Checklist;
use app\models\ChecklistTemplate;
use app\models\ChecklistItem;
use app\models\ChecklistItemTemplate;
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

    public function beforeAction($action)
    {
        if (in_array($action->id, [
            'create-item',
            'create-checklist',
            'save-checklist-changes',
            'save-item-changes'
        ])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($currentChecklist = null, $currentItem = null)
    {
        $checklists = $this->getChecklists(Yii::$app->user->identity->id);
        $checklistTemplates = ChecklistTemplate::find()
            ->all();

        return $this->render('index', [
            'checklists' => $checklists,
            'checklistTemplates' => $checklistTemplates,
            'currentChecklist' => $currentChecklist,
            'currentItem' => $currentItem,
        ]);
    }

    public function actionChecklistDetails($checklistId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklist = Checklist::find()
                ->where(['id' => $checklistId])
                ->andWhere(['assigned_to_user_id' => Yii::$app->user->identity->id]) // only visible for appropriate user
                ->andWhere(['<>', 'state_id', 2])
                ->one();

            if (!isset($checklist))
                return;

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

    public function actionCreateChecklist()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id', 0));
            $name = $request->post('name');

            try {

                $checklist = new Checklist();
                $checklist->name = $name;
                $checklist->assigned_to_user_id = Yii::$app->user->identity->id;
                $checklist->checklist_template_id = $templateId;
                $checklist->owner_user_id = Yii::$app->user->identity->id;
                $checklist->save();

                if ($templateId <> 0) {
                    $itemTemplates = ChecklistItemTemplate::find()
                        ->where(['checklist_template_id' => $templateId])
                        ->all();

                    foreach ($itemTemplates as $itemTemplate) {
                        $item = new ChecklistItem();
                        $item->checklist_id = $checklist->id;
                        $item->sortorder = $itemTemplate->sortorder;
                        $item->item = $itemTemplate->item;
                        $item->owner_user_id = $itemTemplate->owner_user_id;
                        $item->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Ein neue Checkliste wurde erfolgreich erstellt');
                return $this->redirect(['checklist/' . $checklist->id]);
            } catch (\yii\base\Exception $exception) {
                Yii::$app->session->setFlash('error', 'Beim Erstellen einer Checkliste ist ein Fehler aufgetreten');
            }

            Yii::$app->session->setFlash('error', 'Beim Erstellen einer Checkliste ist ein Fehler aufgetreten');
            return $this->redirect(['checklist-template/index']);
        }
    }

    public function actionCreateItem()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $checklistId = intval($request->post('checklist_id', 0));

            if ($checklistId <> 0) {
                $name = $request->post('name');

                try {

                    $item = new ChecklistItem();
                    $item->checklist_id = $checklistId;
                    $item->item = $name;
                    $item->owner_user_id = Yii::$app->user->identity->id;

                    $maxSortorder = ChecklistItem::find()
                        ->where(['checklist_id' => $checklistId])
                        ->max('sortorder');

                    if (!isset($maxSortorder)) {
                        $item->sortorder = 1;
                    } else {
                        $item->sortorder = $maxSortorder + 1;
                    }

                    if ($item->save()) {
                        Yii::$app->session->setFlash('success', 'Ein neues Item wurde erfolgreich erstellt');
                        return $this->redirect(['checklist/' . $checklistId . '/' . $item->id]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Erstellen eines Items ist ein Fehler aufgetreten');
                }
            }

            Yii::$app->session->setFlash('error', 'Beim Erstellen eines Items ist ein Fehler aufgetreten');
            return $this->redirect(['checklist/index']);
        }
    }

    public function actionItemMoveUp()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id = intval($request->post('id', 0));

            if ($id <> 0) {
                try {
                    $item1 = ChecklistItem::find()
                        ->where(['id' => $id])
                        ->one();

                    if ($item1->sortorder > 1) {

                        $item2 = ChecklistItem::find()
                            ->where(['checklist_id' => $item1->checklist_id])
                            ->andWhere(['sortorder' => $item1->sortorder - 1])
                            ->one();

                        $item1->sortorder = $item1->sortorder - 1;
                        $item2->sortorder = $item2->sortorder + 1;
                        if ($item1->save() && $item2->save()) {
                            Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich nach oben geschoben');
                            return $this->redirect(['checklist/' . $item1->checklist_id . '/' . $id]);
                        }
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Verschieben eines Items ist ein Fehler aufgetreten');
                }
            }
        }
    }

    public function actionItemMoveDown()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id = intval($request->post('id', 0));

            if ($id <> 0) {
                try {
                    $item1 = ChecklistItem::find()
                        ->where(['id' => $id])
                        ->one();

                    $maxSortorder = ChecklistItem::find()
                        ->where(['checklist_id' => $item1->checklist_id])
                        ->max('sortorder');

                    if ($item1->sortorder < $maxSortorder) {

                        $item2 = ChecklistItem::find()
                            ->where(['checklist_id' => $item1->checklist_id])
                            ->andWhere(['sortorder' => $item1->sortorder + 1])
                            ->one();

                        $item1->sortorder = $item1->sortorder + 1;
                        $item2->sortorder = $item2->sortorder - 1;
                        if ($item1->save() && $item2->save()) {
                            Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich nach unten geschoben');
                            return $this->redirect(['checklist/' . $item1->checklist_id . '/' . $id]);
                        }
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Verschieben eines Items ist ein Fehler aufgetreten');
                }
            }
        }
    }

    public function actionSaveChecklistChanges()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id = intval($request->post('id', 0));
            $stateId = $request->post('state_id');
            $comment = $request->post('comment');

            if (empty($comment)) {
                $comment = null;
            }

            if ($id <> 0) {
                try {

                    $checklist = Checklist::find()
                        ->where(['id' => $id])
                        ->one();

                    $now = new \DateTime('now');
                    $now = $now->format('Y-m-d H:i:s');

                    $checklist->last_change = $now;
                    $checklist->state_id = $stateId;
                    $checklist->comment = $comment;

                    if ($checklist->save()) {
                        Yii::$app->session->setFlash('success', 'Die Änderungen wurden erfolgreich gespeichert');
                        return $this->redirect(['checklist/' . $checklist->id]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Es ist ein Fehler aufgetreten');
                }
            }

            Yii::$app->session->setFlash('error', 'Es ist ein Fehler aufgetreten');
            return $this->redirect(['checklist/index']);
        }
    }

    public function actionSaveItemChanges()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $itemId = intval($request->post('id', 0));
            $newStateId = $request->post('state_id');
            $itemComment = $request->post('comment');

            if (empty($itemComment)) {
                $itemComment = null;
            }


            if ($itemId <> 0) {
                try {

                    $item = ChecklistItem::find()
                        ->where(['id' => $itemId])
                        ->one();

                    $oldStateId = $item->state_id;

                    $item->state_id = $newStateId;

                    $now = new \DateTime('now');
                    $now = $now->format('Y-m-d H:i:s');

                    $item->last_change = $now;

                    $item->comment = $itemComment;

                    if ($item->save()) {

                        $history = new History();
                        $history->created_at = $now;
                        $history->checklist_item_id = $itemId;
                        $history->user_id = Yii::$app->user->identity->id;
                        $history->state_id_old = $oldStateId;
                        $history->state_id_new = $newStateId;
                        $history->comment = $itemComment;

                        $history->save();

                        Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich geändert');
                        return $this->redirect(['checklist/' . $item->checklist_id . '/' . $itemId]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Ändern des Items ist ein Fehler aufgetreten');
                }
            }

            Yii::$app->session->setFlash('error', 'Beim Ändern des Items ist ein Fehler aufgetreten');
            return $this->redirect(['checklist/index']);
        }
    }
}
