var layers = []
var active_layer_id = 0;
var LANG_DROP_TO_UPLOAD = '';
var LANG_SELECT_UPLOAD = 'Загрузить фон';
var LANG_CANCEL = 'Отмена';
var LANG_ERROR = 'Ошибка';

$(function() {

    $("#layers ul").sortable({
        update: function( event, ui ) {
            $("#layers ul li").each(function(index){
                var layer_id = $(this).data('id');
                var z_index = index+1;
                $('#preview #slider #layer-'+layer_id).css('z-index', z_index);
            })
        }
    });

    $("#preview #slider .layer").draggable({
        stop: function(event, ui) {
            ssSaveLayerPosition($(ui.helper).data('id'), ui.position);
        }
    });

    document.oncontextmenu = function() {return false;};

    $(document).mousedown(function(e){
        if( e.button == 2 ) {
            ssDeselectLayers();
            return false;
        }
        return true;
    });

    $('#btn-add-layer').click(ssAddLayer);
    $('#btn-del-layer').click(ssDeleteLayer);
    $('#props .input-text').change(ssChangeLayerProps);
    $('#props .input-color').change(ssChangeLayerProps);
    $('#props .input-checkbox').change(ssChangeLayerProps);
    $('#props .input-size').change(ssChangeLayerProps);
    $('#props select').change(ssChangeLayerProps);

    $('#props #prop-color').ColorPicker({
        onChange: function (hsb, hex, rgb) {
        	$('#prop-color').val('#' + hex);
            $('#preview #slider #layer-'+active_layer_id).css('color', '#' + hex);
        },
        onBeforeShow: function () {
    		$(this).ColorPickerSetColor(this.value.substr(1, 6));
    	},
        onBlur: function(){
            ssChangeLayerProps();
        },
        onHide: function(){
            ssChangeLayerProps();
        }
    });

    $('#props #prop-background-color').ColorPicker({
        onChange: function (hsb, hex, rgb) {
        	$('#prop-background-color').val('#' + hex);
            $('#preview #slider #layer-'+active_layer_id).css('background-color', '#' + hex);
        },
        onBeforeShow: function () {
    		$(this).ColorPickerSetColor(this.value.substr(1, 6));
    	},
        onBlur: function(){
            ssChangeLayerProps();
        },
        onHide: function(){
            ssChangeLayerProps();
        }
    });

    $('#slide-color').ColorPicker({
        onChange: function (hsb, hex, rgb) {
        	$('#slide-color').val('#' + hex);
            $('#preview #slider').css('background-color', '#' + hex);
        },
        onBeforeShow: function () {
    		$(this).ColorPickerSetColor(this.value.substr(1, 6));
    	}
    });

    ssInitUploader();
    ssInitLayerUploader();

    if (typeof(load_struct) != 'undefined'){
        ssLoadStruct();
    }

});

function ssDeleteLayer(){

    if (!active_layer_id){return;}

    $('#layers #layer-pos-'+active_layer_id).remove();
    $('#preview #slider #layer-'+active_layer_id).remove();

    ssDeselectLayers();

}

function ssAddLayer(){

    ssDeselectLayers();

    var id = $('#layers li').length + 1;

    var layer = {
        id: id,
        text: 'Введите текст',
        href: '',
        styles: {
            'font-family': 'Arial',
            'font-size': '18px',
            'color': '#000000',
            'background-color': '',
            'background-image': '',
            'padding': '',
            'font-weight': 'normal',
            'font-style': 'normal',
            'width': 'auto',
            'height': 'auto',
            'left': 0,
            'top': 0
        }
    }

    layers.push(layer);

    var name = layer.text;
    var tag = '<li class="ui-state-default" id="layer-pos-'+id+'" data-id="'+id+'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span class="title">'+name+'</span></li>';
    $(tag).appendTo('#layers ul');

    $('#layers ul #layer-pos-'+id).click(function(){
        var id = $(this).data('id');
        ssSelectLayer(id);
    });

    tag = '<div class="layer" id="layer-'+id+'" data-id="'+id+'">' + layer.text + '</div>';

    $(tag).click(function(){
        ssSelectLayer(id);
    }).
    css('z-index', id).
    draggable({
        stop: function(event, ui) {
            ssSaveLayerPosition($(ui.helper).data('id'), ui.position);
        }
    }).
    appendTo('#preview #slider');

    ssApplyLayerStyles(id);
    ssSelectLayer(id);

    $('#prop-text').focus().select();

}

