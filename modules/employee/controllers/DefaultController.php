<?php

namespace app\modules\employee\controllers;

use app\models\Category;
use app\models\Module;
use app\models\PasswordChangeForm;
use app\models\ProfileUpdateForm;
use app\models\Progress;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `employee` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'update'],
                'rules' => [
                    [
                        'actions' => ['index', ''],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->role === 0;
                        },
                        'denyCallback' => function ($rule, $action) {
                            return Yii::$app->response->redirect(['site/login']);
                        }
                    ],
                ],
            ],
        ];
    }


    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        $categoryId = Yii::$app->request->get('category_id');

        $query = Module::find();
        if ($categoryId) {
            $query->where(['category_id' => $categoryId]);
        }

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 6,
            'pageSizeParam' => false,
            'forcePageParam' => false,
        ]);

        $modules = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $categories = Category::find()->all();

        $completed = Progress::find()
            ->where(['user_id' => $user->id])
            ->count();

        $completedModules = Progress::find()
            ->where(['user_id' => $user->id])
            ->select('module_id')
            ->column();

        $completedCount = count($completedModules);

        return $this->render('index', [
            'user' => $user,
            'modules' => $modules,
            'completed' => $completed,
            'completedCount' => $completedCount,
            'completedModules' => $completedModules,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'pagination' => $pagination,
        ]);
    }

    public function actionComplete($id)
    {
        $userId = Yii::$app->user->id;
        $exists = Progress::find()
            ->where(['user_id' => $userId, 'module_id' => $id])
            ->exists();

        if (!$exists) {
            $progress = new Progress();
            $progress->user_id = $userId;
            $progress->module_id = $id;
            $progress->completed_at = date('Y-m-d H:i:s');
            $progress->save();

            Yii::$app->session->setFlash('success', 'Модуль отмечен как пройденный!');
        } else {
            Yii::$app->session->setFlash('info', 'Этот модуль уже пройден');
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateemail()
    {
        $model = new \app\models\ProfileUpdateForm();
        $model->user = Yii::$app->user->identity;

        if ($model->user === null) {
            return $this->redirect(['site/login']);
        }

        $model->email = $model->user->email;

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            Yii::$app->session->setFlash('success', 'Email успешно изменен');
            return $this->redirect(['index']);
        }

        return $this->render('updateemail', [
            'model' => $model,
        ]);
    }

    public function actionPass()
    {
        $user = User::findOne(Yii::$app->user->identity->getId());
        $model = new PasswordChangeForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $user->validatePassword($model->oldPassword)) {
                if ($model->changePassword()) {
                    Yii::$app->session->setFlash('success', 'Пароль успешно обновлен');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('pass', ['model' => $model]);
    }
}
