<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Bahrom
 * Date: 25.10.13
 * Time: 23:12
 * To change this template use File | Settings | File Templates.
 */
if(!defined('BPA_CMS')) { die('ACCESS DENIED'); }
function loadConfig(){
    $db=Registry::get("DataBase");
    $core=Registry::get("Core");
    //LOAD CURRENT CONFIG
    $sql = "SELECT config FROM components WHERE link = 'search'";
    $result = $db->query($sql) or die(mysql_error());

    if ( $db->num_rows($result)){
        $conf =  $db->fetch_assoc($result);
        if ($conf){
            $cfg = unserialize($conf['config']);
            //		print_r($cfg);
        }
    }

    return $cfg;

}

function pageBar($current, $perpage, $sql, $query, $look, $limitStr){
    $db=DB::getInstance();
    $ker=Engine::getInstance();
    $html = '';

    $sql = str_replace($limitStr, '', $sql);

    $result = $db->query($sql) or die(mysql_error());
    $records =$db->num_rows($result);

    if ($records){
        $pages = ceil($records / $perpage);

        if($pages>1){
            $html .= '<div class="pagebar">';
            $html .= '<span class="pagebar_title"><strong>Страницы: </strong></span>';
            for ($p=1; $p<=$pages; $p++){
                if ($p != $current) {

                    $link = '/index.php?view=search&query='.$query.'&look='.$look.'&menuid='.menuId().'&page='.$p;

                    $html .= ' <a href="'.$link.'" class="pagebar_page">'.$p.'</a> ';
                } else {
                    $html .= '<span class="pagebar_current">'.$p.'</span>';
                }
            }
            $html .= '</div>';
        }
    }
    return $html;
}