function ssSelectLayer(id){

    active_layer_id = id;

    $('#props').show();
    $('#btn-del-layer').show();

    $('#layers li').removeClass('selected');
    $('#layers #layer-pos-'+id).addClass('selected');

    $('#preview #slider .layer').removeClass('selected');
    $('#preview #slider #layer-'+id).addClass('selected');

    var layer = ssGetLayerById(id);

    $('#prop-text').val(layer.text);
    $('#prop-href').val(layer.href);
    $('#prop-font-family').val(layer.styles['font-family']);
    $('#prop-font-size').val(layer.styles['font-size']);
    $('#prop-color').val(layer.styles['color']);
    $('#prop-background-color').val(layer.styles['background-color']);

    if (layer.styles['font-weight'] == 'bold'){
        $('#prop-font-weight').attr('checked', 'checked');
    } else {
        $('#prop-font-weight').removeAttr('checked');
    }

    if (layer.styles['font-style'] == 'italic'){
        $('#prop-font-style').attr('checked', 'checked');
    } else {
        $('#prop-font-style').removeAttr('checked');
    }

    if (layer.styles['padding']){
        $('#prop-padding').val(layer.styles['padding'].replace('px', ''));
    } else {
        $('#prop-padding').val('');
    }

    if (layer.styles['width'] != 'auto'){
        $('#prop-width').val(layer.styles['width'].replace('px', ''));
    } else {
        $('#prop-width').val('');
    }

    if (layer.styles['height'] != 'auto'){
        $('#prop-height').val(layer.styles['height'].replace('px', ''));
    } else {
        $('#prop-height').val('');
    }

    if (layer.styles['left'] != 0){
        $('#prop-left').val(layer.styles['left'].replace('px', ''));
    } else {
        $('#prop-left').val('');
    }

    if (layer.styles['top'] != 0){
        $('#prop-top').val(layer.styles['top'].replace('px', ''));
    } else {
        $('#prop-top').val('');
    }

}

function ssDeselectLayers(){
    $('#layers li').removeClass('selected');
    $('#preview #slider .layer').removeClass('selected');
    $('#props').hide();
    $('#btn-del-layer').hide();
}

function ssSaveLayerPosition(id, pos){

    for(var lid in layers){
        var layer = layers[lid];
        if (layer.id == id) {
            break;
        }
    }

    layer.styles['left'] = pos.left + 'px';
    layer.styles['top'] = pos.top + 'px';

    if (id==active_layer_id){
        $('#prop-left').val(pos.left);
        $('#prop-top').val(pos.top);
    }

}

function ssChangeLayerProps(){

    if (!active_layer_id) {return false;}

    for(var lid in layers){
        var layer = layers[lid];
        if (layer.id == active_layer_id) {
            break;
        }
    }

    layer.text = $('#prop-text').val();
    layer.href = $('#prop-href').val();
    layer.styles['font-family'] = $('#prop-font-family').val();
    layer.styles['font-size'] = $('#prop-font-size').val();
    layer.styles['font-weight'] = $('#prop-font-weight:checked').length ? 'bold' : 'normal';
    layer.styles['font-style'] = $('#prop-font-style:checked').length ? 'italic' : 'normal';
    layer.styles['color'] = $('#prop-color').val();
    layer.styles['background-color'] = $('#prop-background-color').val();
    layer.styles['padding'] = $('#prop-padding').val() + 'px';
    layer.styles['width'] = $('#prop-width').val() + 'px';
    layer.styles['height'] = $('#prop-height').val() + 'px';
    layer.styles['left'] = $('#prop-left').val() + 'px';
    layer.styles['top'] = $('#prop-top').val() + 'px';

    if (layer.styles['padding'] == 'px') {layer.styles['padding'] = 0;}
    if (layer.styles['width'] == 'px') {layer.styles['width'] = 'auto';}
    if (layer.styles['height'] == 'px') {layer.styles['height'] = 'auto';}
    if (layer.styles['left'] == 'px') {layer.styles['left'] = '0';}
    if (layer.styles['top'] == 'px') {layer.styles['top'] = '0';}

    var pos_text = layer.text.lenght < 20 ? layer.text : layer.text.substr(0, 17) + '...';
    $('#layers ul #layer-pos-'+layer.id+' .title').html(pos_text);

    layers[lid] = layer;

    ssApplyLayerStyles(active_layer_id);

}

function ssApplyLayerStyles(id){

    var layer = ssGetLayerById(id);
    $('#preview #slider #layer-'+id).
        html(layer.text).
        data('href', layer.href).
        css(layer.styles);

}

