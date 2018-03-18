<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 17.09.13
 * Time: 13:55
 * To change this template use File | Settings | File Templates.
 */
function arhive()
{
//    $menuid = menuID();
    $db = Registry::get("DataBase");
    $config = Registry::get("Config");
    $users = Registry::get("Users");
    $core = Registry::get("Core");


    if (!Core::$action) {
        $core->action = 'view';
    } else {
        $core->action = Core::$action;
    }

    if (!Core::$id) {
        $core->id = 0;
    } else {
        $core->id = Core::$id;
    }

    if (isset($_REQUEST['y'])){ $year = intval($_REQUEST['y']); } else { $year = 'all'; 	}
    if (isset($_REQUEST['m'])){ $month = intval($_REQUEST['m']); } else { $month = 'all'; 	}
    if (isset($_REQUEST['d'])){ $day = intval($_REQUEST['d']); } else { $day = 'all'; 	}

    echo '<div class="con_heading">Архив материалов</div>';

  if ($core->action=='view'){
    if ($year == 'all'){

        $sql = "SELECT DATE_FORMAT( pubdate, '%M, %Y' ) fdate, DATE_FORMAT( pubdate, '%Y' ) year, DATE_FORMAT( pubdate, '%m' ) month, COUNT( id ) num
				FROM content
				GROUP BY DATE_FORMAT(pubdate, '%M, %Y')
				ORDER BY pubdate DESC
				";
        $result = $db->query($sql);

        if ($db->num_rows($result)>0){
            echo '<ul class="arhive_list">';
            while ($item = $db->fetch_assoc($result)){
                $item['fdate'] =$core->cmsRusDate($item['fdate']);
                echo '<li><a href="arhive/'.$item['year'].'/'.$item['month'].'">'.$item['fdate'].'</a> ('.$item['num'].')</li>';

            }
            echo '</ul>';

        } else { echo '<p>Нет материалов для отображения</p>'; }

    } else {


        if($day=='all' && $month=='all'){
            echo '<div class="con_description">Материалы за '.$year.' год</div>';

         //   cmsAddPathway($year, '/arhive/'.$menuid.'/'.$year);

            $sql = "SELECT DATE_FORMAT( pubdate, '%M' ) fdate, DATE_FORMAT( pubdate, '%Y' ) year, DATE_FORMAT( pubdate, '%m' ) month, COUNT( id ) num
					FROM content
					WHERE DATE_FORMAT(pubdate, '%Y') LIKE '$year'
					GROUP BY DATE_FORMAT(pubdate, '%M')
					ORDER BY pubdate DESC
					";
            $result = $db->query($sql) or die(mysql_error());

            if ($db->num_rows($result)>0){
                echo '<ul class="arhive_list">';
                while ($item =$db->fetch_assoc($result)){
                    $item['fdate'] = $ker->cmsRusDate($item['fdate']);
                    echo '<li><a href="/arhive/'.$menuid.'/'.$item['year'].'/'.$item['month'].'">'.$item['fdate'].'</a> ('.$item['num'].')</li>';

                }
                echo '</ul>';

            } else { echo '<p>Нет материалов для отображения</p>'; }
        } else

            if($day=='all' && $month=='all'){
                echo '<div class="con_description">Материалы за '.$year.' год</div>';

             //   cmsAddPathway($year, '/arhive/'.$menuid.'/'.$year);

                $sql = "SELECT DATE_FORMAT( pubdate, '%M' ) fdate, DATE_FORMAT( pubdate, '%Y' ) year, DATE_FORMAT( pubdate, '%m' ) month, COUNT( id ) num
					FROM content
					WHERE DATE_FORMAT(pubdate, '%Y') LIKE '$year'
					GROUP BY DATE_FORMAT(pubdate, '%M')
					ORDER BY pubdate DESC
					";
                $result = $db->query($sql) or die(mysql_error());

                if ($db->num_rows($result)>0){
                    echo '<ul class="arhive_list">';
                    while ($item = $db->fetch_assoc($result)){
                        $item['fdate'] =$ker-> cmsRusDate($item['fdate']);
                        echo '<li><a href="/arhive/'.$menuid.'/'.$item['year'].'/'.$item['month'].'">'.$item['fdate'].'</a> ('.$item['num'].')</li>';

                    }
                    echo '</ul>';

                } else { echo '<p>Нет материалов для отображения</p>'; }
            } else {
                $month_name = $core->cmsRusDate(date('F', mktime(0,0,0,$month,1,$year)));
                if ($day == 'all') {

//                    cmsAddPathway($year, '/arhive/'.$menuid.'/'.$year);
//                    cmsAddPathway($month_name, '/arhive/'.$menuid.'/'.$year.'/'.$month);

                    echo '<div class="con_description">Материалы за '.$month_name.' '.$year.' года</div>';
                    $date_str = $year.'-'.$month;
                    $date_where = "DATE_FORMAT(con.created_at, '%Y-%c') LIKE '$date_str'";
                } else {
//                    cmsAddPathway($year, '/arhive/'.$menuid.'/'.$year);
//                    cmsAddPathway($month_name, '/arhive/'.$menuid.'/'.$year.'/'.$month);
//                    cmsAddPathway($day, '/arhive/'.$menuid.'/'.$year.'/'.$month.'/'.$day);

                    echo '<div class="con_description">Материалы за '.$day.', '.$core->cmsRusDate(date('F', mktime(0,0,0,$month,1,$year))).' '.$year.' года</div>';
                    $date_str = $year.'-'.$month.'-'.$day;
                    $date_where = "DATE_FORMAT(con.created_at, '%Y-%c-%e') LIKE '$date_str'";
                }

                $sql = "SELECT con.*, DATE_FORMAT(con.created_at, '%d-%m-%Y') as fdate, cat.cname as category, cat.id cid,cat.slug
							FROM content con, categories cat
							WHERE $date_where AND con.category_id = cat.id
							ORDER BY con.created_at DESC
							";

                $result = $db->query($sql);

                if ($db->numrows($result)){
                    echo '<table width="100%" cellspacing="2" border="0" class="contentlist" >';
                    //		echo '<tr><td colspan="2"><span class="con_title">Материалы</span></td></tr>';
                    while($con = $db->fetch($result,true)){
//                        if(cmsUserAccess('material', $con['id'])){
                            echo '<tr>';
                            echo '<td width="20" valign="top"><img src="/images/icons/markers/article.png" border="0" /></td>';
                            echo '<td width="">';
                            echo '<a href="/content/'.$con['seo'].'.html">'.$con['title'].'</a>';
                            echo '</td>';
                            echo '<td width="20" align="left"><img src=/images/icons/markers/folder.png border=0></td>';
                            echo '<td width="200" align="left"><a href="/content/'.$con['slug'].'">'.$con['category'].'</a></td>';
                            echo '<td width="80" align="right">'.$con['fdate'].'</td>';
                            echo '</tr>';
//                        }
                    }
                    echo '</table>';
                } else {
                    echo '<p>Нет материалов за указанный период.</p>';
                }
            }

    }

  }

    return true;
}
?>