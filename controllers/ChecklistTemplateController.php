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
                'only'  => ['index', 'checklist-template-details', 'checklist-item-template-details', 'assign-template', 'create-template'],
                'rules' => [
                    [
                        'actions' => ['index', 'checklist-template-details', 'checklist-item-template-details'],
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
        $checklistTemplates = $this->getChecklistTemplates();
        $userList = UserTable::find()
            ->all();

        return $this->render('index', [
            'checklistTemplates' => $checklistTemplates,
            'userList' => $userList,
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

    public function beforeAction($action)
    {
        if (in_array($action->id, ['assign-template', 'create-template'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionAssignTemplate()
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
            } catch (\yii\base\Exception $exception) {
                Yii::$app->session->setFlash('error', 'Beim Erstellen eines Templates ist ein Fehler aufgetreten');
            }

            return $this->redirect(['checklist-template/index']);
        }
    }

    /* public function actionFoo()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {

            $templateId = $request->post('selectTemplate');
            $name = $request->post('inputName');
            $assignToUserId = $request->post('selectUser');

            Yii::$app->session->setFlash('error', 'I DID IT!');

            Yii::$app->end();
        }
    } */

    /* public function actionAssignTemplate()
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

                    $items = [];
                    foreach ($itemTemplates as $itemTemplate) {
                        $i = new ChecklistItem();
                        $i->checklist_id = $checklist->id;
                        $i->sortorder = $itemTemplate->sortorder;
                        $i->item = $itemTemplate->item;
                        $i->owner_user_id = Yii::$app->user->identity->id;
                        array_push($items, $i);
                    }


                    foreach ($items as $item) {
                        $item->save();
                    }
                }

                echo true;
            } catch (\yii\base\Exception $exception) {
                echo false;
            }
        }
    } */
}
