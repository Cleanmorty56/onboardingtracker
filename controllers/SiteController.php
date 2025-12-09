<?php

namespace app\controllers;

use app\models\Category;
use app\models\Module;
use app\models\Progress;
use app\models\SignupForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup', 'login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;


        $isGuest = Yii::$app->user->isGuest;
        $isAdmin = !$isGuest && $user->role == 1;

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


        $completed = 0;
        $completedModules = [];
        $completedCount = 0;

        if (!$isGuest) {
            $completed = Progress::find()
                ->where(['user_id' => $user->id])
                ->count();

            $completedModules = Progress::find()
                ->where(['user_id' => $user->id])
                ->select('module_id')
                ->column();

            $completedCount = count($completedModules);
        }

        return $this->render('index', [
            'user' => $user,
            'modules' => $modules,
            'completed' => $completed,
            'completedCount' => $completedCount,
            'completedModules' => $completedModules,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'pagination' => $pagination,
            'isGuest' => $isGuest,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function actionComplete($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Пожалуйста, авторизуйтесь чтобы отметить модуль как пройденный.');
            return $this->redirect(['index']);
        }

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

    public function actionDelete($id)
    {
        $module = Module::findOne($id);

        if (!$module) {
            throw new NotFoundHttpException('Модуль не найден.');
        }

        $user = Yii::$app->user->identity;
        if (Yii::$app->user->isGuest || $user->role != 1) {
            Yii::$app->session->setFlash('error', 'У вас нет прав для удаления модулей.');
            return $this->redirect(['index']);
        }

        if ($module->delete()) {
            Yii::$app->session->setFlash('success', 'Модуль успешно удален.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении модуля.');
        }

        return $this->redirect(['index']);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Спасибо за регистрацию!');
            return $this->goHome();
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }


}
