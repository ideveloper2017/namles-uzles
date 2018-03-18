

function addComment(sess_md5, target, target_id, parent_id){
    $('div.reply').html('').hide();
    $("#cm_addentry"+parent_id).html("<div>Загрузка формы...</div>");
    $("#cm_addentry"+parent_id).load("/component/comments/entryForm.php", {cd: sess_md5, target: target, target_id: target_id, parent_id: parent_id}, cmLoaded());
    $("#cm_addentry"+parent_id).slideDown("fast");
}

function cmLoaded() {
    //$("#content").autogrow();
}

function loadComments(target, target_id, anchor){

    $('div.cm_ajax_list').html('<p style="margin:30px; margin-left:0px; padding-left:50px; background:url(/images/ajax-loader.gif) no-repeat">загрузка комментариев...</p>');

    $.ajax({
        type: "POST",
        url: "/component/comments/comment_ajax.php",
        data: "target="+target+"&target_id="+target_id,
        success: function(data){
            $('div.cm_ajax_list').html(data);
            $('td.loading').html('');
            if (anchor){
                window.location.hash = anchor.substr(1, 100);
                $('a[href='+anchor+']').css('color', 'red').attr('title', 'Вы пришли на страницу по этой ссылке');
            }
        }
    });

}
function expandComment(id){
    $('a#expandlink'+id).hide();
    $('div#expandblock'+id).show();
}

function cancelComment(parent_id){
    $("#cm_addentry"+parent_id).parent('div').find('.cmm_entry').show();
    $("#cm_addentry"+parent_id).hide();
}

