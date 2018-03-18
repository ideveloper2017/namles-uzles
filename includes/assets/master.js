(function ($) {
    $.Master = function (settings) {
        var config = {
			weekstart : 0,
			contentPlugins: {},
			editor : 1,
			editorCss : '',
            lang: {
                button_text: "Choose file...",
                empty_text: "No file...",
				monthsFull : '',
				monthsShort : '',
				weeksFull : '',
				weeksShort : '',
				today : "Today",
				clear : "Clear",
				
				delMsg1: "Are you sure you want to delete this record?",
				delMsg2: "This action cannot be undone!!!",
				working: "working..."
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        var itemid = ($.url(true).param('id')) ? $.url(true).param('id') : 0;
        var plugname = $.url(true).param('plugname');
        var modname = $.url(true).param('modname');
        var posturl = (plugname ? "plugins/" + plugname + "/controller.php" : (modname ? "modules/" + modname + "/controller.php" : "controller.php"));
        //$(document).ready(function () {

            /* == Side Menu == */
            $("nav > ul > li > a.collapsed + ul").hide();
            $("nav > ul > li > a").click(function () {
                $(this).toggleClass("expanded").toggleClass("collapsed").find("+ ul").slideToggle(100);
            });

            $("select:not(.picker__select--month, .picker__select--year)").chosen({
                disable_search_threshold: 10,
                width: "100%"
            });

            $('.wojo.dropdown').dropdown();
            $('body [data-content]').popover({
                trigger: 'hover',
                placement: 'auto'
            });

            $("table.sortable").tablesort();

            $(".filefield").filestyle({buttonText: config.lang.button_text});
            
            $('body [data-datepicker]').pickadate({
				firstDay: config.weekstart,
                formatSubmit: 'yyyy-mm-dd',
                monthsFull: config.lang.monthsFull,
                monthsShort: config.lang.monthsShort,
                weekdaysFull: config.lang.weeksFull,
                weekdaysShort: config.lang.weeksShort,
				today: config.lang.today,
				clear: config.lang.clear,
            });
            $('body [data-timepicker]').pickatime({
                formatSubmit: 'HH:i:00'
            });
            /* == Lightbox == */
            $('.lightbox').swipebox();

            /* == Scrollbox == */
            $(".chosen-results, .scrollbox").enscroll({
                showOnHover: true,
                addPaddingToPane: false,
                verticalTrackClass: 'scrolltrack',
                verticalHandleClass: 'scrollhandle'
            });

            function resizeMenu() {
                windowWidth = $(window).width();
                if (windowWidth < 1025 && windowWidth > 769) {
                    $('#sidemenu').addClass("thin").removeClass("slim")
                } else if (windowWidth < 769) {
                    $('#sidemenu').addClass("slim").removeClass("thin")
                } else {
                    $('#sidemenu').removeClass("thin slim")
                }
                $('#sidemenu').enscroll({
                    addPaddingToPane: false
                })
            }

            resizeMenu();
            $(window).resize(function () {
                resizeMenu();
            });

            /* == Help Sidebar == */
            $('body').on('click', '.helper', function () {
                var div = $(this).data('help');
                $('#helpbar').sidebar('toggle').addClass('loading');
                setTimeout(function () {
                    $('#helpbar').load('help/help.php #' + div + '-help');
                    $('#helpbar').removeClass('loading');
                }, 500);
                $('#helpbar').enscroll({
                    addPaddingToPane: false
                });
            })

            /* == Close Message == */
            $('body').on('click', '.message i.close.icon', function () {
                var $msgbox = $(this).closest('.message')
                $msgbox.slideUp(500, function () {
                    $(this).remove()
                });
            });

            /* == Close Note == */
            $('body').on('click', '.note i.close.icon', function () {
                var $msgbox = $(this).closest('.note')
                $msgbox.slideUp(500, function () {
                    $(this).remove()
                });
            });
			
            /* == Language Switcher == */
			$('.langmenu').on('click', 'a', function () {
				var target = $(this).attr('href');
				$.cookie("LANG_CMSPRO", $(this).data('lang'), {
					expires: 120,
					path: '/'
				});
				$('body').fadeOut(1000, function () {
					window.location.href = target;
				});
				return false
			});
	  
            /* == Tabs == */
            $(".tab_content").hide();
            $("#tabs a:first").addClass("active").show();
            $(".tab_content:first").show();
            $("#tabs a").on('click', function () {
                $("#tabs a").removeClass("active");
                $(this).addClass("active");
                $(".tab_content").hide();
                var activeTab = $(this).data("tab");
                $(activeTab).show();
                //return false;
            });

            /* == Toggle Menu icons == */
            $('#scroll-icons').on('click', 'i', function () {
                var micon = $("input[name=icon]");
                $('#scroll-icons i.active').not(this).removeClass('active');
                $(this).toggleClass("active");
                micon.val($(this).hasClass('active') ? $(this).attr('data-icon-name') : "");
            });

            /* == Single File Picker == */
            $('body').on('click', '.filepicker', function () {
                type = $(this).prev('input').data('ext');
                Messi.load('controller.php', {
                    pickFile: 1,
                    ext: type
                }, {
                    title: config.lang.button_text
                });
            });

            $("body").on("click", ".filelist a", function () {
                var path = $(this).data('path');
                $('input[name=filename], input[name=attr]').val(path);
                $('.messi-modal, .messi').remove();

            });

            /* == Editor == */
			if(config.editor === 1) {
				var oPluginMap = config.contentPlugins;
				var oPluginDropdown = {}
				$.each(oPluginMap, function (i, plg) {
					var sPluginName = plg[1];
					var sPluginCode = plg[0];
					oPluginDropdown[i] = {
						title: sPluginName,
						callback: function (obj, e, plugin) {
							this.insertHtml(sPluginCode);
						}
					}
				});
				$('.bodypost').redactor({
					observeLinks: true,
					wym: true,
					toolbarFixed: true,
					minHeight: 200,
					maxHeight: 500,
					css: config.editorCss,
					predefinedLinks: 'controller.php?linktype=internal',
					plugins: ['fullscreen', 'fontcolor', 'fontsize', 'fontfamily'],
				}).redactor('buttonAddBefore', 'fullscreen', 'cplugins', 'Content Plugins', false, oPluginDropdown);
	
				$('.plugpost').redactor({
					observeLinks: true,
					wym: true,
					minHeight: 200,
					maxHeight: 300,
					predefinedLinks: 'controller.php?linktype=internal',
					plugins: ['fullscreen']
				});
				
				$('.altpost').redactor({
					observeLinks: true,
					minHeight: 100,
					buttons: ['formatting', 'bold', 'italic', 'unorderedlist', 'orderedlist', 'outdent', 'indent'],
					wym: true,
					plugins: ['fullscreen']
				});
			} else {
				$('.bodypost').tinymce({
				    theme: "modern",
				    width: "100%",
				    height: 500,
				    convert_urls: 0,
				    remove_script_host: 0,
				    schema: "html5",
					extended_valid_elements : "+a[*],+i[*],+em[*],+li[*],+span[*],+div[*]",
				    visual_table_class: "wojo table",
				    link_list: "controller.php?linktype=editor",
				    plugins: [
				        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
				        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				        "save table contextmenu directionality emoticons paste textcolor"
				    ],
				    content_css: config.editorCss,
				    toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor | cplugs",
					file_browser_callback: function(field_name, url, type, win) {
						var w = window,
							d = document,
							e = d.documentElement,
							g = d.getElementsByTagName('body')[0],
							x = w.innerWidth || e.clientWidth || g.clientWidth,
							y = w.innerHeight || e.clientHeight || g.clientHeight;
						var cmsURL = 'assets/tinymce/plugins/fileman/index.php?&field_name=' + field_name;
						if (type == 'image') {
							cmsURL = cmsURL + "&type=images";
						}
						tinyMCE.activeEditor.windowManager.open({
							file: cmsURL,
							title: 'Filemanager',
							width: x * 0.8,
							height: y * 0.8,
							resizable: "yes",
							close_previous: "no"
						}, {
							window: win,
							input: field_name,
							resizable: "yes",
						});
					},
					setup: function(editor) {
						editor.addButton('cplugs', {
							type: 'menubutton',
							tooltip: 'Plugins',
							icon: 'cplugs',
							menu: config.contentPlugins,
							onselect: function(e) {
								editor.insertContent(e.target._value);
							}
						});
					}
				});
				
				$('.plugpost').tinymce({
				    theme: "modern",
				    width: "100%",
				    height: 300,
				    convert_urls: 0,
				    remove_script_host: 0,
				    schema: "html5",
					extended_valid_elements : "+a[*],+i[*],+em[*],+li[*],+span[*],+div[*]",
				    visual_table_class: "wojo table",
				    link_list: "controller.php?linktype=editor",
				    plugins: [
				        "advlist autolink link image lists",
				        "searchreplace wordcount visualblocks visualchars code fullscreen",
				        "table contextmenu textcolor"
				    ],
				    content_css: config.editorCss,
				    toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor",
					file_browser_callback: function(field_name, url, type, win) {
						var w = window,
							d = document,
							e = d.documentElement,
							g = d.getElementsByTagName('body')[0],
							x = w.innerWidth || e.clientWidth || g.clientWidth,
							y = w.innerHeight || e.clientHeight || g.clientHeight;
						var cmsURL = 'assets/tinymce/plugins/fileman/index.php?&field_name=' + field_name;
						if (type == 'image') {
							cmsURL = cmsURL + "&type=images";
						}
						tinyMCE.activeEditor.windowManager.open({
							file: cmsURL,
							title: 'Filemanager',
							width: x * 0.8,
							height: y * 0.8,
							resizable: "yes",
							close_previous: "no"
						}, {
							window: win,
							input: field_name,
							resizable: "yes",
						});
					}
				});
				$('.altpost').tinymce({
				    theme: "modern",
				    width: "100%",
				    height: 300,
				    convert_urls: 0,
				    remove_script_host: 0,
					menubar : false,
				    schema: "html5",
					extended_valid_elements : "+a[*],+i[*],+em[*],+li[*],+span[*],+div[*]",
				    visual_table_class: "wojo table",
				    link_list: "controller.php?linktype=editor",
				    plugins: [
				        "advlist code fullscreen insertdatetime",
				    ],
				    content_css: config.editorCss,
				    toolbar: "bold italic alignleft aligncenter alignright alignjustify bullist numlist code"
				});
			}

            /* == Submit Search by date == */
            $("#doDates").on('click', function () {
                $("#admin_form").submit();
                return false;
            });

            /* == Master Form == */
            $('body').on('click', 'button[name=dosubmit]', function () {
                function showResponse(json) {
                    $(".wojo.form").removeClass("loading");
                    $("#msgholder").html(json.message);
                }

                function showLoader() {
                    $(".wojo.form").addClass("loading");
                }
                var options = {
                    target: "#msgholder",
                    beforeSubmit: showLoader,
                    success: showResponse,
                    type: "post",
                    url: posturl,
                    dataType: 'json'
                };

                $('#wojo_form').ajaxForm(options).submit();
            });

            /* == Delete Multiple == */
            $('body').on('click', 'button[name=mdelete]', function () {
                function showResponse(json) {
                    $("button[name='mdelete']").removeClass("loading");
                    $('.wojo.table tbody tr').each(function () {
                        if ($(this).find('input:checked').length) {
                            $(this).fadeOut(400, function () {
                                $(this).remove();
                            });
                        }
                    });
                    $("#msgholder").html(json.message);
                }

                function showLoader() {
                    $("button[name='mdelete']").addClass("loading");
                    $('.wojo.table tbody tr').each(function () {
                        if ($(this).find('input:checked').length) {
                            $(this).animate({
                                'backgroundColor': '#FFBFBF'
                            }, 400);
                        }
                    });

                }

                var options = {
                    target: "#msgholder",
                    beforeSubmit: showLoader,
                    success: showResponse,
                    type: "post",
                    url: posturl,
                    dataType: 'json'
                };

                $('#wojo_form').ajaxForm(options).submit();
            });

            /* == Delete Item == */
            $('body').on('click', 'a.delete', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var title = $(this).data('title');
                var option = $(this).data('option');
				var extra = $(this).data('extra');
                var parent = $(this).parent().parent();
                new Messi("<div class=\"messi-warning\"><i class=\"massive icon warn warning sign\"></i></p><p>" + config.lang.delMsg1  + "<br><strong>" + config.lang.delMsg2  + "</strong></p></div>", {
                    title: title,
                    titleClass: '',
                    modal: true,
                    closeButton: true,
                    buttons: [{
                        id: 0,
                        label: 'Delete Record',
                        class: 'negative',
                        val: 'Y'
                    }],
                    callback: function (val) {
                        $.ajax({
                            type: 'post',
                            url: posturl,
                            dataType: 'json',
                            data: {
                                id: id,
                                delete: option,
								extra: extra ? extra : null,
                                title: encodeURIComponent(name)
                            },
                            beforeSend: function () {
                                parent.animate({
                                    'backgroundColor': '#FFBFBF'
                                }, 400);
                            },
                            success: function (json) {
                                parent.fadeOut(400, function () {
                                    parent.remove();
                                });
                                $.sticky(decodeURIComponent(json.message), {
                                    type: json.type,
                                    title: json.title
                                });
                            }

                        });
                    }
                });
            });

            /* == Submit Search by date == */
            $("#doDates").on('click', function () {
                $("#wojo_form").submit();
                return false;
            });

            /* == Inline Edit == */
            $('body').on('focus', 'div[contenteditable=true]:not(.redactor_editor)', function () {
                $(this).data("initialText", $(this).text());
                $('div[contenteditable=true]:not(.redactor_editor)').not(this).removeClass('active');
                $(this).toggleClass("active");
            }).on('blur', 'div[contenteditable=true]:not(.redactor_editor)', function () {
                if ($(this).data("initialText") !== $(this).text()) {
                    title = $(this).text();
                    type = $(this).data("edit-type");
                    id = $(this).data("id")
                    key = $(this).data("key")
                    path = $(this).data("path")
                    $this = $(this);
                    $.ajax({
                        type: "POST",
                        url: posturl,
                        data: ({
                            'title': title,
                            'type': type,
                            'key': key,
                            'path': path,
                            'id': id,
                            'quickedit': 1
                        }),
                        beforeSend: function () {
                            $this.text(config.lang.working).animate({
                                opacity: 0.2
                            }, 800);
                        },
                        success: function (res) {
                            $this.animate({
                                opacity: 1
                            }, 800);
                            setTimeout(function () {
                                $this.html(res).fadeIn("slow");
                            }, 1000);
                        }
                    })
                }
            });
        //});
        $(window).on('resize', function () {
            $(".slrange").ionRangeSlider('update');
        });

        $(document).on('dragover', function (e) {
            var dropZone = $('#drop'),
                timeout = window.dropZoneTimeout;
            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }
            var found = false,
                node = e.target;
            do {
                if (node === dropZone[0]) {
                    found = true;
                    break;
                }
                node = node.parentNode;
            } while (node != null);
            if (found) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        });

        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
    };
})(jQuery);