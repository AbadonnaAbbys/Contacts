<?php
namespace frontend\controllers;

use common\models\Contact;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup', 'index', 'createContact', 'deleteContact', 'updateContact', 'uploadImage', 'deleteImage'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'createContact', 'deleteContact', 'updateContact', 'uploadImage', 'deleteImage'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['site/login']);
                }
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        if (!Yii::$app->user->id) {
            return $this->redirect(['site/login']);
        }

        $query = Contact::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name');
        $contactsProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['itemsPerPage'],
            ],
        ]);

        $formModel = new ContactForm();

        return $this->render('index', ['contactsProvider' => $contactsProvider, 'formModel' => $formModel]);
    }

    /**
     * @return string
     */
    public function actionCreateContact()
    {
        $formModel = new ContactForm();
        $success = false;

        if (Yii::$app->request->isAjax) {
            /** @var Contact $contact */
            $contact = new Contact();
            $contact->load(Yii::$app->request->post(), 'ContactForm');
            $contact->user_id = Yii::$app->user->id;
            if ($contact->validate()) {
                $success = $contact->save(false);
            } else {
                $formModel->setAttributes($contact->getAttributes());
                $success = false;
            }
        }
        return $this->asJson([
            'success' => $success,
            'html' => $this->renderPartial('contactForm', ['model' => $formModel]),
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDeleteContact()
    {
        $error = '';

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                /** @var Contact $contact */
                $contact = (new Contact())::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
                if ($contact) {
                    try {
                        $contact->delete();
                    } catch (StaleObjectException $e) {
                        $error = 'Can\'t delete';
                    } catch (\Throwable $e) {
                        $error = 'Can\'t delete';
                    }
                }
            } else {
                $error = 'ID not set';
            }
        }
        return $this->asJson([
            'success' => empty($error),
            'error' => $error,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

//    /**
//     * Displays contact page.
//     *
//     * @return mixed
//     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
//                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
//            } else {
//                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
//            }
//
//            return $this->refresh();
//        } else {
//            return $this->render('contact', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
