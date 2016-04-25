<?php
namespace frontend\controllers;
use Yii;

use common\models\LoginForm;
use common\models\Article;  // we need the model for article listing



use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use common\models\User;   // Added by Peter
use zyx\phpmailer\Mailer;
use yii\helpers\Url;

use frontend\models\UploadForm;
use yii\web\UploadedFile;

use kartik\mpdf\Pdf;

use yii\data\BaseDataProvider;
use yii\data\ActiveDataProvider;

use yii\helpers\StringHelper;



/**
 * Site controller
 */
class SiteController extends Controller
{





    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
     * @return mixed
     */
    public function actionIndex()
    {

        $articles = Article::find()
                ->where(['status' => 1])
                ->orderBy('id DESC')
                ->limit(10)
                ->all();
        return $this->render('index', [ 'articles'=>$articles ]); 
    }
    
   /** 
    *   RSS feed generator
    */ 
   public function actionRss()
    {

        $dataProvider = new ActiveDataProvider([
             'query' => Article::find()->orderBy('id DESC'),
                'pagination' => [
                    'pageSize' => 10
                ],
        ]);

        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();

        $headers->set('Content-Type', 'application/rss+xml; charset=utf-8');
            echo \Zelenin\yii\extensions\Rss\RssView::widget([
        'dataProvider' => $dataProvider,
        'channel' => [
            'title' => Yii::$app->name . ' RSS Feed',
            'link' => Url::toRoute('/', true),
            'description' => 'Posts ',
            'language' => function ($widget, \Zelenin\Feed $feed) {
                return Yii::$app->language;
            },
            'image'=> function ($widget, \Zelenin\Feed $feed) {
                $feed->addChannelImage(Yii::$app->request->BaseUrl.'/images/channel.jpg', 'http://example.com', 88, 31, 'Image description');
            },
        ],
        'items' => [
            'title' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return $model->title;
                },
            'description' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return StringHelper::truncateWords($model->body, 50);
                  
                },
            'link' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return Url::toRoute(['site/article', 'id' => $model->id], true);
                    // return "http://localhost";
                },
            'author' => function ($model, $widget, \Zelenin\Feed $feed) {
                    //return $model->user->email . ' (' . $model->user->username . ')';
                    $user = User::find()->where(['id' => $model->created_by])->one();
                    if($user === null){
                        // bad token
                        return "Anonymous";
                      
                    } else {
                        return $user->username;
                    }
                },
            'guid' => function ($model, $widget, \Zelenin\Feed $feed) {
                    //$date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->created_at);
                    return Url::toRoute(['site/article', 'id' => $model->id], true) . ' ' . date('Y-m-d H:i:s', $model->created_at);

                },
            'pubDate' => function ($model, $widget, \Zelenin\Feed $feed) {
                    //$date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->created_at);
                    //return $date->format(DATE_RSS);
                    return date('Y-m-d H:i:s', $model->created_at);
                },


        ]
    ]);



    }


    /**
     *  PDF Generator
     */
    public function actionPdf() {
        $id = intval($_REQUEST['id']);
        $article = Article::find()->where(['id' => $id])->one();
        if($article===null){
            return $this->render('notfound');
        } else {

            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
            // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
            // A4 paper format
                'format' => Pdf::FORMAT_A4,
            // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
                'destination' => Pdf::DEST_BROWSER,
            // your html content input
                'content' => $this->renderPartial('article',['article' => $article, 'pdflink'=>false]), 
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
             // call mPDF methods on the fly
                'methods' => [
                'SetHeader'=>[Yii::$app->params['appName']],
                'SetFooter'=>['{PAGENO}'],
                ]
                ]);

        // http response
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');

        // return the pdf output as per the destination setting
            return $pdf->render(); 



        }




}











    /** 
     *   Action for adding new article
     */
    public function actionAddnew()
    {
        $model = new UploadForm();

        

        if (Yii::$app->request->isPost) {

            $request = Yii::$app->request;

           
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            $model->articleTitle =  $_POST['UploadForm']['articleTitle'];     
            $model->articleContent =  $_POST['UploadForm']['articleContent'];  
            
            if ($model->upload()) {
            
                // file is uploaded successfully
                // redirect user back to my articles so he can see the added article
                header("Location: ". \Yii::$app->urlManager->createUrl("site/myarticles"));
                exit();
            }
        }

        return $this->render('upload', ['model' => $model]);
    }






    
    /** 
     *  Action for listing own articles
     */
    public function actionMyarticles(){
	if (!\Yii::$app->user->isGuest) {
	    $articles = Article::find()
				->where(['created_by'=>\Yii::$app->user->identity->id,'status' => 1])
                ->orderBy('id DESC')
				->limit(10)
				->all();
    	    return $this->render('own_articles_list', [ 'articles'=>$articles ]); 
    	} else {
    	    return $this->goHome();
    	}
    }

    
    /**
     *  Activation action by Petrica Nanca <petrica_nanca@yahoo.com>
     *  - activates the account which corresponds to given token
     */
    public function actionActivate(){
       
       $request = Yii::$app->request;
       $token = $request->get('token');

       $user = new User();

	   // look for token in db
       $UsersFound = User::find()->where(['activate_token' => $token])->one();
       if($UsersFound === null){
	    // bad token
           return $this->render('badtoken');
       } else {
	    // if we have a match we activate the account - check also status to see if user is not activated already
           if($UsersFound->active_status == 1){
              return $this->render('badtoken');
          } else {
                try {
                    $model = new ResetPasswordForm($UsersFound->password_reset_token);
                } catch (InvalidParamException $e) {
                    throw new BadRequestHttpException($e->getMessage());
                }

                if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                    Yii::$app->session->setFlash('success', 'New password was saved.');
                    if($UsersFound===null){
	                //nothing  
    		        return $this->goHome();  
	            } else {
    	            // authenticating
	                if (Yii::$app->getUser()->login($UsersFound)) {
	            	    // get own articles and pass them to the view
    		            return $this->render('index', [ 'welcome'=>true  ]);// welcome the user
            		} else {
	                    return $this->goHome();
    		        }
	            }
                }

                return $this->render('resetPassword', [
                    'model' => $model, 'activate'=>true
                ]);
          }
      }
    } // end Activation Action
    
    











    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
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

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    



    /***
     *  Article display action
     */
    public function actionArticle(){
        $id = intval($_REQUEST['id']);
        $article = Article::find()->where(['id' => $id])->one();
        if($article===null){
       
            return $this->render('notfound');


        } else {
          
            return $this->render('article', [
                'article' => $article,
            ]);
        }
    }









    /**
     *   Delete article action
     */
    public function actionDeletearticle(){
        $id = $_REQUEST['id'];
        
        $res = array();
        $res['success'] = false;
        header('Content-Type: application/json');

        if(isset($_REQUEST['id'])){
            $id = intval($_REQUEST['id']);
            $article = Article::find()->where(['id' => $id, 'created_by'=>\Yii::$app->user->identity->id ])->one();
            if($article===null){
                $res['success'] = false;
            } else {
                $article->status = 0;
                if($article->save()){
                    $res['success'] = true;
                } else {
                    $res['success'] = false;
                }
            }
        }
        echo json_encode($res);
    
    }





    /**
     * Signs user up.
     *
     * @return mixed   - adapted by Peter <petrica_nanca@yahoo.com> such that It would send the confirmation email during signup process
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
            	    // we send the email with the link to the user here
            	    $activate_url = Yii::$app->params['baseUrl'].Url::to(['site/activate', 'token' => $user->activate_token]);
            	    
            	    Yii::$app->mail->compose()
                 ->setFrom(['noreply@newsportal.com' => Yii::$app->params['appName']])
                 ->setTo( $user->email )
                 ->setSubject('Welcome to '.Yii::$app->params['appName'])
                 ->setTextBody("Hi ".$user->username.", <br /><br> Welcome to ".Yii::$app->params['appName']." Site!<br><br /> To activate your account you need to click the following link:<br>". $activate_url."<br><br> Thanks,<br>".Yii::$app->params['appName']." Team")
                 ->send();

                  Yii::$app->user->logout(); 
                  return $this->render('thanksForRegistering');
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
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
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
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
            // load user and authenticate him


            
                       
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