function search(){
    $db=Registry::get("DataBase");
    $core=Registry::get("Core");

    $GLOBALS['page_title'] = 'Поиск';

    if (isset($_REQUEST['query'])){ $query = $_REQUEST['query'];} else { $query = '';}
    if (isset($_REQUEST['look'])){ $look = $_REQUEST['look'];	} else { $look = 'anyword'; }
    $cfg =$core->getComponentConfig('search');
//    $cfg = loadConfig();


    if ($query && strlen($query)>=3){
        $query = trim($query);
        $query = strip_tags($query);
        $query = str_replace('\'', '', $query);
        $query = str_replace('"', '', $query);
        echo '<div class="row" >';
        echo '<form action="/search.html" method="post" class="cs-contact-form">';
        echo '<input type="hidden"
						 name="do"
						 value="search"/></p>';
        echo '<p><input type="text" class="form-control"
						 name="query"
						 size="30"
						 value="'.$query.'"
						 /></p>';
        echo '<p><select name="look" class="form-control">
					<option value="anyword">Ихтиёрий сўз</option>
					<option value="allwords">Барча сўзлар</option>
					<option value="phrase">Масофа бўйича</option>
				  </select> </p>';
        echo '<p><input class="btn btn-primary" type="submit" value="Излаш"/>';

        echo '</form>';

        $query = urldecode($query);
        $query = trim($query);
        $query = str_replace('\'', '', $query);
        $query = str_replace('"', '', $query);
        $query = $db->escape($query);
        $query = htmlspecialchars($query);
        $words =preg_split('/ /', $query);;
        $count=sizeof($words);
        $n=0;
        $perpage = $cfg['perpage'];
        if (isset($_REQUEST['page'])) { $page = $_REQUEST['page']; } else { $page = 1; }

        $sql = "SELECT con.*, cat.cname cat_title, cat.id cat_id,cat.slug FROM 
                                        content con 
                                        left join categories_bind cb on cb.item_id=con.id
                                        left join categories cat on cat.id=cb.category_id
				                        WHERE ";

        if($look == 'anyword'){
            $looktype = 'любое слово';
            foreach($words as $w){
                if(strlen($w)>1){
                    $n++;
                    if ($n==1) { $sql .= "con.fulltext LIKE '%$w%'"; }
                    else { $sql .= " OR con.fulltext LIKE '%$w%'"; }
                }
            }
        }
        if($look == 'allwords'){
            $looktype = 'все слова';
            foreach($words as $w){
                if(strlen($w)>1){
                    $n++;
                    if ($n==1) { $sql .= "con.fulltext LIKE '%$w%'"; }
                    else { $sql .= " AND con.fulltext LIKE '%$w%'"; }
                }
            }
        }
        if($look == 'phrase'){
            $looktype = 'фраза целиком';
            $sql .= "con.fulltext LIKE '%$query%'";
        }



        $result = $db->query($sql) ;
        $found= $db->numrows($result);

        $sql .= "LIMIT ".(($page-1)*$perpage).", $perpage";
        $result = $db->query($sql);


        if($found>0){
            $_SESSION['squery'] = $query;

            echo '<p style="clear:both">
					<b>Мақола топилди:</b> '.$found.' та
				  </p>';

//            echo '<p>
//			 		<b>Излаш</b> "<i>'.$query.'</i>" <b>с помощью</b> <a href="http://www.yandex.ru/yandsearch?text='.urlencode($query).'" target="_blank"><img src="/images/icons/yandex.png" alt="Яндекс" border="0"/></a>
//				  </p>';



            echo '<table width="90%" align="center">';
            $n = 0+(($page-1)*$perpage);
            $acon = $db->fetch_all($result,true);
            foreach($acon as $con){
                $menu_id=$db->getFieldById('id','menus',"link='{$con['slug']}'");
                $url=Content::getArticleURL($menu_id, $con['seo']);
                $n++;
                echo '<tr>';
                echo '<td width="20" valign="top">';
                echo $n.'.';
                echo '</td>';
                echo '<td>';
                echo '<a href="'.$url.'"><b>'.preg_replace('/(?i)' . $query . '/', '<i style="background:#FFFF00">' . $query . '</i>', $con['title']).'</b></a><br/>';
                echo preg_replace('/(?i)' . $query . '/', '<i style="background:#FFFF00">' . $query . '</i>', $con['introtext']).'<br/>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
            //echo pageBar($page, $perpage, $sql, $query, $look, "LIMIT ".(($page-1)*$perpage).", $perpage");

        }else{
            echo '<p>По запросу "<b>'.$query.'</b>" ничего не найдено.</p>';
            echo '<p><b>Найти</b> "<i>'.$query.'</i>" <b>с помощью</b> <a href="http://www.yandex.ru/yandsearch?text='.urlencode($query).'" target="_blank"><img src="/images/icons/yandex.png" alt="Яндекс" border="0"/></a>
			  			  </p>';
        }

    }else {
        if($query){
            echo '<p style="color:red">Поисковый запрос должен быть не короче 3х символов!</p>';
        }

        //PRINT SEARCH FORM
        echo '<div id="colleft">';
        echo '<h4>Излаш...</h4>';
        echo '<form action="/search.html" method="POST" style="clear:both">';
        echo 'Сайтдан излаш: ';
        echo '<input type="hidden"
							 name="do"
							 value="search"/>';
        echo '<input type="text"
							 name="query"
							 size="30"
							 value=""
							 /> ';
        echo '<select name="look">
						<option value="anyword">Ихтиёрий сўзни</option>
						<option value="allwords">Ҳамма сўзни</option>
						<option value="phrase">Фраза бўйича</option>
					  </select> ';
        echo '<input type="submit" value="Қидириш"/>';

        echo '</form>';
        echo '</div>';
//        echo '</div>';


    }

    if ($core->action=='tag'){
        $query = $_REQUEST['query'];
        $query = urldecode($query);
        $query = trim($query);
        $query = str_replace('\'', '', $query);
        $query = str_replace('"', '', $query);
        $query = $db->escape($query);
        $query = htmlspecialchars($query);
        if ($query) {
            $_SESSION['fsearchquery'] = $query;
            $quer = $_SESSION['fsearchquery'];

            if ($quer) {
                if ($query && strlen($quer) < 3) {
                    $total = 0;
                    unset($_SESSION['fsearchquery']);
                    $msg = '<p><strong>Ошибка:</strong> <span style="color:red">Строка для поиска не может быть меньше 3 букв!</span></p>';
                }else{

                    $sql = "SELECT f.* FROM files f LEFT JOIN users u ON f.user_id = u.id WHERE f.published = 1 AND LOWER(f.title) LIKE '%".strtolower($quer)."%' OR LOWER(f.description) LIKE '%".strtolower($quer)."%' OR LOWER(f.tags) LIKE '%".strtolower($quer)."%' ORDER BY f.pubdate DESC, f.title  ";
                    //.(($page-1)*$perpage).", $perpage";

                    $result = $db->query($sql);
                    $find=$db->numrows($result);
                    if ($find) {
                        while ($file = $db->fetch($result, true)) {
                            $file['pubdate'] =  $core->cmsRusDate($core->dodate($config->long_date, $file['pubdate']));
                            $file['mb'] = $core->getSize($file['size']);
                            $file['title'] = preg_replace('/(?i)' . $quer . '/', '<i style="background:#FFFF00">' . $quer . '</i>', $file['title']);
//                        $file['description'] = $inCore->parseSmiles($file[description], true);
                            $file['description'] = preg_replace('/(?i)' . $quer . '/', '<i style="background:#FFFF00">' . $quer . '</i>', $file['description']);
                            $file['category'] = $fc->getFileCategory($file['cat_id']);

                            $files[] = $file;
                        }
                        $total = $db->rows_count('files',"published = 1 AND LOWER(title) LIKE '%".strtolower($quer)."%' OR LOWER(description) LIKE '%".strtolower($quer)."%' OR LOWER(tags) LIKE '%".strtolower($quer)."%'");

                    }else{
                        $total = 0;

                    }
                }
            }
        }else {unset($_SESSION['fsearchquery']);}
    }
}
?>