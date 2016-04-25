<?php
namespace frontend\controllers;
use Yii;





namespace app\components;
use Yii;
use app\components\ArticleItem;


/* @var $this yii\web\View */
use yii\helpers\Url;

$this->registerJsFile(Yii::$app->request->BaseUrl. '/js/logic.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = Yii::$app->params['appName'];
$add_new_article_url = Yii::$app->params['baseUrl'].Url::to(['site/addnew']);
?>
<div class="site-index">

    <div class="jumbotron">
	
	<h1>My Articles</h1>

        <p><a class="btn btn-lg btn-success" href="<?php echo $add_new_article_url; ?> ">Add New Article</a></p>
    </div>

    <div class="body-content">
	<!-- LIST WITH NEWS -->
        <?php 
        if(isset($articles) && count($articles)>0)   {
            foreach($articles as $article){

                    echo \app\components\ArticleItem::widget(['article'=>$article, 'show_delete'=>true]);
                    //echo "<br><br>";
                    //print_r($article);

            }
        } else {
            echo "You do not have any articles posted. To add new articles <a href=\"". $add_new_article_url ."\">Click Here</a>";
        }
       
        ?>


    </div>
</div>
