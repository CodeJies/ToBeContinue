<?php
namespace app\article\model;
use think\Model;
use app\article\model\Article as ArticleModel;
/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/3/7
 * Time: 14:52
 */
class Article extends Model
{

    protected  function createArticle($userId,$articleTitle,$articleSummary){
        $model=new ArticleModel();
        if(isset($userId)&&isset($articleTitle)&&isset($articleSummary)){
            $model->owner_id=$userId;
            $model->article_create_date=date("Y-m-d h:i:sa");
            $model->article_summary=$articleSummary;
            $model->article_title=$articleTitle;
            if($model->save()){
                $success=array('msg'=>'生成文章成功');
                return jsonString($success);
            }else{
                return jsonString(null,'生成文章失敗');
            }
        }else{
            return jsonString(null,'入参不对');
        }
    }


    public function getArticleEditable($userId,$articleId){
        if(isset($userId)&&isset($articleId)){
            $articleInfo=$this->getArticleInfo($articleId);
            if($articleInfo->article_editable){
                $articleInfo->article_editable=0;
                $articleInfo->article_writer_id=$userId;
//                return jsonString($articleInfo,'获取续写权限失败1');
                if(false!==$articleInfo->save()){
                 return jsonString(array(['msg'=>'获取续写权限成功']));
                }else{
                    return jsonString(null,'获取续写权限失败');
                }
            }else{
                return jsonString($articleInfo,'该文章已经有人在续写啦');
            }
        }
    }




    public function getArticleInfo($articleId){
        $articleInfo=ArticleModel::get(['id'=>$articleId]);
       return $articleInfo;
    }

}
