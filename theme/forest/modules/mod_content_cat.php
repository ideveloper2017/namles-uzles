<ul>
    <?php

    $NSLevel=1;
    $last_level=-1;

    foreach ($cats as $items){

    if ($NSLevel < $last_level) {
        $tail=$NSLevel-$last_level;
        for($i=0;$i<$tail;$i++){
            ?>
              </ul></li>
            <?php
        }
    }
    if ($items['parent_id'] == 1){
   ?>
        <li>
            <a href="{$item.seolink}">{$item.title}</a>
        </li>

        <?php
    }else{
    ?>
     <li>
            <a href="{$item.seolink}">{$item.title}</a>
         <ul>
    <?php
    }
        $last_level=$NSLevel;
    }
    ?>
</ul>

        <!--<ul>-->
        <!--    {foreach key=key item=item from=$items}-->
        <!--    {if $item.NSLevel < $last_level}-->
        <!--    {math equation="x - y" x=$last_level y=$item.NSLevel assign="tail"}-->
        <!--    {section name=foo start=0 loop=$tail step=1}-->
        <!--</ul></li>-->
        <!--{/section}-->
        <!--{/if}-->
        <!--{if $item.NSRight - $item.NSLeft == 1}-->
        <!--<li>-->
        <!--    <a href="{$item.seolink}">{$item.title}</a>-->
        <!--</li>-->
        <!--{else}-->
        <!--<li>-->
        <!--    <a href="/{$item.seolink}">{$item.title}</a>-->
        <!---->
        <!--    <ul>-->
        <!--        {/if}-->
        <!--        {assign var="last_level" value=$item.NSLevel}-->
        <!---->
        <!--        {/foreach}-->
        <!--    </ul>-->
