<?php

/* @var $this yii\web\View */

/* @var $this yii\web\View */
use yii\helpers\Url;

$add_new_article_url = Yii::$app->params['baseUrl'].Url::to(['site/addnew']);

$this->title = Yii::$app->params['appName'];
$this->registerJsFile(Yii::$app->request->BaseUrl. '/js/logic.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="site-index">

    <div class="jumbotron">
        <?php if(isset($welcome) && $welcome == true){ ?>
    	<h1>Welcome</h1>
        <?php } else { ?>
        <h1>Latest news</h1>
        <?php } ?>
   </div>

    <div class="body-content">

    <!-- LIST WITH NEWS -->
        <?php 
        if(isset($articles) && count($articles)>0)   {
            foreach($articles as $article){

                    echo \app\components\ArticleItem::widget(['article'=>$article, 'show_delete'=>false]);
                    //echo "<br><br>";
                    //print_r($article);

            }
        } else {
            
            echo "You do not have any articles posted. To add new articles <a href=\"". $add_new_article_url ."\">Click Here</a>";
        }
       
        ?>
        

    </div>
</div>
