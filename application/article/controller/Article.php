<?php
namespace app\article\Controller;
use app\article\model\Article as ArticleModel;
use think\Controller;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/3/7
 * Time: 14:48
 */
class Article extends Controller
{

    public function createArticle(){
        $userId=$this->request->post('userId');
        $articleTitle=$this->request->post('articleTitle');
        $articleSummary=$this->request->post('articleSummary');
        $ArticleModel=new ArticleModel();
        return $ArticleModel->createArticle($userId,$articleTitle,$articleSummary);
    }

    public function writeArticleById(){
        $userId=$this->request->post('userId');
        $articleId=$this->request->post('articleId');
        $ArticleModel=new ArticleModel();
       return  $ArticleModel->getArticleEditable($userId,$articleId);
    }

    public function getRecommonArticleList(){

    }

}