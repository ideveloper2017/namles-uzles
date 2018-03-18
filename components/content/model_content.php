<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 19.01.2016
 * Time: 15:46
 */
class model_content {

    public function __construct()
    {
        $this->inDB   = Registry::get("DataBase");
        $this->config = Registry::get("Core")->getComponentConfig('content');
        $this->langID =Lang::getLangID();
    }


    public function getArticle($id_or_link){
        if(is_numeric($id_or_link)){
            $where = "c.id = '$id_or_link'";
        } else {
            $where= "c.seo = '$id_or_link'";
        }

        if (!empty($id_or_link)) {
            $sql = "SELECT c.*,u.username as author,ct.cname as cat,ct.slug as catseo,ct.id as catid FROM content c
                                                                               left join users u on u.id=c.user_id
                                                                               left join categories_bind cb on cb.item_id=c.id
                                                                               left join categories ct on ct.id=cb.category_id
                                                                               left join languages l on l.flag=c.lang
                                                                             where $where and (c.lang='{$this->langID}')";
             $result = $this->inDB->query($sql);
            if (!$this->inDB->numrows($result)) {
                return false;
            }
            $article = $this->inDB->fetch($result);
        }
        return $article;
    }


}

?>