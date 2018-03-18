var auto = {
    data: {
        action: '',
        apiUrl: '/auto/api/',
        container: {}
    },

    init: function(section, container){
        this.data.action = section;
        $.extend(this.data.container, container);
        this.callAction();
    },
    callAction: function(){
        var fn = this.actions[this.data.action];

        if(typeof fn === 'function'){
            $(function(){fn();});
        } else {
            if(console.dir != undefined){
                console.dir('Action not found:' + this.data.action);
            }
        }

    },

    actions: {
        searchFilter: function(){
            auto.models.search.init();
        },
        add: function(){

            auto.models.add.catchErrorsField();
            var phones = [
                { "mask": "+7 ### ###-####", "cc": "AC", "name_en": "Ascension", "desc_en": "", "name_ru": "Остров Вознесения", "desc_ru": "" }
            ];

            $(".phone").inputmask({ mask: phones, definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
            $("#price").inputmask('999999999', { numericInput: true, placeholder:" "})
            $("#countprobeg").inputmask('999999', { numericInput: true, placeholder:" "})
            $('input[name="volume"]').inputmask({"mask": "9[.9]"});
            //$(".phone").mask("+7 (999) 999-9999");

            $('input[name="is_gt"]').on('change', function(e){
               if(this.checked) {
                   $('#cgttext').show();
               } else{
                   $('#cgttext').hide();
               }
            });

            $('select[name="vendor"]').on('change', function(e){
                if(this.value > 0){
                    this.disabled = true;
                    auto.models.models.getByVendor(this.value, auto.models.add.setModels, true);
                    $('select[name="model"]').attr('disabled', true)
                    $('#ajax_models_loader_pic').show();
                } else {
                    auto.models.add.clearModelsList();
                }
            });

            $('#auto-add-addphone').on('click', function(e){
                $('#phone2').show();
                $(this).hide();
            });

            $('select[name="geo[region]"]').on('change', function(e){
                if(this.value > 0){
                    this.disabled = true;
                    auto.models.geo.getCities(this.value, auto.models.add.setCities);
                    $('select[name="geo[city]"]').attr('disabled', true)
                } else {
                    auto.models.add.clearCitiesList();
                }
            });

            $('.error-field-desc').each(function(i, val){
                $(val).on('click', function(e){
                    e.preventDefault();
                    $($(this).data('error-selector')).focus();
                });
            });

            $('.info').ckeditor();

            $(document).on('change', '.checkerr', function(e){
                $(this).removeClass('checkerr');
            });

            $("input[name='admin[vip][active]'], input[name='admin[attach][active]'], input[name='admin[bg][active]']").on('change', function(){
                var $element = $(this);

                if($element.val() > 0){
                    $element.parents('.admin-service-container').removeClass('disabled');
               } else {
                    $element.parents('.admin-service-container').addClass('disabled');
               }
            });
        },
        photomanager: function(){
            auto.models.photomanager.init();
        },
        currency: function(){
            auto.models.currency.init();
        },
        show: function(){
            auto.models.show.init();
        },
        qa: function(){
            auto.models.qa.init();
        },
        cabinet_questions: function(){
            $('.answer-form').on('submit', function(e){
                var $form = $(this);
                e.preventDefault();

                if(!$form.hasClass('submitted')){
                    $.post(auto.data.apiUrl + "ad/answer/add/" + $form.data('question-id'), $form.serialize(), function(data) {
                        $form.removeClass('submitted');

                        if(data.status == 'ok'){
                            $('p', $form.parents('.answer')).html($('textarea', $form).val());
                            $form.remove();

                        } else {
                            $('.form-field-error').empty();
                            $('.form-field-error-border').removeClass('form-field-error-border');
                            $(data.error).each(function(fieldName, val){
                                $(val.selector).parents('.form-field').find('.form-field-error').html(val.title);
                                $(val.selector).addClass('form-field-error-border');
                            });
                        }
                    }, 'json');
                }

                $(this).addClass('submitted');
            });
        },
        cabinet_ads: function(){
            $('.btn_sold').on('click', function(){
                var $adItem = $(this).parents('.aditem');
                $.fancybox.open($('#sell_auto'));

                $('#set_sell').on('click', function(){
                    auto.models.ad.set_sell($adItem.data('ad-id'), function(data){
                        if(data.status == 'ok'){
                            $($adItem).addClass('auto_changes').switchClass("auto_changes", "auto_sold", 3000, "easeInOutQuad" );
                            $('.auto_status', $adItem).html('<span>Машина продана</span>');
                        }


                        $.fancybox.close();
                    });
                });

            });
        }
    },

    models: {
        show: {
            init: function(){
                $('#show_contacts').on('click', function(){
                    auto.models.show.getPhone(auto.data.container.id, auto.models.show.showPhone);
                });

                $('#set_sell').on('click', function(){
                   auto.models.ad.set_sell($(this).data('ad-id'), function(data){
                       if(data.status == 'ok'){
                           location.reload();
                       }
                   });
                });
                auto.models.show.initGallery();
                auto.models.show.initSoc();
            },
            initGallery: function(){
                $('.photo_container-photo_previews a').on('click', function(e){
                    e.preventDefault();
                    $('.photo_container-photo_previews a').removeClass('active');
                    $(this).addClass('active');
                    $('#show_main_photo').attr('photos-index', $(this).attr('photos-index')).get(0).href = $(this).attr('href');
                    $('#show_main_photo img')[0].src = $(this).attr('data-preview');
                });

                $('#show_main_photo').on('click', function(e){
                    e.preventDefault();
                    var index = $(this).attr('photos-index');
                    $.fancybox.open($('.photo_container-photo_previews a'), {
                            nextEffect: "elastic",
                            padding: 2,
                            helpers: {
                                thumbs: {
                                    width: 75,
                                    height: 75
                                }
                            },

                            tpl: {
                                closeBtn : '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                                next     : '<a title="Следующая фотография" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                                prev     : '<a title="Предыдущая фотография" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
                            },
                            index : index ? index : 0}
                    );
                });
            },
            initSoc: function(){
              $('.share_soc_icons').on('click', function(e){
                  e.preventDefault();
                  auto.models.show.share.go(this);
              });
            },
            getPhone: function(id, callback){
                $.get(auto.data.apiUrl + "ad/" + id + "/showphone", function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                });
            },
            showPhone: function(data){
                $('.container-phone').html(data);
            },

            share: {
                go: function(_element, _options) {
                    var
                        self = auto.models.show.share,
                        options = $.extend(
                            {
                                type:       'vk',
                                url:        location.href,
                                count_url:  location.href,  // для какой ссылки крутим счётчик
                                title:      document.title,
                                image:      '',
                                text:       ''
                            },
                            $(_element).data(), // Если параметры заданы в data, то читаем их
                            _options            // Параметры из вызова метода имеют наивысший приоритет
                        );

                    if (self.popup(link = self[options.type](options)) === null) {
                        // Если не удалось открыть попап
                        if ( $(_element).is('a') ) {
                            // Если это <a>, то подставляем адрес и просим браузер продолжить переход по ссылке
                            $(_element).prop('href', link);
                            return true;
                        }
                        else {
                            // Если это не <a>, то пытаемся перейти по адресу
                            location.href = link;
                            return false;
                        }
                    }
                    else {
                        // Попап успешно открыт, просим браузер не продолжать обработку
                        return false;
                    }
                },

                // ВКонтакте
                vk: function(_options) {
                    var options = $.extend({
                        url:    location.href,
                        title:  document.title,
                        image:  '',
                        text:   ''
                    }, _options);

                    return 'http://vkontakte.ru/share.php?'
                        + 'url='          + encodeURIComponent(options.url)
                        + '&title='       + encodeURIComponent(options.title)
                        + '&description=' + encodeURIComponent(options.text)
                        + '&image='       + encodeURIComponent(options.image)
                        + '&noparse=true';
                },

                // Одноклассники
                ok: function(_options) {
                    var options = $.extend({
                        url:    location.href,
                        text:   ''
                    }, _options);

                    return 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1'
                        + '&st.comments=' + encodeURIComponent(options.text)
                        + '&st._surl='    + encodeURIComponent(options.url);
                },

                // Facebook
                fb: function(_options) {
                    var options = $.extend({
                        url:    location.href,
                        title:  document.title,
                        image:  '',
                        text:   ''
                    }, _options);

                    return 'http://www.facebook.com/sharer.php?s=100'
                        + '&p[title]='     + encodeURIComponent(options.title)
                        + '&p[summary]='   + encodeURIComponent(options.text)
                        + '&p[url]='       + encodeURIComponent(options.url)
                        + '&p[images][0]=' + encodeURIComponent(options.image);
                },

                // Живой Журнал
                lj: function(_options) {
                    var options = $.extend({
                        url:    location.href,
                        title:  document.title,
                        text:   ''
                    }, _options);

                    return 'http://livejournal.com/update.bml?'
                        + 'subject='        + encodeURIComponent(options.title)
                        + '&event='         + encodeURIComponent(options.text + '<br/><a href="' + options.url + '">' + options.title + '</a>')
                        + '&transform=1';
                },
                gp: function (_options) {
                    var options = $.extend({
                        url: location.href
                    }, _options);

                    return 'https://plus.google.com/share?url='
                        + encodeURIComponent(options.url);
                },
                // Твиттер
                tw: function(_options) {
                    var options = $.extend({
                        url:        location.href,
                        count_url:  location.href,
                        title:      document.title
                    }, _options);

                    return 'http://twitter.com/share?'
                        + 'text='      + encodeURIComponent(options.title)
                        + '&url='      + encodeURIComponent(options.url)
                        + '&counturl=' + encodeURIComponent(options.count_url);
                },

                // Mail.Ru
                mr: function(_options) {
                    var options = $.extend({
                        url:    location.href,
                        title:  document.title,
                        image:  '',
                        text:   ''
                    }, _options);

                    return 'http://connect.mail.ru/share?'
                        + 'url='          + encodeURIComponent(options.url)
                        + '&title='       + encodeURIComponent(options.title)
                        + '&description=' + encodeURIComponent(options.text)
                        + '&imageurl='    + encodeURIComponent(options.image);
                },

                // Открыть окно шаринга
                popup: function(url) {
                    return window.open(url,'','toolbar=0,status=0,scrollbars=1,width=626,height=436');
                }
            }
        },
        search: {
            data: {
                selectors: {
                    showMoreBtn: '#searchFilter-section-more-showBtn',
                    showMoreBtnTitle: '.searchFilter-section-more-showBtn-title',
                    moreContainer: '#searchFilter-section-more-container'
                },
                lng: {
                    normalSearch: 'Обычный поиск',
                    wideSearch: 'Расширенный поиск',
                    pluralWideSearch: ['выбран {0} параметр', 'выбрано {0} параметра', 'выбрано {0} параметров']

                }
            },
            init: function(){
                $(auto.models.search.data.selectors.showMoreBtn).on('click', function(){
                    $(this).toggleClass('opened');
                    if($(this).hasClass('opened')){
                        auto.models.search.showMore();
                    }  else {
                        auto.models.search.hideMore();
                    }
                });

                $(document).keyup(function(e) {
                    switch(e.keyCode) {
                        case 87 : auto.models.search.hideMore(); break;
                        case 83 : auto.models.search.showMore(); break;
                    }
                });

                auto.models.search.reInitMore();

                $('#searchFilter-container select[name="id_vendor"]').on('change', function(e){
                    auto.models.models.getByVendor(this.value, auto.models.search.domAddModels);
                    $('#searchFilter-container select[name="id_model"]').attr('disabled', 'disabled');
                });
            },
            domAddModels: function(html){
                $('#searchFilter-container select[name="id_model"]').html(html).removeAttr('disabled');
            },
            showMore: function(){
                $(auto.models.search.data.selectors.showMoreBtn).addClass('opened');
                $(auto.models.search.data.selectors.moreContainer).show();
                $(auto.models.search.data.selectors.showMoreBtnTitle, auto.models.search.data.selectors.showMoreBtn).html(auto.models.search.data.lng.normalSearch);
            },
            hideMore: function(){
                $(auto.models.search.data.selectors.showMoreBtn).removeClass('opened');
                $(auto.models.search.data.selectors.moreContainer).hide();
                $(auto.models.search.data.selectors.showMoreBtnTitle, auto.models.search.data.selectors.showMoreBtn).html(auto.models.search.getHideBtnTitle());
            },
            reInitMore: function(){
                if($(auto.models.search.data.selectors.showMoreBtn).hasClass('opened')){
                    auto.models.search.showMore();
                }  else {
                    auto.models.search.hideMore();
                }
            },
            getHideBtnTitle: function(){
                var checked = 0;
                $('input', auto.models.search.data.selectors.moreContainer).each(function(k, v){
                    if(v.checked && v.value > 0){
                        checked++;
                    }
                });
                if(checked){
                    $(auto.models.search.data.selectors.showMoreBtnTitle, auto.models.search.data.selectors.showMoreBtn).html(auto.helpers.pluralString(checked, [auto.models.search.data.lng.pluralWideSearch[0], auto.models.search.data.lng.pluralWideSearch[1], auto.models.search.data.lng.pluralWideSearch[2]]).format(checked));
                } else {
                    $(auto.models.search.data.selectors.showMoreBtnTitle, auto.models.search.data.selectors.showMoreBtn).html(auto.models.search.data.lng.wideSearch);
                }
            }
        },
        models: {
            getByVendor: function(vendorId, callback, is_add){
                $.get(auto.data.apiUrl + "vendors/getmodels/" + vendorId, {
                    'is_add': is_add ? 1 : 0
                },function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                });
            }
        },
        geo: {
            getCities: function(region, callback){
                $.get(auto.data.apiUrl + "geo/getcities/" + region, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                });
            },
            getRegions: function(country, callback){
                $.get(auto.data.apiUrl + "geo/getregions/" + country, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                });
            }
        },
        add: {
            setModels: function(html){
                $('select[name="model"]').html(html).removeAttr('disabled');
                $('select[name="vendor"]').removeAttr('disabled');
                $('#ajax_models_loader_pic').hide();
            },
            setCities: function(html){
                $('select[name="geo[city]"]').html(html).removeAttr('disabled');
                $('select[name="geo[region]"]').removeAttr('disabled');
            },
            clearModelsList: function(){
                $('select[name="model"]').attr('disabled', true).html('<option>выберите производителя</option>');
            },
            clearCitiesList: function(){
                $('select[name="geo[city]"]').attr('disabled', true).html('<option>выберите город</option>');
            },
            catchErrorsField: function(){
                $('.checkerr').on('change', function(){
                    $(this).removeClass('checkerr');
                });
            }
        },
        currency: {
            init: function(){
                $(document).keyup(function(e) {
                    switch(e.keyCode) {
                        case 67 : auto.models.currency.toggleCurrencyPanel(); break;
                    }
                });

                $('#currency-control > div').on('click', function(){
                    auto.models.currency.setCurrency($(this).attr('data-currency'), true);
                });
            },
            toggleCurrencyPanel: function(){
                $('#currency-control').is(':visible') ? $('#currency-control').hide() : $('#currency-control').show();
            },
            showCurrencyPanel: function(){
                $('#currency-control').show();
            },
            hideCurrencyPanel: function(){
                $('#currency-control').hide();
            },
            setCurrency: function(value, reload){
                $.cookie('InstantCMS[currency]', value, { expires: 7, path: '/'});
                if(reload != undefined){
                    document.location.reload(true);
                }
            }
        },
        notices: {
            data: {
                selector: {
                    container: '#auto-notice'
                }
            },
            show: function(text, data){
                var notice = $(auto.models.notices.data.selector.container);

                if(notice){
                    notice.finish().show().removeClass().addClass('auto-message').html(text).fadeOut(4500);
                    if(data.class){
                        notice.addClass(data.class);
                    }
                }
            },
            hide: function(){
                $(auto.models.notices.data.selector.container).hide();
            }
        },
        photomanager: {
            data: {
                selectors: {
                    addBtn: '#auto-add_photo_btn',
                    photoItem: ".photo_item",
                    photoItemImgContainer: ".photo_item a",
                    photoItemId: "data-num",
                    photoItemUpload: ".photo_item.upload",
                    photoItemUploadAttr: "data-uid",
                    progressBarClass: "progressBar",
                    photoItemContainer: '.auto-add-photo_manager-container',
                    photoCounter: '#photo_counter',
                    limClass: 'max-limit',
                    templatePhotoItem: '#template-photoItem',
                    templatePhotoItemUpload: '#template-photoItem-upload'
                }
            },
            init: function(){
                $(auto.models.photomanager.data.selectors.addBtn).fileupload({
                    url: auto.data.apiUrl + 'photos/add/' + auto.data.container.id,
                    dataType: 'json',
                    done: function (e, data) {
                        $.each(data.result, function (index, responseFileData) {
                            if(responseFileData.status == 1){
                                auto.models.photomanager.domAddPhoto(responseFileData, data.files[index]);
                            } else {
                                auto.models.photomanager.refreshCounter(auto.data.container.photoCount--);
                                auto.models.photomanager.domRemovePhotoUpload(data.files[index].uploadID);
                                auto.models.notices.show('Во время загрузки файла: ' + data.files[index].name + ' (' + (data.files[index].size/ (1024*1024)).toFixed(2) + ' MB) возникла ошибка: ' + responseFileData.codeMsg, {class: 'limited'});
                            }
                        });
                    },
                    progress: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);

                        $.each(data.files, function (index, file){
                            var uploadItemhadler = $(auto.models.photomanager.data.selectors.photoItemUpload + '[' + auto.models.photomanager.data.selectors.photoItemUploadAttr + '="' + file.uploadID + '"]');
                            uploadItemhadler.find('.' + auto.models.photomanager.data.selectors.progressBarClass).css({
                                width: progress + '%'
                            }).html(progress + '%');

                            if(progress == 100){
                                uploadItemhadler.addClass('uploaded').find('.' + auto.models.photomanager.data.selectors.progressBarClass).remove();;
                            }

                        });

                    }
                }).on('fileuploadadd', function (e, data){
                    $.each(data.files, function (index, file){
                        var uId = 'u' + Math.random().toString(36).substring(6);
                        file.uploadID = uId;
                        auto.models.photomanager.refreshCounter(auto.data.container.photoCount++);
                        auto.models.photomanager.domAddPhotoUpload(uId)
                    });
                });
                $(document).on('click', '.photo_item .remove', function(e){
                    var parent_item = $(this).parents('.photo_item');
                    auto.models.photomanager.removePhoto(parent_item.attr(auto.models.photomanager.data.selectors.photoItemId), auto.models.photomanager.domRemovePhoto);
                });
                $(document).on('click', '.photo_item .setmain', function(e){
                    if(!$(this).hasClass('selected')){
                        var parent_item = $(this).parents('.photo_item');

                        $('.photo_item .setmain').removeClass('selected').html('Назначить основным');
                        $(this).addClass('selected').html('Основное фото');
                        auto.models.photomanager.setMain(parent_item.attr(auto.models.photomanager.data.selectors.photoItemId), function(){
                            $('.photo_item').removeClass('main');
                            parent_item.addClass('main');
                            auto.models.notices.show('Главная фотография изменена!', {class: 'success'});
                            auto.models.photomanager.domSetMainPhoto;
                        });
                    }
                });
                $(auto.models.photomanager.data.selectors.photoItemImgContainer).fancybox();
            },
            removePhoto: function(num, callback){
                $.post(auto.data.apiUrl + "photos/remove/" + num, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                }, 'json');
            },
            setMain: function(num, callback){
                $.post(auto.data.apiUrl + "photos/setmain/" + num, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                }, 'json');
            },
            domSetMainPhoto: function(pId){
                $(auto.models.photomanager.data.selectors.photoItem).removeClass('main');
                $(auto.models.photomanager.data.selectors.photoItem + '[' + auto.models.photomanager.data.selectors.photoItemId + '="' + pId + '"]').addClass('main');
            },
            domAddPhotoUpload: function(uId){
                $.tmpl($(auto.models.photomanager.data.selectors.templatePhotoItemUpload).html(), {
                    progressBar: 0,
                    uid: uId
                }).appendTo($(auto.models.photomanager.data.selectors.photoItemContainer));
            },
            domRemovePhotoUpload: function(uId){
                $(auto.models.photomanager.data.selectors.photoItemUpload + '[' + auto.models.photomanager.data.selectors.photoItemUploadAttr + '="' + uId + '"]').remove();
            },
            domAddPhoto: function(data, hFile){
                var uploadItemHandler = $(auto.models.photomanager.data.selectors.photoItemUpload + '[' + auto.models.photomanager.data.selectors.photoItemUploadAttr + '="' + hFile.uploadID + '"]'),
                    photoItem = $.tmpl($(auto.models.photomanager.data.selectors.templatePhotoItem), {
                        num: data.id,
                        largePicture: data.images.large,
                        smallPicture: data.images.small
                    });
                uploadItemHandler.replaceWith(photoItem);

                auto.models.notices.show('Вы успешно добавили фотографию!', {class: 'success'});
            },
            domRemovePhoto: function(data){
                $(auto.models.photomanager.data.selectors.photoItem + '[' + auto.models.photomanager.data.selectors.photoItemId + '="' + data.id + '"]').fadeOut('normal', function() {
                    $(this).remove();
                    auto.models.photomanager.refreshCounter(auto.data.container.photoCount--);
                });
            },
            refreshCounter: function(){
                $(auto.models.photomanager.data.selectors.photoCounter).html(auto.data.container.photoCount);

                if(auto.data.container.photoCount > 0){
                    $('#auto-add-photo_manager-container_null').hide();
                } else {
                    $('#auto-add-photo_manager-container_null').show();
                }

                if(auto.data.container.photoCount >= auto.data.container.maxPhotoCount){
                    $(auto.models.photomanager.data.selectors.addBtn).addClass(auto.models.photomanager.data.selectors.limClass);
                    auto.models.notices.show('Вы достигли лимита фотографий!', {class: 'limited'});
                } else {
                    $(auto.models.photomanager.data.selectors.addBtn).removeClass(auto.models.photomanager.data.selectors.limClass);
                }
            }
        },
        qa: {
            init: function(){
                $('#auto-qa-showbtn').on('click', function(){
                    if($(this).parent('.auto-show-qa-container').hasClass('open')){
                        $(this).parent('.auto-show-qa-container').removeClass('open');
                    } else {
                        $(this).parent('.auto-show-qa-container').addClass('open');
                    }
                });

                $('#auto-qa-container form').on('submit', function(e){
                    e.preventDefault();

                    if(!$(this).hasClass('submitted')){
                        $.post(auto.data.apiUrl + "ad/question/add/" + $(this).data('ad-id'), $(this).serialize(), function(data) {
                            $('#auto-qa-container form').removeClass('submitted');

                            if(data.status == 'ok'){
                                $('#auto-qa-container form')[0].reset();
                                $('.auto-show-qa-container').removeClass('open');
                                $('#auto-qa-result').html('Вопрос успешно добавлен!').show(300, function(){
                                    $('#auto-qa-result').fadeOut(6000);
                                });
                            } else {
                                $('.form-field-error').empty();
                                $('.form-field-error-border').removeClass('form-field-error-border');
                                $(data.error).each(function(fieldName, val){
                                    $(val.selector).parents('.form-field').find('.form-field-error').html(val.title);
                                    $(val.selector).addClass('form-field-error-border');
                                });
                            }
                        }, 'json');
                    }

                    $(this).addClass('submitted');
                });
            }
        },
        ad: {
            set_sell: function(adId, callback){
                $.post(auto.data.apiUrl + "ad/sell/" + adId, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                }, 'json');
            }
        },
        paidservices: {
            holding: function(callback){
                $.post(auto.data.apiUrl + "services", {
                    type: auto.data.container.paidservice,
                    id: auto.data.container.id
                }, function(data) {
                    if(callback != undefined){
                        var fn = callback;
                        fn(data);
                    }
                }, 'json');
            }
        }
    },

    helpers: {
        pluralString: function(n, arrStrings){
            function plural (a){
                if ( a % 10 == 1 && a % 100 != 11 ) return 0
                else if ( a % 10 >= 2 && a % 10 <= 4 && ( a % 100 < 10 || a % 100 >= 20)) return 1
                else return 2;
            }

            switch (plural(n)) {
                case 0: return arrStrings[0];
                case 1: return arrStrings[1];
                default: return arrStrings[2];
            }
        }
    }
}


