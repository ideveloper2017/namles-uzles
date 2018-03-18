(function ($) {
    $.Home = function (settings) {
        var config = {
            addNote: "Add New Note",
            enterNote: "Enter note...",
			lat: 43.652527,
			long: -79.381961,
            temp: 'c',
            city: 'Toronto'
        };

        if (settings) {
            $.extend(config, settings);
        }

        $('body').on('click', 'a#newnote', function () {
            var parent = $("#notes");
            var text = "<div class=\"wojo fluid input\"><input type=\"text\" placeholder=\"" + config.enterNote + "\" name=\"note\"></div>";
            text += "<div class=\"wojo divider\"></div>";
            text += "<div class=\"inline-group\">";
            text += "<label class=\"radio\"><input name=\"color\" type=\"radio\" value=\"success\" checked=\"checked\"><i></i><span class=\"wojo success font\">Positive</span></label>";
            text += "<label class=\"radio\"><input name=\"color\" type=\"radio\" value=\"danger\"><i></i><span class=\"wojo danger font\">Negative</span></label>";
            text += "<label class=\"radio\"><input name=\"color\" type=\"radio\" value=\"warning\"><i></i><span class=\"wojo warning font\">Warning</span></label>";
            text += "<label class=\"radio\"><input name=\"color\" type=\"radio\" value=\"teal\"><i></i><span class=\"wojo teal font\">Teal</span></label>";
            text += "<label class=\"radio\"><input name=\"color\" type=\"radio\" value=\"info\"><i></i><span class=\"wojo info font\">Info</span></label>";
            text += "</div>";
            new Messi(text, {
                title: config.addNote,
                modal: true,
                closeButton: true,
                buttons: [{
                    id: 0,
                    label: config.addNote,
                    class: 'positive',
                    val: 'Y'
                }],
                callback: function (val) {
                    noteval = $("input[name=note]").val();
                    color = $("input:radio[name=color]:checked").val();
                    $.ajax({
                        type: 'post',
                        url: "controller.php",
                        dataType: 'json',
                        data: {
                            newNote: 1,
                            note: noteval,
                            color: color
                        },
                        success: function (json) {
                            if (json.type == "success") {
                                $("#notes").prepend(json.html);
                            }
                            $.sticky(decodeURIComponent(json.message), {
                                type: json.type,
                                title: json.title
                            });
                        }

                    });

                }
            });
        });

        $('body').on('click', '#notes a.note-close', function () {
            id = $(this).data('id');
            $.post("controller.php", {
                deleteNote: 1,
                id: id
            });
        });

        $('body').on('click', 'a.sdelete', function () {
            var target = $(this).attr('href');
            $.post("controller.php", {
                deleteStats: 1
            });
            $('body').fadeOut(1000, function () {
                window.location.href = target;
            });
            return false
        });

        $("[data-select-range]").on('click', '.item', function () {
            v = $("input[name=range]").val();
            getVisitsChart(v)
        });

        var map = null;
        loadMap = function (latitude, longitude) {
            var latlng = new google.maps.LatLng(latitude, longitude);
            var myOptions = {
                zoom: 13,
                center: latlng,
                mapTypeControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap"), myOptions);
        }
        setupMarker = function (latitude, longitude) {
            var pos = new google.maps.LatLng(latitude, longitude);
            var image = new google.maps.MarkerImage('../assets/pin.png');
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                raiseOnDrag: false,
                icon: image,
                title: name
            });
        }

        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latitude = config.lat;
        var longitude = config.long;
        loadMap(latitude, longitude);
        setupMarker(latitude, longitude);

        $('#address').on("keyup", function (e) {
            if (e.keyCode == 13) {
                var address = document.getElementById('address').value;
                geocoder.geocode({
                    'address': address
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var image = new google.maps.MarkerImage('../assets/pin.png');
                        var marker = new google.maps.Marker({
                            map: map,
                            raiseOnDrag: false,
                            icon: image,
                            position: results[0].geometry.location
                        });
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }

                });
            }
        });

        var city = config.city;
        $.simpleWeather({
            location: city,
            woeid: '',
            unit: config.temp,
            success: function (weather) {
                city = weather.city;
                region = weather.country;
                tomorrow_date = weather.tomorrow.date;
                $(".weather-city").html(city);
                $(".weather-currently").html(weather.currently);
                $(".today-img").html('<i class="big-img-weather wicon-' + weather.code + '"></i>');
                $(".today-temp").html(weather.low + '&deg; / ' + weather.high + '&deg;');
                $(".weather-region").html(region);
                $(".weather-day").html(tomorrow_date);
                $(".1-days-day").html(weather.forecasts.one.day);
                $(".1-days-image").html('<i class="wicon-' + weather.forecasts.one.code + '"></i>');
                $(".1-days-temp").html(weather.forecasts.one.low + '&deg; / ' + weather.forecasts.one.high + '&deg;');
                $(".2-days-day").html(weather.forecasts.two.day);
                $(".2-days-image").html('<i class="wicon-' + weather.forecasts.two.code + '"></i>');
                $(".2-days-temp").html(weather.forecasts.two.low + '&deg; / ' + weather.forecasts.two.high + '&deg;');
                $(".3-days-day").html(weather.forecasts.three.day);
                $(".3-days-image").html('<i class="wicon-' + weather.forecasts.three.code + '"></i>');
                $(".3-days-temp").html(weather.forecasts.three.low + '&deg; / ' + weather.forecasts.three.high + '&deg;');
                $(".4-days-day").html(weather.forecasts.four.day);
                $(".4-days-image").html('<i class="wicon-' + weather.forecasts.four.code + '"></i>');
                $(".4-days-temp").html(weather.forecasts.four.low + '&deg; / ' + weather.forecasts.four.high + '&deg;');

            }
        });

        $('#location').on("keyup", function (e) {
            if (e.keyCode == 13) {
                city = $('#location').val();
                $.simpleWeather({
                    location: city,
                    woeid: '',
                    unit: config.temp,
                    success: function (weather) {
                        city = weather.city;
                        region = weather.country;
                        tomorrow_date = weather.tomorrow.date;
                        $(".weather-city").html(city);
                        $(".weather-currently").html(weather.currently);
                        $(".today-img").html('<i class="big-img-weather wicon-' + weather.code + '"></i>');
                        $(".today-temp").html(weather.low + '&deg; / ' + weather.high + '&deg;');
                        $(".weather-region").html(region);
                        $(".weather-day").html(tomorrow_date);
                        $(".1-days-day").html(weather.forecasts.one.day);
                        $(".1-days-image").html('<i class="wicon-' + weather.forecasts.one.code + '"></i>');
                        $(".1-days-temp").html(weather.forecasts.one.low + '&deg; / ' + weather.forecasts.one.high + '&deg;');
                        $(".2-days-day").html(weather.forecasts.two.day);
                        $(".2-days-image").html('<i class="wicon-' + weather.forecasts.two.code + '"></i>');
                        $(".2-days-temp").html(weather.forecasts.two.low + '&deg; / ' + weather.forecasts.two.high + '&deg;');
                        $(".3-days-day").html(weather.forecasts.three.day);
                        $(".3-days-image").html('<i class="wicon-' + weather.forecasts.three.code + '"></i>');
                        $(".3-days-temp").html(weather.forecasts.three.low + '&deg; / ' + weather.forecasts.three.high + '&deg;');
                        $(".4-days-day").html(weather.forecasts.four.day);
                        $(".4-days-image").html('<i class="wicon-' + weather.forecasts.four.code + '"></i>');
                        $(".4-days-temp").html(weather.forecasts.four.low + '&deg; / ' + weather.forecasts.four.high + '&deg;');

                    }
                });
            }
        });

        function getVisitsChart(range) {
            $.ajax({
                type: 'GET',
                url: 'controller.php?getVisitsStats=1&timerange=' + range,
                dataType: 'json',
                async: false,
                success: function (json) {
                    var option = {
                        shadowSize: 0,
                        lines: {
                            show: true
                        },
                        points: {
                            show: true
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            borderColor: {
                                top: "#FFF",
                                left: "#FFF"
                            }
                        },
                        xaxis: {
                            ticks: json.xaxis
                        }
                    }
                    $.plot($('#chart'), [json.hits, json.visits], option);
                }
            });
        }

        getVisitsChart('year');

    };
})(jQuery);