<?php
/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/3/9
 * Time: 10:51
 */

namespace app\article\model;

use app\article\model\Paragraph as ParagraphModel;
use think\Model;

class Paragraph extends Model
{

    public function createPragraphById($userId,$articleId,$pragraphContent){
        $model=new ParagraphModel();
        $articleModel=new Article();
        $articleModel=$articleModel->getArticleInfo($articleId);
        if($articleModel->article_writer_id==$userId){
            $model->article_id=$articleId;
            $model->owner_id=$userId;
            $model->paragraph_content=$pragraphContent;
            $model->paragraph_number=$this->getArticleParagraphCountById($articleId)+1;
            if($model->save()){
                $articleModel->article_editable=0;
                $articleModel->article_writer_id='';
                $articleModel->save();
                return  jsonString($model,'编辑文章段落成功');
            }else{
                return  jsonString(null,'编辑文章段落失败');
            }
        }else{
            return jsonString(null,'你无权编辑这篇文章');
        }
    }


    public function getArticleParagraphCountById($articleId){
        $list = ParagraphModel::all(['article_id'=>$articleId]);
        $result=end($list);
        $count=0;
        if($result!=null){
            $count=$result->paragraph_number;
        }
        return $count;
    }



}