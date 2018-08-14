<?php
/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/3/9
 * Time: 10:43
 */

namespace app\article\Controller;

use app\article\model\Paragraph as ParagraphModel;
use think\Controller;

class Paragraph extends Controller
{

    public function createParagraphById(){
        $userId=$this->request->post('userId');
        $articleId=$this->request->post('articleId');
        $paragraphContent=$this->request->post('paragrahpContent');
        $paragraphModel=new ParagraphModel();
        return $paragraphModel->createPragraphById($userId,$articleId,$paragraphContent);
    }


}