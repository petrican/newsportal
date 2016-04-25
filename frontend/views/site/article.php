<?php

/* @var $this yii\web\View */

use app\models\Photo;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

$photo = new Photo();
// look for token in db
$photoObj = Photo::find()->where(['id' => $article->photo])->one();

$this->title = Yii::$app->params['appName'];
$this->registerJsFile(Yii::$app->request->BaseUrl. '/js/logic.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$pdf_article_url = Yii::$app->params['baseUrl'].Url::to(['site/pdf', 'id'=>$article->id]);
?>


<div class="site-index">

    <div class="body-content">
	     <!-- ARTICLE -->
        
        <div id="article-<?php echo $article->id; ?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row"><h1><?php echo  HtmlPurifier::process($article->title); ?></h1></div>
            <?php
            if(isset($pdflink) && $pdflink==false){
                // not showing link in PDF view mode
            } else {
            ?>
                <div class="row"><a href="<?php echo $pdf_article_url;  ?>">[View as PDF]</a></div>
            <?php } ?>
            <div class="row">
                
                <img src="uploads/<?php echo $photoObj->name.".".$photoObj->ext; ?>" style=" padding: 0px; margin-right: 20px; float: left;">
                
                <?php 
                    echo  nl2br(HtmlPurifier::process($article->body));
                ?>
                
            </div>
        </div>
        

    </div>
</div>