function ssGetLayerById(id){
    for(var lid in layers){
        var layer = layers[lid];
        if (layer.id == id) {
            return layer;
        }
    }
}

function ssInitUploader(){

    var upload_url = $('#ss-slide-options').data('upload-url');

    uploaderSlide = new qq.FileUploader({
            element: document.getElementById('file-uploader'),
            action: upload_url,
            multiple: false,
            debug: false,

            onSubmit: function(id, fileName){
                var widget = $('#ss-upload-widget');
                $('#file-uploader', widget).hide();
                $('.loading', widget).show();
            },

            onComplete: function(id, file_name, result){

                var widget = $('#ss-upload-widget');

                $('#file-uploader', widget).show();
                $('.loading', widget).hide();

                if(!result.success) {
                    alert(result.error);
                    return;
                }

                $('#ss-slide-options input[name=bg_image]').val(result.src);
                $('#preview #slider').css('background-image', 'url("'+result.src+'")');

            }

        });

}

function ssInitLayerUploader(){

    var upload_url = $('#ss-slide-options').data('upload-layer-url');

    uploaderLayer = new qq.FileUploader({
            element: document.getElementById('file-layer-uploader'),
            action: upload_url,
            multiple: false,
            debug: false,

            onSubmit: function(id, fileName){
                uploaderLayer.setParams({
                    layer_id: active_layer_id
                });
                var widget = $('#ss-layer-upload-widget');
                $('#file-layer-uploader', widget).hide();
                $('.loading', widget).show();
            },

            onComplete: function(id, file_name, result){

                var widget = $('#ss-layer-upload-widget');

                $('#file-layer-uploader', widget).show();
                $('.loading', widget).hide();

                if(!result.success) {
                    alert(result.error);
                    return;
                }

                for(var lid in layers){
                    var layer = layers[lid];
                    if (layer.id == result.layer_id) {
                        break;
                    }
                }

                layer.styles['background-image'] = 'url('+result.src+')';
                layer.styles['width'] = result.width + 'px';
                layer.styles['height'] = result.height + 'px';

                if (layer.styles['width'] == 'px') {layer.styles['width'] = 'auto';}
                if (layer.styles['height'] == 'px') {layer.styles['height'] = 'auto';}

                layers[lid] = layer;

                $('#preview #slider #layer-' + result.layer_id)
                    .css('background-image', 'url("'+result.src+'")')
                    .css('width', result.width+'px')
                    .css('height', result.height+'px');

                ssSelectLayer(result.layer_id);

            }

        });

}

function ssSave(){

    if (!$('#slide-title').val()){
        alert('Введите название слайда');
        return;
    }

    var layers_compiled = [];

    $('#layers li').each(function(index){

       var id = $(this).data('id');

       var layer = ssGetLayerById(id);
       layer.styles['left'] = $('#preview #slider #layer-'+id).css('left');
       layer.styles['top'] = $('#preview #slider #layer-'+id).css('top');
       layer.styles['z-index'] = $('#preview #slider #layer-'+id).css('z-index');

       layers_compiled.push(layer);

    });

    var struct = JSON.stringify(layers_compiled);

    $('#addform input[name=struct]').val(struct);

    $('#addform').submit();

    return false;

}

function ssLoadStruct(){

    load_struct = JSON.parse(load_struct);

    for (var idx in load_struct){

        var layer = load_struct[idx];

        var id = layer.id;

        var pos_text = layer.text.lenght < 20 ? layer.text : layer.text.substr(0, 17) + '...';

        var tag = '<li class="ui-state-default" id="layer-pos-'+id+'" data-id="'+id+'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span class="title">'+pos_text+'</span></li>';
        $(tag).appendTo('#layers ul');

        tag = '<div class="layer" id="layer-'+id+'" data-id="'+id+'">' + layer.text + '</div>';

        $(tag).
        draggable({
            stop: function(event, ui) {
                ssSaveLayerPosition($(ui.helper).data('id'), ui.position);
            }
        }).
        appendTo('#preview #slider');

        $('#preview #slider #layer-'+id).
            html(layer.text).
            data('href', layer.href).
            css(layer.styles);

        delete layer.styles['z-index'];

        layers.push(layer);

    }

    $('#layers ul li').click(function(){
        var id = $(this).data('id');
        ssSelectLayer(id);
    });

    $('#preview #slider .layer').each(function(){
        var id = $(this).data('id');
        $(this).click(function(){
            ssSelectLayer(id);
        });
    });

}