String.prototype.format = function() {
    var formatted = this;
    for (var i = 0; i < arguments.length; i++) {
        var regexp = new RegExp('\\{'+i+'\\}', 'gi');
        formatted = formatted.replace(regexp, arguments[i]);
    }
    return formatted;
};

// jquery mask
(function(e){if(e.fn.inputmask===undefined){function t(e){var t=document.createElement("input"),e="on"+e,n=e in t;if(!n){t.setAttribute(e,"return;");n=typeof t[e]=="function"}t=null;return n}function n(t,r,i){var s=i.aliases[t];if(s){if(s.alias)n(s.alias,undefined,i);e.extend(true,i,s);e.extend(true,i,r);return true}return false}function r(t){function s(e){function s(e,t,n){this.matches=[];this.isGroup=e||false;this.isOptional=t||false;this.isQuantifier=n||false;this.quantifier={min:1,max:1}}function o(e,n,i){var s=t.definitions[n];i=i!=undefined?i:e.matches.length;if(s&&!r){var o=s["prevalidator"],u=o?o.length:0;for(var a=1;a<s.cardinality;a++){var f=u>=a?o[a-1]:[],l=f["validator"],c=f["cardinality"];e.matches.splice(i++,0,{fn:l?typeof l=="string"?new RegExp(l):new function(){this.test=l}:new RegExp("."),cardinality:c?c:1,optionality:e.isOptional,casing:s["casing"],def:s["definitionSymbol"]||n})}e.matches.splice(i++,0,{fn:s.validator?typeof s.validator=="string"?new RegExp(s.validator):new function(){this.test=s.validator}:new RegExp("."),cardinality:s.cardinality,optionality:e.isOptional,casing:s["casing"],def:s["definitionSymbol"]||n})}else{e.matches.splice(i++,0,{fn:null,cardinality:0,optionality:e.isOptional,casing:null,def:n});r=false}}if(t.numericInput){e=e.split("").reverse().join("")}var n=/(?:[?*+]|\{[0-9]+(?:,[0-9]*)?\})\??|[^.?*+^${[]()|\\]+|./g,r=false;var u=new s,a,f,l=[];i=[];while(a=n.exec(e)){f=a[0];switch(f.charAt(0)){case t.optionalmarker.end:case t.groupmarker.end:var c=l.pop();if(l.length>0){l[l.length-1]["matches"].push(c)}else{u.matches.push(c)}break;case t.optionalmarker.start:l.push(new s(false,true));break;case t.groupmarker.start:l.push(new s(true));break;case t.quantifiermarker.start:var h=new s(false,false,true);f=f.replace(/[{}]/g,"");var p=f.split(","),d=isNaN(p[0])?p[0]:parseInt(p[0]),v=p.length==1?d:isNaN(p[1])?p[1]:parseInt(p[1]);h.quantifier={min:d,max:v};if(l.length>0){var m=l[l.length-1]["matches"];var a=m.pop();if(!a["isGroup"]){var g=new s(true);g.matches.push(a);a=g}m.push(a);m.push(h)}else{var a=u.matches.pop();if(!a["isGroup"]){var g=new s(true);g.matches.push(a);a=g}u.matches.push(a);u.matches.push(h)}break;case t.escapeChar:r=true;break;default:if(l.length>0){o(l[l.length-1],f)}else{if(u.matches.length>0){var y=u.matches[u.matches.length-1];if(y["isGroup"]){y.isGroup=false;o(y,t.groupmarker.start,0);o(y,t.groupmarker.end)}}o(u,f)}}}if(u.matches.length>0)i.push(u);if(t.repeat>0||t.repeat=="*"||t.repeat=="+"){var b=new s(false,false,false);var g=new s(true),w=new s(false,false,true);w.quantifier=t.repeat=="*"?{min:0,max:"*"}:t.repeat=="+"?{min:1,max:"*"}:{min:t.greedy?t.repeat:1,max:t.repeat};if(i.length>1){g.matches=i;b.matches.push(g);b.matches.push(w)}else{g.matches=i[0].matches;b.matches.push(g);b.matches.push(w)}i=[b]}return i}function o(e){return t.optionalmarker.start+e+t.optionalmarker.end}function u(e){var n=0,r=0,i=e.length;for(var s=0;s<i;s++){if(e.charAt(s)==t.optionalmarker.start){n++}if(e.charAt(s)==t.optionalmarker.end){r++}if(n>0&&n==r)break}var o=[e.substring(0,s)];if(s<i){o.push(e.substring(s+1,i))}return o}function a(e){var n=e.length;for(var r=0;r<n;r++){if(e.charAt(r)==t.optionalmarker.start){break}}var i=[e.substring(0,r)];if(r<n){i.push(e.substring(r+1,n))}return i}function f(t,i,l){var c=u(i);var h;var p=a(c[0]);if(p.length>1){h=t+p[0]+o(p[1])+(c.length>1?c[1]:"");if(e.inArray(h,r)==-1&&h!=""){r.push(h);n.push({mask:h,maskToken:s(h),_buffer:undefined,buffer:undefined,tests:{},validPositions:{},metadata:l})}h=t+p[0]+(c.length>1?c[1]:"");if(e.inArray(h,r)==-1&&h!=""){r.push(h);n.push({mask:h,maskToken:s(h),_buffer:undefined,buffer:undefined,tests:{},validPositions:{},metadata:l})}if(a(p[1]).length>1){f(t+p[0],p[1]+c[1],l)}if(c.length>1&&a(c[1]).length>1){f(t+p[0]+o(p[1]),c[1],l);f(t+p[0],c[1],l)}}else{h=t+c;if(e.inArray(h,r)==-1&&h!=""){r.push(h);n.push({mask:h,maskToken:s(h),validPositions:{},_buffer:undefined,buffer:undefined,tests:{},metadata:l})}}}var n=[];var r=[];var i=[];if(t.repeat=="*"||t.repeat=="+")t.greedy=false;if(e.isFunction(t.mask)){t.mask=t.mask.call(this,t)}if(e.isArray(t.mask)){e.each(t.mask,function(e,t){if(t["mask"]!=undefined){f("",t["mask"].toString(),t)}else{f("",t.toString())}})}else{if(t.mask.length==1&&t.greedy==false&&t.repeat!=0){t.placeholder=""}f("",t.mask.toString())}return t.greedy?n:n.sort(function(e,t){return e["mask"].length-t["mask"].length})}var i=typeof ScriptEngineMajorVersion==="function"?ScriptEngineMajorVersion():(new Function("/*@cc_on return @_jscript_version; @*/"))()>=10,s=navigator.userAgent,o=s.match(new RegExp("iphone","i"))!==null,u=s.match(new RegExp("android.*safari.*","i"))!==null,a=s.match(new RegExp("android.*chrome.*","i"))!==null,f=s.match(new RegExp("android.*firefox.*","i"))!==null,l=/Kindle/i.test(s)||/Silk/i.test(s)||/KFTT/i.test(s)||/KFOT/i.test(s)||/KFJWA/i.test(s)||/KFJWI/i.test(s)||/KFSOWI/i.test(s)||/KFTHWA/i.test(s)||/KFTHWI/i.test(s)||/KFAPWA/i.test(s)||/KFAPWI/i.test(s),c=t("paste")?"paste":t("input")?"input":"propertychange";function h(t,n,r,s){function y(e,t){t=t||0;var n=[],i,s=0,o;do{if(e===true&&b()["validPositions"][s]){var u=b()["validPositions"][s];o=u["match"];i=u["locator"].slice();n.push(o["fn"]==null?o["def"]:r.placeholder.charAt(s%r.placeholder.length))}else{var a=x(s,false,i,s-1);a=a[r.greedy||t>s?0:a.length-1];o=a["match"];i=a["locator"].slice();n.push(o["fn"]==null?o["def"]:r.placeholder.charAt(s%r.placeholder.length))}s++}while(o["fn"]!=null||o["fn"]==null&&o["def"]!=""||t>=s);n.pop();return n}function b(){return t[n]}function w(e,t){e=e||b();var n=-1;for(var r in e["validPositions"]){var i=parseInt(r);if(i>n)n=i}return n}function E(e,t){t=t||b();for(var n in t["validPositions"]){if(n>e)t["validPositions"][n]=undefined}}function S(e){if(b()["validPositions"][e]){return b()["validPositions"][e]["match"]}return x(e)[0]["match"]}function x(e,t,n,i){function f(t,n,i,s){function u(i,s,c){var h=o;if(o==e&&i.matches==undefined){a.push({match:i,locator:s.reverse()});return true}else if(i.matches!=undefined){if(i.isGroup&&c!==true){i=u(t.matches[l+1],s);if(i)return true}else if(i.isOptional){i=f(i,n,s,c);if(i){o=h}}else if(i.isQuantifier&&c!==true){var p=i;for(var d=n.length>0&&c!==true?n.shift():0;d<(isNaN(p.quantifier.max)?d+1:p.quantifier.max)&&o<=e;d++){var v=t.matches[t.matches.indexOf(p)-1];i=u(v,[d].concat(s),true);if(i){var m=a[a.length-1]["match"];var g=v.matches.indexOf(m)==0;if(g){if((isNaN(p.quantifier.max)||r.greedy===false)&&d>=p.quantifier.min){a.push({match:{fn:null,cardinality:0,optionality:true,casing:null,def:""},locator:[]});return true}else if(d==p.quantifier.max-1)o=h;else return true}else{return true}}}}else{i=f(i,n,s,c);if(i)return true}}else o++}for(var l=n.length>0?n.shift():0;l<t.matches.length;l++){if(t.matches[l]["isQuantifier"]!==true){var c=u(t.matches[l],[l].concat(i),s);if(c&&o==e){return c}else if(o>e){break}}}}var s=b()["maskToken"],o=n?i:0,u=n||[0],a=[];if(t!==true&&b()["tests"][e]&&!b()["validPositions"][e]){return b()["tests"][e]}if(n==undefined){var l=e-1,c;while((c=b()["validPositions"][l])==undefined&&l>-1){l--}if(c!=undefined&&l>-1){o=l;u=c["locator"].slice()}else{l=e-1;while((c=b()["tests"][l])==undefined&&l>-1){l--}if(c!=undefined&&l>-1){o=l;u=c[0]["locator"].slice()}}}for(var h=u.shift();h<s.length;h++){var p=f(s[h],u,[h]);if(p&&o==e||o>e){break}}if(a.length==0)a.push({match:{fn:null,cardinality:0,optionality:true,casing:null,def:""},locator:[]});b()["tests"][e]=a;return a}function T(){if(b()["_buffer"]==undefined){b()["_buffer"]=y(false,1)}return b()["_buffer"]}function N(){if(b()["buffer"]==undefined){b()["buffer"]=y(true)}return b()["buffer"]}function C(i,s,o){function u(t,n,i){var s=false;e.each(x(t,!i),function(o,u){var a=u["match"];var f=n?1:0,l="",c=N();for(var h=a.cardinality;h>f;h--){l+=D(c,t-(h-1),true)}if(n){l+=n}s=a.fn!=null?a.fn.test(l,c,t,i,r):n==F(t)||n==r.skipOptionalPartCharacter?{refresh:true,c:F(t),pos:t}:false;if(s!==false){var p=n;switch(a.casing){case"upper":p=p.toUpperCase();break;case"lower":p=p.toLowerCase();break}var d=t;if(s!==true&&s["pos"]!=t){d=s["pos"];u=x(d,!i)[0]}b()["validPositions"][d]=e.extend({},u,{input:p});return false}});return s}function a(r,o){var a=false;e.each(o,function(t,n){a=e.inArray(n["activeMasksetIndex"],r)==-1&&n["result"]!==false;if(a)return false});if(a){o=e.map(o,function(n,i){if(e.inArray(n["activeMasksetIndex"],r)==-1){return n}else{E(p,t[n["activeMasksetIndex"]])}})}else{var f=-1,l=-1,c;e.each(o,function(t,n){if(e.inArray(n["activeMasksetIndex"],r)!=-1&&n["result"]!==false&(f==-1||f>n["result"]["pos"])){f=n["result"]["pos"];l=n["activeMasksetIndex"]}});o=e.map(o,function(o,a){if(e.inArray(o["activeMasksetIndex"],r)!=-1){if(o["result"]["pos"]==f){return o}else if(o["result"]!==false){n=o["activeMasksetIndex"];for(var h=i;h<f;h++){c=u(h,t[l]["buffer"][h],true);if(c===false){E(f-1);break}else{_(N(),h,t[l]["buffer"][h]);E(h)}}c=u(f,s,true);if(c!==false){_(N(),f,s);E(f)}return o}}})}return o}o=o===true;if(o){var f=u(i,s,o);if(f===true){f={pos:i}}return f}var l=[],f=false,c=n,h=N().slice(),p=w(),d=M(i),v=[];e.each(t,function(e,t){if(typeof t=="object"){n=e;var r=i;var a=w(),d;if(a==p){if(r-p>1){for(var m=a==-1?0:a;m<r;m++){d=u(m,h[m],true);if(d===false){break}else{_(N(),m,h[m]);if(d===true){d={pos:m}}}}}if(!L(r)&&!u(r,s,o)){var g=O(r)-r;for(var y=0;y<g;y++){if(u(++r,s,o)!==false)break}v.push(n)}}if(w()>=p||n==c){if(r>=0&&r<A()){f=u(r,s,o);if(f!==false){if(f===true){f={pos:r}}}l.push({activeMasksetIndex:e,result:f})}}}});var m=a(v,l);n=c;return m}function k(){var r=n,i={activeMasksetIndex:0,lastValidPosition:-1,next:-1};e.each(t,function(e,t){if(typeof t=="object"){n=e;if(w()>i["lastValidPosition"]){i["activeMasksetIndex"]=e;i["lastValidPosition"]=w();i["next"]=O(w())}else if(w()==i["lastValidPosition"]&&(i["next"]==-1||i["next"]>O(w()))){i["activeMasksetIndex"]=e;i["lastValidPosition"]=w();i["next"]=O(w())}}});n=i["lastValidPosition"]!=-1&&w(t[r])==i["lastValidPosition"]?r:i["activeMasksetIndex"];if(r!=n){B(N(),O(i["lastValidPosition"]),A());b()["writeOutBuffer"]=true}d.data("_inputmask")["activeMasksetIndex"]=n}function L(e){var t=S(e);return t.fn!=null?t.fn:false}function A(){var e=d.prop("maxLength"),t;if(r.greedy==false){var n=w()+1,i=S(n);while(i.fn!=null&&i.def!=""){var s=x(++n);i=s[s.length-1]}t=y(false,n).length}else t=N().length;return e==undefined||t<e&&e>-1?t:e}function O(e){var t=A();if(e>=t)return t;var n=e;while(++n<t&&!L(n)){}return n}function M(e){var t=e;if(t<=0)return 0;while(--t>0&&!L(t)){}return t}function _(e,t,n){t=P(e,t);var r=S(t);var i=n;if(i!=undefined&&r!=undefined){switch(r.casing){case"upper":i=n.toUpperCase();break;case"lower":i=n.toLowerCase();break}}e[t]=i}function D(e,t){t=P(e,t);return e[t]}function P(e,t){if(e.length<=t){var n=y(true,t);e.length=n.length;for(var r=0,i=e.length;r<i;r++){if(e[r]==undefined)e[r]=n[r]}e[t]=F(t)}return t}function H(e,t,n){e._valueSet(t.join(""));if(n!=undefined){X(e,n)}}function B(e,t,n,r){for(var i=t,s=A();i<n&&i<s;i++){if(r===true){if(!L(i))_(e,i,"")}else _(e,i,F(i))}}function j(e){_(N(),e,F(e))}function F(e){var t=S(e);return t["fn"]==null?t["def"]:r.placeholder.charAt(e%r.placeholder.length)}function I(r,i,s,o,u){var a=o!=undefined?o.slice():R(r._valueGet()).split("");e.each(t,function(e,t){if(typeof t=="object"){t["buffer"]=undefined;t["_buffer"]=undefined;t["validPositions"]={};t["p"]=-1}});if(s!==true)n=0;if(i)r._valueSet("");e.each(a,function(t,n){if(u===true){var o=b()["p"],a=o==-1?o:M(o),f=a==-1?t:O(a);if(e.inArray(n,T().slice(a+1,f))==-1){tt.call(r,undefined,true,n.charCodeAt(0),i,s,t)}}else{tt.call(r,undefined,true,n.charCodeAt(0),i,s,t);s=s||t>0&&t>b()["p"]}})}function q(t){return e.inputmask.escapeRegex.call(this,t)}function R(e){return e.replace(new RegExp("("+q(T().join(""))+")*$"),"")}function U(e){var t=N(),n=t.slice(),r,i;for(var i=n.length-1;i>=0;i--){if(S(i).optionality){if(!L(i)||!C(i,t[i],true))n.pop();else break}else break}H(e,n)}function z(t,n){if(t.data("_inputmask")&&(n===true||!t.hasClass("hasDatepicker"))){var i=e.map(N(),function(e,t){return L(t)&&C(t,e,true)?e:null});var s=(h?i.reverse():i).join("");return e.isFunction(r.onUnMask)?r.onUnMask.call(t,N().join(""),s,r):s}else{return t[0]._valueGet()}}function W(e){if(h&&typeof e=="number"&&(!r.greedy||r.placeholder!="")){var t=N().length;e=t-e}return e}function X(t,n,i){var s=t.jquery&&t.length>0?t[0]:t,o;if(typeof n=="number"){n=W(n);i=W(i);if(!e(s).is(":visible")){return}i=typeof i=="number"?i:n;s.scrollLeft=s.scrollWidth;if(r.insertMode==false&&n==i)i++;if(s.setSelectionRange){s.selectionStart=n;s.selectionEnd=i}else if(s.createTextRange){o=s.createTextRange();o.collapse(true);o.moveEnd("character",i);o.moveStart("character",n);o.select()}}else{if(!e(t).is(":visible")){return{begin:0,end:0}}if(s.setSelectionRange){n=s.selectionStart;i=s.selectionEnd}else if(document.selection&&document.selection.createRange){o=document.selection.createRange();n=0-o.duplicate().moveStart("character",-1e5);i=n+o.text.length}n=W(n);i=W(i);return{begin:n,end:i}}}function V(i){if(e.isFunction(r.isComplete))return r.isComplete.call(d,i,r);if(r.repeat=="*")return undefined;var s=false,o=0,u=n;e.each(t,function(e,t){if(typeof t=="object"){n=e;var r=M(A());if(w()>=o&&w()==r){var u=true;for(var a=0;a<=r;a++){var f=L(a);if(f&&(i[a]==undefined||i[a]==F(a))||!f&&i[a]!=F(a)){u=false;break}}s=s||u;if(s)return false}o=w()}});n=u;return s}function J(e,t){return h?e-t>1||e-t==1&&r.insertMode:t-e>1||t-e==1&&r.insertMode}function K(t){var n=e._data(t).events;e.each(n,function(t,n){e.each(n,function(e,t){if(t.namespace=="inputmask"){if(t.type!="setvalue"){var n=t.handler;t.handler=function(e){if(this.readOnly||this.disabled)e.preventDefault;else return n.apply(this,arguments)}}}})})}function Q(t){function n(t){if(e.valHooks[t]==undefined||e.valHooks[t].inputmaskpatch!=true){var n=e.valHooks[t]&&e.valHooks[t].get?e.valHooks[t].get:function(e){return e.value};var r=e.valHooks[t]&&e.valHooks[t].set?e.valHooks[t].set:function(e,t){e.value=t;return e};e.valHooks[t]={get:function(t){var r=e(t);if(r.data("_inputmask")){if(r.data("_inputmask")["opts"].autoUnmask)return r.inputmask("unmaskedvalue");else{var i=n(t),s=r.data("_inputmask"),o=s["masksets"],u=s["activeMasksetIndex"],a=o[u]["_buffer"];a=a?a.join(""):"";return i!=a?i:""}}else return n(t)},set:function(t,n){var i=e(t);var s=r(t,n);if(i.data("_inputmask"))i.triggerHandler("setvalue.inputmask");return s},inputmaskpatch:true}}}var r;if(Object.getOwnPropertyDescriptor)r=Object.getOwnPropertyDescriptor(t,"value");if(r&&r.get){if(!t._valueGet){var i=r.get;var s=r.set;t._valueGet=function(){return h?i.call(this).split("").reverse().join(""):i.call(this)};t._valueSet=function(e){s.call(this,h?e.split("").reverse().join(""):e)};Object.defineProperty(t,"value",{get:function(){var t=e(this),n=e(this).data("_inputmask"),r=n["masksets"],s=n["activeMasksetIndex"];return n&&n["opts"].autoUnmask?t.inputmask("unmaskedvalue"):i.call(this)!=r[s]["_buffer"].join("")?i.call(this):""},set:function(t){s.call(this,t);e(this).triggerHandler("setvalue.inputmask")}})}}else if(document.__lookupGetter__&&t.__lookupGetter__("value")){if(!t._valueGet){var i=t.__lookupGetter__("value");var s=t.__lookupSetter__("value");t._valueGet=function(){return h?i.call(this).split("").reverse().join(""):i.call(this)};t._valueSet=function(e){s.call(this,h?e.split("").reverse().join(""):e)};t.__defineGetter__("value",function(){var t=e(this),n=e(this).data("_inputmask"),r=n["masksets"],s=n["activeMasksetIndex"];return n&&n["opts"].autoUnmask?t.inputmask("unmaskedvalue"):i.call(this)!=r[s]["_buffer"].join("")?i.call(this):""});t.__defineSetter__("value",function(t){s.call(this,t);e(this).triggerHandler("setvalue.inputmask")})}}else{if(!t._valueGet){t._valueGet=function(){return h?this.value.split("").reverse().join(""):this.value};t._valueSet=function(e){this.value=h?e.split("").reverse().join(""):e}}n(t.type)}}function G(e,t,n,i){var s=N();if(i!==false)while(!L(e)&&e-1>=0)e--;for(var o=e;o<t&&o<A();o++){if(L(o)){j(o);var u=O(o);var a=D(s,u);if(a!=F(u)){if(u<A()&&C(o,a,true)!==false&&S(o).def==S(u).def){_(s,o,a);if(u<t){j(u)}}else{if(L(o))break}}}else{j(o)}}if(n!=undefined)_(s,M(t),n);if(r.greedy==false){var f=R(s.join("")).split("");s.length=f.length;for(var o=0,l=s.length;o<l;o++){s[o]=f[o]}if(s.length==0)b()["buffer"]=T().slice()}return e}function Y(e,t,n){var i=N();if(D(i,e)!=F(e)){for(var s=M(t);s>e&&s>=0;s--){if(L(s)){var o=M(s);var u=D(i,o);if(u!=F(o)){if(C(s,u,true)!==false&&S(s).def==S(o).def){_(i,s,u);j(o)}}}else j(s)}}if(n!=undefined&&D(i,e)==F(e))_(i,e,n);var a=i.length;if(r.greedy==false){var f=R(i.join("")).split("");i.length=f.length;for(var s=0,l=i.length;s<l;s++){i[s]=f[s]}if(i.length==0)b()["buffer"]=T().slice()}return t-(a-i.length)}function Z(e,t,n){if(r.numericInput||h){switch(t){case r.keyCode.BACKSPACE:t=r.keyCode.DELETE;break;case r.keyCode.DELETE:t=r.keyCode.BACKSPACE;break}if(h){var i=n.end;n.end=n.begin;n.begin=i}}var s=true;if(n.begin==n.end){var o=t==r.keyCode.BACKSPACE?n.begin-1:n.begin;if(r.isNumeric&&r.radixPoint!=""&&N()[o]==r.radixPoint){n.begin=N().length-1==o?n.begin:t==r.keyCode.BACKSPACE?o:O(o);n.end=n.begin}s=false;if(t==r.keyCode.BACKSPACE)n.begin--;else if(t==r.keyCode.DELETE)n.end++}else if(n.end-n.begin==1&&!r.insertMode){s=false;if(t==r.keyCode.BACKSPACE)n.begin--}B(N(),n.begin,n.end);var u=A();if(r.greedy==false&&(isNaN(r.repeat)||r.repeat>0)){G(n.begin,u,undefined,!h&&t==r.keyCode.BACKSPACE&&!s)}else{var a=n.begin;for(var f=n.begin;f<n.end;f++){if(L(f)||!s)a=G(n.begin,u,undefined,!h&&t==r.keyCode.BACKSPACE&&!s)}if(!s)n.begin=a}var l=O(-1);B(N(),n.begin,n.end,true);I(e,false,false,N());if(w()<l){b()["p"]=l}else{b()["p"]=n.begin}}function et(t){v=false;var n=this,i=e(n),s=t.keyCode,u=X(n);if(s==r.keyCode.BACKSPACE||s==r.keyCode.DELETE||o&&s==127||t.ctrlKey&&s==88){t.preventDefault();if(s==88)p=N().join("");Z(n,s,u);k();H(n,N(),b()["p"]);if(n._valueGet()==T().join(""))i.trigger("cleared");if(r.showTooltip){i.prop("title",b()["mask"])}}else if(s==r.keyCode.END||s==r.keyCode.PAGE_DOWN){setTimeout(function(){var e=O(w());if(!r.insertMode&&e==A()&&!t.shiftKey)e--;X(n,t.shiftKey?u.begin:e,e)},0)}else if(s==r.keyCode.HOME&&!t.shiftKey||s==r.keyCode.PAGE_UP){X(n,0,t.shiftKey?u.begin:0)}else if(s==r.keyCode.ESCAPE||s==90&&t.ctrlKey){I(n,true,false,p.split(""));i.click()}else if(s==r.keyCode.INSERT&&!(t.shiftKey||t.ctrlKey)){r.insertMode=!r.insertMode;X(n,!r.insertMode&&u.begin==A()?u.begin-1:u.begin)}else if(r.insertMode==false&&!t.shiftKey){if(s==r.keyCode.RIGHT){setTimeout(function(){var e=X(n);X(n,e.begin)},0)}else if(s==r.keyCode.LEFT){setTimeout(function(){var e=X(n);X(n,e.begin-1)},0)}}var a=X(n);if(r.onKeyDown.call(this,t,N(),r)===true)X(n,a.begin,a.end);g=e.inArray(s,r.ignorables)!=-1}function tt(i,s,o,u,a,f){if(o==undefined&&v)return false;v=true;var l=this,c=e(l);i=i||window.event;var o=s?o:i.which||i.charCode||i.keyCode;if(s!==true&&!(i.ctrlKey&&i.altKey)&&(i.ctrlKey||i.metaKey||g)){return true}else{if(o){if(s!==true&&o==46&&i.shiftKey==false&&r.radixPoint==",")o=44;var h,p,d,y=String.fromCharCode(o);if(s){var E=a?f:w()+1;h={begin:E,end:E}}else{h=X(l)}var S=J(h.begin,h.end),x=n;if(S){e.each(t,function(e,t){if(typeof t=="object"){n=e;b()["undoBuffer"]=N().join("")}});n=x;Z(l,r.keyCode.DELETE,h);if(!r.insertMode){e.each(t,function(e,t){if(typeof t=="object"){n=e;Y(h.begin,A())}})}n=x}var T=N().join("").indexOf(r.radixPoint);if(r.isNumeric&&s!==true&&T!=-1){if(r.greedy&&h.begin<=T){h.begin=M(h.begin);h.end=h.begin}else if(y==r.radixPoint){h.begin=T;h.end=h.begin}}var L=h.begin;p=C(L,y,a);if(a===true)p=[{activeMasksetIndex:n,result:p}];var P=-1;e.each(p,function(e,t){n=t["activeMasksetIndex"];b()["writeOutBuffer"]=true;var i=t["result"];if(i!==false){var s=false,o=N();if(i!==true){s=i["refresh"];L=i.pos!=undefined?i.pos:L;y=i.c!=undefined?i.c:y}if(s!==true){if(r.insertMode==true){var u=L;if(D(o,L)!=F(L)){while(b()["validPositions"][u]){u=O(u)}}if(u<A()){Y(L,A(),y)}else b()["writeOutBuffer"]=false}else _(o,L,y);if(P==-1||P>O(L)){P=O(L)}}else if(!a){var f=L<A()?L+1:L;if(P==-1||P>f){P=f}}if(P>b()["p"])b()["p"]=P}});if(a!==true){n=x;k()}if(u!==false){e.each(p,function(e,t){if(t["activeMasksetIndex"]==n){d=t;return false}});if(d!=undefined){var B=this;setTimeout(function(){r.onKeyValidation.call(B,d["result"],r)},0);if(b()["writeOutBuffer"]&&d["result"]!==false){var j=N();var I;if(s){I=undefined}else if(r.numericInput){if(L>T){I=M(P)}else if(y==r.radixPoint){I=P-1}else I=M(P-1)}else{I=P}H(l,j,I);if(s!==true){setTimeout(function(){if(V(j)===true)c.trigger("complete");m=true;c.trigger("input")},0)}}else if(S){b()["buffer"]=b()["undoBuffer"].split("")}}else if(S){b()["buffer"]=b()["undoBuffer"].split("")}}if(r.showTooltip){c.prop("title",b()["mask"])}if(i)i.preventDefault?i.preventDefault():i.returnValue=false}}}function nt(t){var n=e(this),i=this,s=t.keyCode,o=N();r.onKeyUp.call(this,t,o,r);if(s==r.keyCode.TAB&&r.showMaskOnFocus){if(n.hasClass("focus.inputmask")&&i._valueGet().length==0){o=T().slice();H(i,o);X(i,0);p=N().join("")}else{H(i,o);if(o.join("")==T().join("")&&e.inArray(r.radixPoint,o)!=-1){X(i,W(0));n.click()}else X(i,W(0),W(A()))}}}function rt(t){if(m===true&&t.type=="input"){m=false;return true}var n=this,i=e(n);if(t.type=="propertychange"&&n._valueGet().length<=A()){return true}setTimeout(function(){var t=e.isFunction(r.onBeforePaste)?r.onBeforePaste.call(n,n._valueGet(),r):n._valueGet();I(n,false,false,t.split(""),true);H(n,N());if(V(N())===true)i.trigger("complete");i.click()},0)}function it(t){var n=this,i=e(n);var s=X(n),o=n._valueGet();o=o.replace(new RegExp("("+q(T().join(""))+")*"),"");if(s.begin>o.length){X(n,o.length);s=X(n)}if(N().length-o.length==1&&o.charAt(s.begin)!=N()[s.begin]&&o.charAt(s.begin+1)!=N()[s.begin]&&!L(s.begin)){t.keyCode=r.keyCode.BACKSPACE;et.call(n,t)}else{I(n,false,false,o.split(""));H(n,N());if(V(N())===true)i.trigger("complete");i.click()}t.preventDefault()}function st(s){d=e(s);if(d.is(":input")){d.data("_inputmask",{masksets:t,activeMasksetIndex:n,opts:r,isRTL:false});if(r.showTooltip){d.prop("title",b()["mask"])}r.greedy=r.greedy?r.greedy:r.repeat==0;Q(s);if(r.numericInput)r.isNumeric=r.numericInput;if(s.dir=="rtl"||r.numericInput&&r.rightAlignNumerics||r.isNumeric&&r.rightAlignNumerics)d.css("text-align","right");if(s.dir=="rtl"||r.numericInput){s.dir="ltr";d.removeAttr("dir");var o=d.data("_inputmask");o["isRTL"]=true;d.data("_inputmask",o);h=true}d.unbind(".inputmask");d.removeClass("focus.inputmask");d.closest("form").bind("submit",function(){if(p!=N().join("")){d.change()}}).bind("reset",function(){setTimeout(function(){d.trigger("setvalue")},0)});d.bind("mouseenter.inputmask",function(){var t=e(this),n=this;if(!t.hasClass("focus.inputmask")&&r.showMaskOnHover){if(n._valueGet()!=N().join("")){H(n,N())}}}).bind("blur.inputmask",function(){var i=e(this),s=this,o=s._valueGet(),u=N();i.removeClass("focus.inputmask");if(p!=N().join("")){i.change()}if(r.clearMaskOnLostFocus&&o!=""){if(o==T().join(""))s._valueSet("");else{U(s)}}if(V(u)===false){i.trigger("incomplete");if(r.clearIncomplete){e.each(t,function(e,t){if(typeof t=="object"){t["buffer"]=undefined;t["validPositions"]={}}});n=0;if(r.clearMaskOnLostFocus)s._valueSet("");else{u=T().slice();H(s,u)}}}}).bind("focus.inputmask",function(){var t=e(this),n=this,i=n._valueGet();if(r.showMaskOnFocus&&!t.hasClass("focus.inputmask")&&(!r.showMaskOnHover||r.showMaskOnHover&&i=="")){if(n._valueGet()!=N().join("")){H(n,N(),O(w()))}}t.addClass("focus.inputmask");p=N().join("")}).bind("mouseleave.inputmask",function(){var t=e(this),n=this;if(r.clearMaskOnLostFocus){if(!t.hasClass("focus.inputmask")&&n._valueGet()!=t.attr("placeholder")){if(n._valueGet()==T().join("")||n._valueGet()=="")n._valueSet("");else{U(n)}}}}).bind("click.inputmask",function(){var t=this;setTimeout(function(){var n=X(t),i=N();if(n.begin==n.end){var s=h?W(n.begin):n.begin,o=w(undefined,s),u;if(r.isNumeric){u=r.skipRadixDance===false&&r.radixPoint!=""&&e.inArray(r.radixPoint,i)!=-1?r.numericInput?O(e.inArray(r.radixPoint,i)):e.inArray(r.radixPoint,i):O(o)}else{u=O(o)}if(s<u){if(L(s))X(t,s);else X(t,O(s))}else X(t,u)}},0)}).bind("dblclick.inputmask",function(){var e=this;setTimeout(function(){X(e,0,O(w()))},0)}).bind(c+".inputmask dragdrop.inputmask drop.inputmask",rt).bind("setvalue.inputmask",function(){var e=this;I(e,true);p=N().join("");if(e._valueGet()==T().join(""))e._valueSet("")}).bind("complete.inputmask",r.oncomplete).bind("incomplete.inputmask",r.onincomplete).bind("cleared.inputmask",r.oncleared);d.bind("keydown.inputmask",et).bind("keypress.inputmask",tt).bind("keyup.inputmask",nt);if(u||f||a||l){d.attr("autocomplete","off").attr("autocorrect","off").attr("autocapitalize","off").attr("spellcheck",false);if(f||l){d.unbind("keydown.inputmask",et).unbind("keypress.inputmask",tt).unbind("keyup.inputmask",nt);if(c=="input"){d.unbind(c+".inputmask")}d.bind("input.inputmask",it)}}if(i)d.bind("input.inputmask",rt);var v=e.isFunction(r.onBeforeMask)?r.onBeforeMask.call(s,s._valueGet(),r):s._valueGet();I(s,true,false,v.split(""));p=N().join("");var m;try{m=document.activeElement}catch(g){}if(m===s){d.addClass("focus.inputmask");X(s,O(w()))}else if(r.clearMaskOnLostFocus){if(N().join("")==T().join("")){s._valueSet("")}else{U(s)}}else{H(s,N())}K(s)}}var h=false,p=N().join(""),d,v=false,m=false,g=false;if(s!=undefined){switch(s["action"]){case"isComplete":return V(s["buffer"]);case"unmaskedvalue":h=s["$input"].data("_inputmask")["isRTL"];return z(s["$input"],s["skipDatepickerCheck"]);case"mask":st(s["el"]);break;case"format":d=e({});d.data("_inputmask",{masksets:t,activeMasksetIndex:n,opts:r,isRTL:r.numericInput});if(r.numericInput){r.isNumeric=r.numericInput;h=true}I(d,false,false,s["value"].split(""),true);return N().join("");case"isValid":d=e({});d.data("_inputmask",{masksets:t,activeMasksetIndex:n,opts:r,isRTL:r.numericInput});if(r.numericInput){r.isNumeric=r.numericInput;h=true}I(d,false,true,s["value"].split(""));return V(N())}}}e.inputmask={defaults:{placeholder:"_",optionalmarker:{start:"[",end:"]"},quantifiermarker:{start:"{",end:"}"},groupmarker:{start:"(",end:")"},escapeChar:"\\",mask:null,oncomplete:e.noop,onincomplete:e.noop,oncleared:e.noop,repeat:0,greedy:true,autoUnmask:false,clearMaskOnLostFocus:true,insertMode:true,clearIncomplete:false,aliases:{},onKeyUp:e.noop,onKeyDown:e.noop,onBeforeMask:undefined,onBeforePaste:undefined,onUnMask:undefined,showMaskOnFocus:true,showMaskOnHover:true,onKeyValidation:e.noop,skipOptionalPartCharacter:" ",showTooltip:false,numericInput:false,isNumeric:false,radixPoint:"",skipRadixDance:false,rightAlignNumerics:true,definitions:{9:{validator:"[0-9]",cardinality:1,definitionSymbol:"*"},a:{validator:"[A-Za-zА-яЁё]",cardinality:1,definitionSymbol:"*"},"*":{validator:"[A-Za-zА-яЁё0-9]",cardinality:1}},keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91},ignorables:[8,9,13,19,27,33,34,35,36,37,38,39,40,45,46,93,112,113,114,115,116,117,118,119,120,121,122,123],isComplete:undefined},escapeRegex:function(e){var t=["/",".","*","+","?","|","(",")","[","]","{","}","\\"];return e.replace(new RegExp("(\\"+t.join("|\\")+")","gim"),"\\$1")},format:function(t,i){var s=e.extend(true,{},e.inputmask.defaults,i);n(s.alias,i,s);return h(r(s),0,s,{action:"format",value:t})},isValid:function(t,i){var s=e.extend(true,{},e.inputmask.defaults,i);n(s.alias,i,s);return h(r(s),0,s,{action:"isValid",value:t})}};e.fn.inputmask=function(t,i){var s=e.extend(true,{},e.inputmask.defaults,i),o,u=0;if(typeof t==="string"){switch(t){case"mask":n(s.alias,i,s);o=r(s);if(o.length==0){return this}return this.each(function(){h(e.extend(true,{},o),0,s,{action:"mask",el:this})});case"unmaskedvalue":var a=e(this),f=this;if(a.data("_inputmask")){o=a.data("_inputmask")["masksets"];u=a.data("_inputmask")["activeMasksetIndex"];s=a.data("_inputmask")["opts"];return h(o,u,s,{action:"unmaskedvalue",$input:a})}else return a.val();case"remove":return this.each(function(){var t=e(this),n=this;if(t.data("_inputmask")){o=t.data("_inputmask")["masksets"];u=t.data("_inputmask")["activeMasksetIndex"];s=t.data("_inputmask")["opts"];n._valueSet(h(o,u,s,{action:"unmaskedvalue",$input:t,skipDatepickerCheck:true}));t.removeData("_inputmask");t.unbind(".inputmask");t.removeClass("focus.inputmask");var r;if(Object.getOwnPropertyDescriptor)r=Object.getOwnPropertyDescriptor(n,"value");if(r&&r.get){if(n._valueGet){Object.defineProperty(n,"value",{get:n._valueGet,set:n._valueSet})}}else if(document.__lookupGetter__&&n.__lookupGetter__("value")){if(n._valueGet){n.__defineGetter__("value",n._valueGet);n.__defineSetter__("value",n._valueSet)}}try{delete n._valueGet;delete n._valueSet}catch(i){n._valueGet=undefined;n._valueSet=undefined}}});break;case"getemptymask":if(this.data("_inputmask")){o=this.data("_inputmask")["masksets"];u=this.data("_inputmask")["activeMasksetIndex"];return o[u]["_buffer"].join("")}else return"";case"hasMaskedValue":return this.data("_inputmask")?!this.data("_inputmask")["opts"].autoUnmask:false;case"isComplete":o=this.data("_inputmask")["masksets"];u=this.data("_inputmask")["activeMasksetIndex"];s=this.data("_inputmask")["opts"];return h(o,u,s,{action:"isComplete",buffer:this[0]._valueGet().split("")});case"getmetadata":if(this.data("_inputmask")){o=this.data("_inputmask")["masksets"];u=this.data("_inputmask")["activeMasksetIndex"];return o[u]["metadata"]}else return undefined;default:if(!n(t,i,s)){s.mask=t}o=r(s);if(o.length==0){return this}return this.each(function(){h(e.extend(true,{},o),u,s,{action:"mask",el:this})});break}}else if(typeof t=="object"){s=e.extend(true,{},e.inputmask.defaults,t);n(s.alias,t,s);o=r(s);if(o.length==0){return this}return this.each(function(){h(e.extend(true,{},o),u,s,{action:"mask",el:this})})}else if(t==undefined){return this.each(function(){var t=e(this).attr("data-inputmask");if(t&&t!=""){try{t=t.replace(new RegExp("'","g"),'"');var r=e.parseJSON("{"+t+"}");e.extend(true,r,i);s=e.extend(true,{},e.inputmask.defaults,r);n(s.alias,r,s);s.alias=undefined;e(this).inputmask(s)}catch(o){}}})}}}})(jQuery)
// jquery cookie
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

