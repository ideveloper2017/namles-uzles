<div id="respond">
    <ul class="commentlist">
        <?php foreach($comrows as $comm):?>
            <li>
                <div class="comment">
                    <span class="avatar"><img src="/uploads/avatars/<?php echo $comm->avatar;?>" alt="comment avatar"></span>
                    <span class="comment-info"><?php echo $comm->created_at;?> <span><?php echo $comm->author?></span> </span>
                    <?php echo $comm->content?>
                                  <a href="#" class="comment-reply">reply</a>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
    <div class="pagination">
        <?php if ($pager->display_pages()) { ?>
            <?php echo $pager->display_pages(); ?>
        <?php } ?>
    </div>
</div>

<div class="comments-area">
    <div class="perfect-form-box">
        <h2 class="perfect-form-title">Comments <span>(3)</span></h2>
        <span class="perfect-form-write">write a comment</span>
        <form class="perfect-form">
            <input type="text" name="name" class="perfect-line">
            <span>name</span>
            <input type="text" name="email" class="perfect-line">
            <span>email</span>
            <input type="text" name="website" class="perfect-line">
            <span>category</span>
            <textarea name="message" class="perfect-area"></textarea>
            <span>message</span>
            <div class="clear"></div>
            <input type="submit" value="Write" class="perfect-button">
        </form>
    </div>

</div>

