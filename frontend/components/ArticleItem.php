<?php

namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;


class ArticleItem extends Widget{
	public $article;
	public $show_delete;
	
	public function init(){
                // your logic here
				
		parent::init();

	}
	public function run(){
                 // you can load & return the view or you can return the output variable
		return $this->render('ArticleItem',['article' => $this->article,  'inst'=>$this]);
	}


	public function truncate($string, $length, $dots = "...") {
    	return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
	}

}
?>
