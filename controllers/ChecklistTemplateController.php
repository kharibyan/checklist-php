<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\ChecklistTemplate;
use app\models\ChecklistItemTemplate;
use app\models\UserTable;
use app\models\Checklist;
use app\models\ChecklistItem;

class ChecklistTemplateController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => [
                    'index',
                    'checklist-template-details',
                    'checklist-item-template-details',
                    'assign-template',
                    'create-template',
                    'create-item',
                    'item-move-up',
                    'item-move-down',
                    'delete-item',
                    'delete-template',
                    'save-template-changes',
                    'save-item-changes'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'checklist-template-details',
                            'checklist-item-template-details',
                            'assign-template',
                            'create-template',
                            'create-item',
                            'item-move-up',
                            'item-move-down',
                            'delete-item',
                            'delete-template',
                            'save-template-changes',
                            'save-item-changes'
                        ],
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
            'create-template',
            'save-template-changes',
            'save-item-changes'
        ])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($currentChecklist = null, $currentItem = null)
    {
        $checklistTemplates = $this->getChecklistTemplates();
        $userList = UserTable::find()
            ->all();

        return $this->render('index', [
            'checklistTemplates' => $checklistTemplates,
            'userList' => $userList,
            'currentChecklist' => $currentChecklist,
            'currentItem' => $currentItem,
        ]);
    }

    public function actionChecklistTemplateDetails($checklistTemplateId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklistTemplate = ChecklistTemplate::find()
                ->where(['id' => $checklistTemplateId])
                ->one();

            return $this->renderAjax('_checklist-template-details', [
                'checklistTemplate' => $checklistTemplate,
            ]);
        }
    }

    public function actionChecklistItemTemplateDetails($checklistItemTemplateId)
    {
        if (\Yii::$app->request->isAjax) {
            $checklistItemTemplate = ChecklistItemTemplate::find()
                ->where(['id' => $checklistItemTemplateId])
                ->one();

            return $this->renderAjax('_checklist-item-template-details', [
                'checklistItemTemplate' => $checklistItemTemplate,
            ]);
        }
    }

    private function getChecklistTemplates()
    {
        $checklistTemplates = ChecklistTemplate::find()->all();

        foreach ($checklistTemplates as $template) {
            $template->checklistItemTemplates = $this->getChecklistItemTemplates($template->id);
        }

        return $checklistTemplates;
    }

    private function getChecklistItemTemplates($checklistTemplateId)
    {
        return ChecklistItemTemplate::find()
            ->where(['checklist_template_id' => $checklistTemplateId])
            ->orderBy(['sortorder' => SORT_ASC])
            ->all();
    }

    /* public function actionAssignTemplate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id'));
            $name = $request->post('name');    // echo $_POST["inputName"];
            $assignToUserId = intval($request->post('assigned_to_user_id'));

            try {
                $checklist = new Checklist();
                $checklist->assigned_to_user_id = $assignToUserId;
                $checklist->checklist_template_id = $templateId;
                $checklist->name = $name;
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

                Yii::$app->session->setFlash('success', 'Die Checkliste wurde erfolgreich zugewiesen');
            } catch (\yii\base\Exception $exception) {
                Yii::$app->session->setFlash('error', 'Beim Zuweisen einer Checkliste ist ein Fehler aufgetreten');
            }

            return $this->redirect(['checklist-template/index']);
        }
    } */

    public function actionAssignTemplate()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $templateId = intval($request->post('checklist_template_id'));
            $name = $request->post('name');    // echo $_POST["inputName"];
            $assignToUserId = intval($request->post('assigned_to_user_id'));

            try {
                $checklist = new Checklist();
                $checklist->assigned_to_user_id = $assignToUserId;
                $checklist->checklist_template_id = $templateId;
                $checklist->name = $name;
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

                echo true;
            } catch (\yii\base\Exception $exception) {
                echo false;
            }
        }
    }

    public function actionCreateTemplate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id', 0));
            $name = $request->post('name');

            try {
                $template = new ChecklistTemplate();
                $template->name = $name;
                $template->owner_user_id = Yii::$app->user->identity->id;

                if ($template->save() && $templateId) {
                    $itemTemplates = ChecklistItemTemplate::find()
                        ->where(['checklist_template_id' => $templateId])
                        ->all();

                    foreach ($itemTemplates as $itemTemplate) {
                        $i = new ChecklistItemTemplate();
                        $i->checklist_template_id = $template->id;
                        $i->sortorder = $itemTemplate->sortorder;
                        $i->item = $itemTemplate->item;
                        $i->owner_user_id = Yii::$app->user->identity->id;

                        $i->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Ein neues Template wurde erfolgreich erstellt');
                return $this->redirect(['checklist-template/' . $template->id]);
            } catch (\yii\base\Exception $exception) {
                Yii::$app->session->setFlash('error', 'Beim Erstellen eines Templates ist ein Fehler aufgetreten');
            }

            return $this->redirect(['checklist-template/index']);
        }
    }

    public function actionCreateItem()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id', 0));

            if ($templateId <> 0) {
                $name = $request->post('name');

                try {

                    $item = new ChecklistItemTemplate();
                    $item->checklist_template_id = $templateId;
                    $maxSortorder = ChecklistItemTemplate::find()
                        ->where(['checklist_template_id' => $templateId])
                        ->max('sortorder');
                    if (!isset($maxSortorder)) {
                        $item->sortorder = 1;
                    } else {
                        $item->sortorder = $maxSortorder + 1;
                    }
                    $item->item = $name;
                    $item->owner_user_id = Yii::$app->user->identity->id;

                    if ($item->save()) {
                        Yii::$app->session->setFlash('success', 'Ein neues Item wurde erfolgreich erstellt');
                        return $this->redirect(['checklist-template/' . $templateId . '/' . $item->id]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Erstellen eines Items ist ein Fehler aufgetreten');
                }
            }

            return $this->redirect(['checklist-template/index']);
        }
    }

    public function actionItemMoveUp()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            /* $templateId = intval($request->post('checklist_template_id', 0)); */
            $itemId = intval($request->post('item_template_id', 0));

            if ($itemId <> 0) {
                try {
                    $item1 = ChecklistItemTemplate::find()
                        ->where(['id' => $itemId])
                        ->one();

                    if ($item1->sortorder > 1) {

                        $item2 = ChecklistItemTemplate::find()
                            ->where(['checklist_template_id' => $item1->checklist_template_id])
                            ->andWhere(['sortorder' => $item1->sortorder - 1])
                            ->one();

                        $item1->sortorder = $item1->sortorder - 1;
                        $item2->sortorder = $item2->sortorder + 1;
                        if ($item1->save() && $item2->save()) {
                            Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich nach oben geschoben');
                            return $this->redirect(['checklist-template/' . $item1->checklist_template_id . '/' . $itemId]);
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
            $itemId = intval($request->post('item_template_id', 0));

            if ($itemId <> 0) {
                try {
                    $item1 = ChecklistItemTemplate::find()
                        ->where(['id' => $itemId])
                        ->one();

                    $maxSortorder = ChecklistItemTemplate::find()
                        ->where(['checklist_template_id' => $item1->checklist_template_id])
                        ->max('sortorder');

                    if ($item1->sortorder < $maxSortorder) {

                        $item2 = ChecklistItemTemplate::find()
                            ->where(['checklist_template_id' => $item1->checklist_template_id])
                            ->andWhere(['sortorder' => $item1->sortorder + 1])
                            ->one();

                        $item1->sortorder = $item1->sortorder + 1;
                        $item2->sortorder = $item2->sortorder - 1;
                        if ($item1->save() && $item2->save()) {
                            Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich nach unten geschoben');
                            return $this->redirect(['checklist-template/' . $item1->checklist_template_id . '/' . $itemId]);
                        }
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Verschieben eines Items ist ein Fehler aufgetreten');
                }
            }
        }
    }

    public function actionDeleteItem()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $itemId = intval($request->post('item_template_id', 0));

            if ($itemId <> 0) {
                try {
                    $item = ChecklistItemTemplate::find()
                        ->where(['id' => $itemId])
                        ->one();

                    $templateId = $item->checklist_template_id;

                    $remainingItems = ChecklistItemTemplate::find()
                        ->where(['checklist_template_id' => $item->checklist_template_id])
                        ->andWhere(['>', 'sortorder', $item->sortorder])
                        ->all();

                    if (isset($remainingItems)) {
                        foreach ($remainingItems as $i) {
                            $i->sortorder = $i->sortorder - 1;
                            $i->save();
                        }
                    }

                    $item->delete();

                    Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich gelöscht');
                    return $this->redirect(['checklist-template/' . $templateId]);
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Löschen eines Items ist ein Fehler aufgetreten');
                }
            }
        }
    }

    public function actionDeleteTemplate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id', 0));

            if ($templateId <> 0) {
                try {

                    $template = ChecklistTemplate::find()
                        ->where(['id' => $templateId])
                        ->one();

                    $template->delete();

                    ChecklistItemTemplate::deleteAll(['checklist_template_id' => $templateId]);

                    Yii::$app->session->setFlash('success', 'Das Template wurde erfolgreich gelöscht');
                    return $this->redirect(['checklist-template/index']);
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Löschen eines templates ist ein Fehler aufgetreten');
                }
            }
        }
    }

    public function actionSaveTemplateChanges()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $templateId = intval($request->post('checklist_template_id', 0));
            $templateName = $request->post('name');

            if ($templateId <> 0) {
                try {

                    $template = ChecklistTemplate::find()
                        ->where(['id' => $templateId])
                        ->one();

                    $template->name = $templateName;

                    if ($template->save()) {
                        Yii::$app->session->setFlash('success', 'Der Template-Name wurde erfolgreich geändert');
                        return $this->redirect(['checklist-template/' . $templateId]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Ändern des Namens ist ein Fehler aufgetreten');
                }
            }

            return $this->redirect(['checklist-template/index']);
        }
    }
    public function actionSaveItemChanges()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $itemId = intval($request->post('item_template_id', 0));
            $itemItem = $request->post('item');

            if ($itemId <> 0) {
                try {

                    $item = ChecklistItemTemplate::find()
                        ->where(['id' => $itemId])
                        ->one();

                    $item->item = $itemItem;

                    if ($item->save()) {
                        Yii::$app->session->setFlash('success', 'Das Item wurde erfolgreich geändert');
                        return $this->redirect(['checklist-template/' . $item->checklist_template_id . '/' . $itemId]);
                    }
                } catch (\yii\base\Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Beim Ändern des Items ist ein Fehler aufgetreten');
                }
            }
            return $this->redirect(['checklist-template/index']);
        }
    }
}
