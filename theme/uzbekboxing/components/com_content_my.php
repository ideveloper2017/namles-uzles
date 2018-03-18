<div class="pi-responsive-table-sm">
    <table class="pi-table pi-table-hovered pi-table-zebra">
        <thead>
        <tr>
            <th style="width: 50px;">
               Дата
            </th>
            <th>
                Статья
            </th>
            <th>
                Статус
            </th>
            <th>
                Раздел
            </th>
            <th>
                Действия
            </th>
        </tr>
             </thead>
        <tbody>
<?php foreach($articles as $article){?>
   <tr>
            <td>
                <?php echo $article['pubdate'];?>
            </td>
            <td>
               <i class="icon-newspaper"></i> <a href="/articles/<?php echo $article['seo'];?>"> <?php echo $article['title'];?></a>
            </td>
            <td>
                <?php echo $article['status'];?>
            </td>
            <td>
                <?php echo $article['category'];?>
            </td>
            <td>
               <a href="/articles/<?php echo $article['seo'];?>/<?php echo $article['id'];?>"><i class="icon-pencil"></i></a>
               <a href="/articles/<?php echo $article['seo'];?>"><i class="icon-trash"></i></a>
            </td>
        </tr>

<?php }?>

        </tbody>
        <!-- End table body -->

    </table>
    <div class="pi-pagenav pi-padding-bottom-40">
        <?php echo $pager->display_pages(); ?>
        </div>
</div>