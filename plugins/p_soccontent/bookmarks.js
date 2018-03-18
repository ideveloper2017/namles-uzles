// Social bookmarks
// (c) www.web-mastery.info, 2008
function bm() {
var title=encodeURIComponent(document.title);
var url=encodeURIComponent(location.href);

// dir image
var dir='plugins/p_soccontent/zakladki/';
var services=[
['facebook.com/sharer.php?u='+url,'facebook.png','FaceBook'],
['vkontakte.ru/share.php?url='+url,'vkontakte.png','Вконтакте'],
['odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+url+'&title='+title,'odnoklassniki.png','Одноклассники'],
['connect.mail.ru/share?share_url='+url,'mailrumir.png','Мой@Мир'],
['twitter.com/?status='+title+' '+url,'twitter.png','Twitter'],
['my.ya.ru/posts_add_link.xml?URL='+url+'&title='+title,'ya.png','Яндекс.Блог'],
['livejournal.com/update.bml?event='+url+'&subject='+title,'livejournal.png','LiveJournal'],
['google.com/buzz/post?url='+url+'&title='+title,'google.png','Google Buzz'],
['mister-wong.ru/index.php?action=addurl&bm_url='+url+'&bm_description='+title,'misterwong.png','Mister Wong'],
['technorati.com/faves?add='+url+'','technorati.png','Technorati'],
['del.icio.us/post?url='+url+'&title='+title,'delicious.png','del.icio.us'],
['stumbleupon.com/submit?url='+url+'&title='+title,'stumbleupon.png','Stumbleupon'],
['digg.com/submit?url='+url+'&title='+title,'digg.png','Digg'],
['reddit.com/submit?url='+url+'&title='+title,'reddit.png','Reddit'],];
var btn='<div class="single-share" style="padding-top:50px;"><div class="btn-share"></div>';
for (i=0;i<services.length;i++) {
btn+='<a href="http://'+services[i][0]+'" target=_blank><img src="/'+dir+services[i][1]+'" alt="Добавить в '+services[i][2]+'" title="Добавить в '+services[i][2]+'" style="border:0;padding:0;margin:0 4px 0 0;"></a>';}
btn+='</div>';
document.write(btn);}
bm();