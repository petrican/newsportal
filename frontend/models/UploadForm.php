<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Photo;
use app\models\Article;


class UploadForm extends Model
{
    /**
     *      * @var UploadedFile
     **/

    public $imageFile;
    public $articleTitle;
    public $articleContent;

    public function rules()
    {
        return [
            
            ['articleTitle', 'required'],
            ['articleTitle', 'string', 'min' => 10],
            
            ['articleContent', 'required'],
            ['articleContent', 'string', 'min' => 10],
            
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }
    
    public function upload()
    {
        //print_r($this->validate()); exit(); // debug testing validation


        if ($this->validate()) {
    	    // we create a random filename with same extension
    	    $fname = uniqid();
    	    
    	    $photo = new Photo();
    	    $photo->name = $fname;
    	    $photo->ext =  $this->imageFile->extension;
    	    $photo->created_at = (string)time();
    	    if($photo->save()){
                //$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
                $this->imageFile->saveAs('uploads/' . $fname . '.' . $this->imageFile->extension);
                // save the article as well
                $article = new Article();
                $article->title = $this->articleTitle;
                $article->body = $this->articleContent;
                $article->photo = $photo->id;
                $article->created_at = (string)time();
                $article->created_by = \Yii::$app->user->identity->id;
                $article->save();
                // redirect user back to my articles so he can see the added article
                //header("Location: ". \Yii::$app->urlManager->createUrl("site/myarticles"));
                return true;
            } else {
                return true;
            }
           
        } else {
            return false;
        }
    }
}
