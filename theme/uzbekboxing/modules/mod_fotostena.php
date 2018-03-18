<link rel="stylesheet" type="text/css" href="/modules/mod_fotostena/css/style.css" />
<div style="position:relative;">
    <div class="infobar" id="infobar">
        <span id="description"></span>
        <span id="loading">Загрузка</span>
        <span class="reference"></span>
    </div>
    <div id="thumbsWrapper" style="overflow: hidden;">
        <?php if ($photos) {?>
            <?php $foo=$cfg['colnum']*98;?>
         <div id="content3" style="width:<?php echo $foo;?>px;">
            <?php foreach ($photos as $photo) {?>
            <img src="/images/photos/small/<?php echo $photo['files']?>" rel="<?php echo $photo['id'];?>" alt="/images/photos/<?php echo $photo['files']?>" title="<?php echo $photo['title'];?>"/>
            <?php }?>
<!--            <div class="placeholder"></div>-->
        </div>
        <div id="panel">
            <div id="wrappers">
                <a id="prev"></a>
                <a id="next"></a>
            </div>
        </div>
        <?php }?>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        var current = -1;
        var totalpictures = $('#content3 img').size();
        var speed 	= 500;
        $('#content3').show();

        $(window).bind('resize', function() {
            var $picture = $('#wrappers').find('img');
            resize($picture);
        });

        $('#content3 > img').hover(function () {
            var $this   = $(this);
            $this.stop().animate({'opacity':'1.0'},200);
        },function () {
            var $this   = $(this);
            $this.stop().animate({'opacity':'0.4'},200);
        }).bind('click',function(){
            var $this   = $(this);

            $('#loading').show();

            $('<img/>').load(function(){
                $('#loading').hide();
                $('#infobar').show();
                if($('#wrappers').find('img').length) return;
                current 	= $this.index();
                var $theImage   = $(this);

                resize($theImage);

                $('#wrappers').append($theImage);
                $theImage.fadeIn(800);

                $('#panel').animate({'height':'100%'},speed,function(){
                    var title = $this.attr('title');
                    rel   = $this.attr('rel');
                    if(title != '') {

                        $('#description').load("/modules/mod_fotostena/load.php", {item_id: rel});
                        $('#description').show(); }
                    else
                        $('#description').empty().hide();

                    if(current==0)
                        $('#prev').hide();
                    else
                        $('#prev').fadeIn();
                    if(current==parseInt(totalpictures-1))
                        $('#next').hide();
                    else
                        $('#next').fadeIn();

                    $('#thumbsWrapper').css({'z-index':'0','height':'0px'});
                });
            }).attr('src', $this.attr('alt'));
        });

        $('#wrappers').delegate('img','click',function(){
            $this = $(this);
            $('#description').empty().hide();

            $('#thumbsWrapper').css('z-index','10')
                .stop()
                .animate({'height':'100%'},speed,function(){
                    var $theWrapper = $(this);
                    $('#panel').animate({'height':'0px'},speed);
                    $theWrapper.css('z-index','0');
                    $('#infobar').hide();
                    $this.remove();
                    $('#prev').hide();
                    $('#next').hide();
                });
        });

        $('#next').bind('click',function(){
            var $this           = $(this);
            var $nextimage 		= $('#content3 img:nth-child('+parseInt(current+2)+')');
            navigate($nextimage,'right');
        });
        $('#prev').bind('click',function(){
            var $this           = $(this);
            var $previmage 		= $('#content3 img:nth-child('+parseInt(current)+')');
            navigate($previmage,'left');
        });

        function navigate($nextimage,dir){
            if(dir=='left' && current==0)
                return;
            if(dir=='right' && current==parseInt(totalpictures-1))
                return;
            $('#loading').show();
            $('<img/>').load(function(){
                var $theImage = $(this);
                $('#loading').hide();
                $('#description').empty().fadeOut();

                $('#wrappers img').stop().fadeOut(500,function(){
                    var $this = $(this);

                    $this.remove();
                    resize($theImage);

                    $('#wrappers').append($theImage.show());
                    $theImage.stop().fadeIn(800);

                    var title = $nextimage.attr('title');
                    rel = $nextimage.attr('rel');
                    if(title != ''){
                        $('#description').load("/modules/mod_fotostena/load.php", {item_id: rel});
                        $('#description').show();
                    }
                    else
                        $('#description').empty().hide();

                    if(current==0)
                        $('#prev').hide();
                    else
                        $('#prev').show();
                    if(current==parseInt(totalpictures-1))
                        $('#next').hide();
                    else
                        $('#next').show();
                });
                if(dir=='right')
                    ++current;
                else if(dir=='left')
                    --current;
            }).attr('src', $nextimage.attr('alt'));
        }

        function resize($image){
            var windowH      = $(window).height()-100;
            var windowW      = $(window).width()-80;
            var theImage     = new Image();
            theImage.src     = $image.attr("src");
            var imgwidth     = theImage.width;
            var imgheight    = theImage.height;

            if((imgwidth > windowW)||(imgheight > windowH)){
                if(imgwidth > imgheight){
                    var newwidth = windowW;
                    var ratio = imgwidth / windowW;
                    var newheight = imgheight / ratio;
                    theImage.height = newheight;
                    theImage.width= newwidth;
                    if(newheight>windowH){
                        var newnewheight = windowH;
                        var newratio = newheight/windowH;
                        var newnewwidth =newwidth/newratio;
                        theImage.width = newnewwidth;
                        theImage.height= newnewheight;
                    }
                }
                else{
                    var newheight = windowH;
                    var ratio = imgheight / windowH;
                    var newwidth = imgwidth / ratio;
                    theImage.height = newheight;
                    theImage.width= newwidth;
                    if(newwidth>windowW){
                        var newnewwidth = windowW;
                        var newratio = newwidth/windowW;
                        var newnewheight =newheight/newratio;
                        theImage.height = newnewheight;
                        theImage.width= newnewwidth;
                    }
                }
            }
            $image.css({'width':theImage.width+'px','height':theImage.height+'px'});

        }
    });
</script>