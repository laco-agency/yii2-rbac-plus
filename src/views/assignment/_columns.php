<?php

use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel \johnitvn\rbacplus\models\AssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$columns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => Yii::$app->getModule('rbac')->userModelIdField,
        'filter' => Html::activeTextInput($searchModel, 'id', ['class' => 'form-control']),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => Yii::$app->getModule('rbac')->userModelLoginField,
        'filter' => Html::activeTextInput($searchModel, 'login', ['class' => 'form-control']),
    ],
    [
        'label' => 'Roles',
        'content' => function ($model) {
            $authManager = Yii::$app->authManager;
            $idField = Yii::$app->getModule('rbac')->userModelIdField;
            $roles = [];
            foreach ($authManager->getRolesByUser($model->{$idField}) as $role) {
                $roles[] = $role->name;
            }
            if (count($roles) == 0) {
                return Yii::t("yii", "(not set)");
            } else {
                return implode(",", $roles);
            }

        }
    ],
];


$extraColumns = \Yii::$app->getModule('rbac')->userModelExtraDataColumns;
if ($extraColumns !== null) {
    // If extra columns exist merge and return
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'kartik\grid\ActionColumn',
    'template' => '{update}',
    'header' => Yii::t('rbac', 'Assignment'),
    'dropdown' => false,
    'vAlign' => 'middle',
    'urlCreator' => function ($action, $model, $key, $index) {
        return Url::to(['assignment', 'id' => $key]);
    },
    'updateOptions' => ['role' => 'modal-remote', 'title' => Yii::t('rbac', 'Update'), 'data-toggle' => 'tooltip'],
];

return $columns;