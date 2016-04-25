<?php
/**
 *  Article Item view - Petrica Nanca <petrica_nanca@yahoo.com>
 */
//print_r($article);
use yii\helpers\Url;
use app\models\Photo;
use common\models\User;
use yii\helpers\HtmlPurifier;

$photo = new Photo();
// look for token in db
$photoObj = Photo::find()->where(['id' => $article->photo])->one();

$u = new User();
// look for token in db
$userObj = User::find()->where(['id' => $article->created_by])->one();


$article_url = Yii::$app->params['baseUrl'].Url::to(['site/article','id'=>$article->id]);
$del_url = Yii::$app->params['baseUrl'].Url::to(['site/deletearticle']);

?>
		
        
            <div id="article-<?php echo $article->id; ?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="<?php echo $article_url; ?>">
                	<h4><?php echo  HtmlPurifier::process($article->title); ?></h4> 
                </a>
                <small><i>(<?php echo date('l jS \of F Y h:i:s A', $article->created_at); ?> by <?php echo $userObj->username; ?>)</i></small>
                <div style="float:left;">
                	<a href="<?php echo $article_url; ?>">
                		<img src="uploads/<?php echo $photoObj->name.".".$photoObj->ext; ?>" style="max-width: 90px; padding: 5px;">
                	</a>
                </div>

                <p><?php echo $inst->truncate( HtmlPurifier::process($article->body), 200); ?>

                <a class="btn btn-xs btn-default" href="<?php echo $article_url; ?>">View article &raquo;</a>
                <?php 
                // if owner we show the actions buttons
                if(isset(\Yii::$app->user->identity->id)){
                	$currUser = \Yii::$app->user->identity->id;
            	} else {
            		$currUser = 0;
            	}
                if($userObj->id == $currUser && $inst->show_delete){
                	?>
                	<button id="delete-<?php echo $article->id; ?>" onClick="DeleteArticle('<?php echo $article->id; ?>','<?php echo $del_url; ?>');" class="btn btn-xs btn-danger">Delete</button>
                	<?php
                }
                ?>
                </p>
            </div>
       		<div style="clear:both;"></div>