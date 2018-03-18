<?php
/**
 * Created by PhpStorm.
 * User: iDevelopmen
 * Date: 22.12.2015
 * Time: 19:18
 */
class Comments{

    public $target;
    public $target_id = 0;
    public $commentcount;
    public $userid;


    public function __construct($target = '', $target_id = 0)
    {
        $this->target=$target;
        $this->target_id=$target_id;
    }


    public function getComments(){

        $q = "SELECT COUNT(*) FROM comments";

        $record = Registry::get("DataBase")->query($q);
        $total = Registry::get("DataBase")->fetchrow($record);
        $counter = $total[0];
        $pager=Registry::get("Paginator");
        $pager->items_total = $counter;
        $pager->default_ipp = 5;
        $pager->paginate();
        $sql = "select cm.*,up.lname as author,up.avatar from comments cm
                              inner join users u on u.id=cm.user_id
                              inner join user_profiles up on up.user_id=u.id
                              where cm.active=1 order by cm.created_at asc

              " . $pager->limit;


        $row = Registry::get("DataBase")->fetch_all($sql);
        return ($row) ? $row : 0;
    }

    public function getCommentsTree($parent_id, $level, $comments, &$tree)
    {
        $level++;
        foreach ($comments as $key => $comment) {
            if ($comment['parent_id'] == $parent_id) {
                $comment['level'] = $level - 1;
                $tree[] = $comment;
                self::getCommentsTree($comment['id'], $level, $comments, $tree);
            }
        }
    }
}
?>