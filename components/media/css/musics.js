function delMusic(mus_id){
    if (confirm('Удалить песню?')){
        $('span#'+ mus_id).show();
        $.post('/music/delete', 'mus='+ mus_id, function(){
            $('#music'+ mus_id).fadeOut(700);
            location.reload();
        });
    }
}

function addmuspl(mus_id){
    if (confirm('Добавить песню в плейлист?')){
        $.post('/music/addmuspl', 'mus='+ mus_id, function(msg){
            alert(msg);
            if (msg == 'Песня успешно добавлена в ваш плейлист.'){
                location.reload();
            }
        });
    }	
}


function delmuspl(mus_id){
    if (confirm('Удалить песню из плейлиста?')){
        $.post('/music/delmuspl', 'mus='+ mus_id, function(msg){
            location.reload();
        });
    }
}

function rating(id,rate){
    $('#rated').fadeOut(700);
    $.post('/music/rated', 'rating='+ rate +'&mus='+ id, function(msg){
        location.reload();
    });
}