// $.tmpl
(function(a){var r=a.fn.domManip,d="_tmplitem",q=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,b={},f={},e,p={key:0,data:{}},i=0,c=0,l=[];function g(g,d,h,e){var c={data:e||(e===0||e===false)?e:d?d.data:{},_wrap:d?d._wrap:null,tmpl:null,parent:d||null,nodes:[],calls:u,nest:w,wrap:x,html:v,update:t};g&&a.extend(c,g,{nodes:[],parent:d});if(h){c.tmpl=h;c._ctnt=c._ctnt||c.tmpl(a,c);c.key=++i;(l.length?f:b)[i]=c}return c}a.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(f,d){a.fn[f]=function(n){var g=[],i=a(n),k,h,m,l,j=this.length===1&&this[0].parentNode;e=b||{};if(j&&j.nodeType===11&&j.childNodes.length===1&&i.length===1){i[d](this[0]);g=this}else{for(h=0,m=i.length;h<m;h++){c=h;k=(h>0?this.clone(true):this).get();a(i[h])[d](k);g=g.concat(k)}c=0;g=this.pushStack(g,f,i.selector)}l=e;e=null;a.tmpl.complete(l);return g}});a.fn.extend({tmpl:function(d,c,b){return a.tmpl(this[0],d,c,b)},tmplItem:function(){return a.tmplItem(this[0])},template:function(b){return a.template(b,this[0])},domManip:function(d,m,k){if(d[0]&&a.isArray(d[0])){var g=a.makeArray(arguments),h=d[0],j=h.length,i=0,f;while(i<j&&!(f=a.data(h[i++],"tmplItem")));if(f&&c)g[2]=function(b){a.tmpl.afterManip(this,b,k)};r.apply(this,g)}else r.apply(this,arguments);c=0;!e&&a.tmpl.complete(b);return this}});a.extend({tmpl:function(d,h,e,c){var i,k=!c;if(k){c=p;d=a.template[d]||a.template(null,d);f={}}else if(!d){d=c.tmpl;b[c.key]=c;c.nodes=[];c.wrapped&&n(c,c.wrapped);return a(j(c,null,c.tmpl(a,c)))}if(!d)return[];if(typeof h==="function")h=h.call(c||{});e&&e.wrapped&&n(e,e.wrapped);i=a.isArray(h)?a.map(h,function(a){return a?g(e,c,d,a):null}):[g(e,c,d,h)];return k?a(j(c,null,i)):i},tmplItem:function(b){var c;if(b instanceof a)b=b[0];while(b&&b.nodeType===1&&!(c=a.data(b,"tmplItem"))&&(b=b.parentNode));return c||p},template:function(c,b){if(b){if(typeof b==="string")b=o(b);else if(b instanceof a)b=b[0]||{};if(b.nodeType)b=a.data(b,"tmpl")||a.data(b,"tmpl",o(b.innerHTML));return typeof c==="string"?(a.template[c]=b):b}return c?typeof c!=="string"?a.template(null,c):a.template[c]||a.template(null,q.test(c)?c:a(c)):null},encode:function(a){return(""+a).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}});a.extend(a.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){__=__.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(__,$1,$2);__=[];",close:"call=$item.calls();__=call._.concat($item.wrap(call,__));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},"if":{open:"if(($notnull_1) && $1a){",close:"}"},"else":{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){__.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){__.push($.encode($1a));}"},"!":{open:""}},complete:function(){b={}},afterManip:function(f,b,d){var e=b.nodeType===11?a.makeArray(b.childNodes):b.nodeType===1?[b]:[];d.call(f,b);m(e);c++}});function j(e,g,f){var b,c=f?a.map(f,function(a){return typeof a==="string"?e.key?a.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+d+'="'+e.key+'" $2'):a:j(a,e,a._ctnt)}):e;if(g)return c;c=c.join("");c.replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(f,c,e,d){b=a(e).get();m(b);if(c)b=k(c).concat(b);if(d)b=b.concat(k(d))});return b?b:k(c)}function k(c){var b=document.createElement("div");b.innerHTML=c;return a.makeArray(b.childNodes)}function o(b){return new Function("jQuery","$item","var $=jQuery,call,__=[],$data=$item.data;with($data){__.push('"+a.trim(b).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(m,l,k,g,b,c,d){var j=a.tmpl.tag[k],i,e,f;if(!j)throw"Unknown template tag: "+k;i=j._default||[];if(c&&!/\w$/.test(b)){b+=c;c=""}if(b){b=h(b);d=d?","+h(d)+")":c?")":"";e=c?b.indexOf(".")>-1?b+h(c):"("+b+").call($item"+d:b;f=c?e:"(typeof("+b+")==='function'?("+b+").call($item):("+b+"))"}else f=e=i.$1||"null";g=h(g);return"');"+j[l?"close":"open"].split("$notnull_1").join(b?"typeof("+b+")!=='undefined' && ("+b+")!=null":"true").split("$1a").join(f).split("$1").join(e).split("$2").join(g||i.$2||"")+"__.push('"})+"');}return __;")}function n(c,b){c._wrap=j(c,true,a.isArray(b)?b:[q.test(b)?b:a(b).html()]).join("")}function h(a){return a?a.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function s(b){var a=document.createElement("div");a.appendChild(b.cloneNode(true));return a.innerHTML}function m(o){var n="_"+c,k,j,l={},e,p,h;for(e=0,p=o.length;e<p;e++){if((k=o[e]).nodeType!==1)continue;j=k.getElementsByTagName("*");for(h=j.length-1;h>=0;h--)m(j[h]);m(k)}function m(j){var p,h=j,k,e,m;if(m=j.getAttribute(d)){while(h.parentNode&&(h=h.parentNode).nodeType===1&&!(p=h.getAttribute(d)));if(p!==m){h=h.parentNode?h.nodeType===11?0:h.getAttribute(d)||0:0;if(!(e=b[m])){e=f[m];e=g(e,b[h]||f[h]);e.key=++i;b[i]=e}c&&o(m)}j.removeAttribute(d)}else if(c&&(e=a.data(j,"tmplItem"))){o(e.key);b[e.key]=e;h=a.data(j.parentNode,"tmplItem");h=h?h.key:0}if(e){k=e;while(k&&k.key!=h){k.nodes.push(j);k=k.parent}delete e._ctnt;delete e._wrap;a.data(j,"tmplItem",e)}function o(a){a=a+n;e=l[a]=l[a]||g(e,b[e.parent.key+n]||e.parent)}}}function u(a,d,c,b){if(!a)return l.pop();l.push({_:a,tmpl:d,item:this,data:c,options:b})}function w(d,c,b){return a.tmpl(a.template(d),c,b,this)}function x(b,d){var c=b.options||{};c.wrapped=d;return a.tmpl(a.template(b.tmpl),b.data,c,b.item)}function v(d,c){var b=this._wrap;return a.map(a(a.isArray(b)?b.join(""):b).filter(d||"*"),function(a){return c?a.innerText||a.textContent:a.outerHTML||s(a)})}function t(){var b=this.nodes;a.tmpl(null,null,null,this).insertBefore(b[0]);a(b).remove()}})(jQuery);
// $.browser fix
!function(a,b){"use strict";var c,d;if(a.uaMatch=function(a){a=a.toLowerCase();var b=/(opr)[\/]([\w.]+)/.exec(a)||/(chrome)[ \/]([\w.]+)/.exec(a)||/(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("trident")>=0&&/(rv)(?::| )([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[],c=/(ipad)/.exec(a)||/(iphone)/.exec(a)||/(android)/.exec(a)||/(windows phone)/.exec(a)||/(win)/.exec(a)||/(mac)/.exec(a)||/(linux)/.exec(a)||[];return{browser:b[3]||b[1]||"",version:b[2]||"0",platform:c[0]||""}},c=a.uaMatch(b.navigator.userAgent),d={},c.browser&&(d[c.browser]=!0,d.version=c.version,d.versionNumber=parseInt(c.version)),c.platform&&(d[c.platform]=!0),(d.android||d.ipad||d.iphone||d["windows phone"])&&(d.mobile=!0),(d.mac||d.linux||d.win)&&(d.desktop=!0),(d.chrome||d.opr||d.safari)&&(d.webkit=!0),d.rv){var e="msie";c.browser=e,d[e]=!0}if(d.opr){var f="opera";c.browser=f,d[f]=!0}if(d.safari&&d.android){var g="android";c.browser=g,d[g]=!0}d.name=c.browser,d.platform=c.platform,a.browser=d}(jQuery,window);
