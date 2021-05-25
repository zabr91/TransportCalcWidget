jQuery(function ($) {

    $(document).ready(function () {
        'use strict';

        var appSettings = {
            'map': $('.calculate-map'),
            'center': [53.677834, 23.829529],
            'zoom': 5,
            'strokeColor': '2e3191',
            'mapBehaviorsDisables': ['drag', 'scrollZoom'],
            'mapControlsRemove': ['fullscreenControl', 'trafficControl', 'typeSelector', 'searchControl'],
        };


        //Controls
        var appMap, appRoute, lengthval;
        var inputFrom = $('.calculate-from-position__from input');
        var inputTo = $('.calculate-from-position__to input');
        var distance = $('#distance');
        var inputVolume = $('input#size');
        var inputWeight = $('input#mass');


        var sendBtn = $('#send-calc-result');
        var calcOptions = $(".calc-options");
        var openDialog = false;
        var tableConf = [
            /*	[1.5,  10],
                [3.5,  20],
                [5.5,  35],
                [9.5,	50],
                [20,	82] */
        ];

        var htmlForm =
            '<form class="tcw-form">' +
            '<div>' +
            '<label>Имя</label>' +
            '<input type="text" size="40"  value="" name="name" class="name" placeholder="Имя"/>' +
            '</div>' +
            '<div>' +
            '<label>Телефон</label>' +
            '<input type="tel" size="40"  value="" name="phone" class="phone"  placeholder="+7(__)__-__-__" />' +
            '</div>' +
            '<div>' +
            '<label>e-mail</label>' +
            '<input type="email" size="40" value="" name="email" class="email" placeholder="Почта"/>' +
            '</div>' +
            ' <div class="flex">Наличныe <label class="switch"><input type="checkbox" id="formpay" value="Наличные"><span class="slider round"></span></label>Безналичные</div>' +
            '<label><input type="checkbox" id="apcept"/> Заполняя форму обратной связи я даю согласие на обработку своих персональных данных</label>' +
            '<form>';


        // Enter point in app
        if (appSettings['map']) {
            appInit();
        }
        ;

        //on select option
        calcOptions.on("click", function (e) {
            //console.log('select option');
            calculateDistancePrice(0);
        });


        sendBtn.on("click", function (e) {
            e.preventDefault();
            //if(openDialog == false){

            $.alert({
                title: 'Подать заявку',
                theme: 'supervan',
                backgroundDismiss: function () {
                    return false; // modal wont close.
                },
                closeIcon: true,
                content: htmlForm,
                buttons: {
                    formSubmit: {
                        text: 'Отпавить',
                        btnClass: 'btn-blue',
                        action: function () {

                            return sendData(this);

                        },
                        cancel: function () {
                            //close
                        },
                    }
                }
            });

        });

        /*
		closeWindow.on("click", function(e){ 
			e.preventDefault();
			modalWindow.fadeOut(300);
		});*/

        /**
         * Start app
         */
        function appInit() {

            //off submit on keydown enter
            $(window).keydown(function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $.ajax({
                type: "GET",
                url: ajax_object.ajaxurl,
                data: {
                    action: 'get_table'
                },
                success: function (response) {

                    var responseJSON = JSON.parse(response);
                    tableConf = responseJSON;
                }
            });

            mapInit();
        }

        /**
         * Init Yandex map
         */
        function mapInit() {
            ymaps.ready(function () {

                appMap = new ymaps.Map('map', {center: appSettings['center'], zoom: appSettings['zoom']});

                appSettings['mapBehaviorsDisables'].forEach(element => appMap.behaviors.disable(element));
                appSettings['mapControlsRemove'].forEach(element => appMap.controls.remove(element));


                inputFrom.on('input', function () {
                    routeInit();
                });
                inputTo.on('input', function () {
                    routeInit();
                });

                inputVolume.bind("keyup input wheel", function () {
                    var c1, c2, b1, b2;

                    c1 = inputVolume.val();
                    c2 = inputWeight.val();

                    b1 = tonnaToMcub(c2);
                    b2 = mcubTotonna(c1);

                    inputWeight.val(b2);
                    calculateDistancePrice(lengthval);
                });

                inputWeight.bind("keyup input wheel", function () {
                    var val = $(this).val();

                    inputVolume.val(tonnaToMcub(val));

                    calculateDistancePrice(lengthval);
                });

            });
        };

        /**
         * Init route in map
         */
        function routeInit() {

        //    mask('#map', true);

            if (inputTo.val() != '' && inputFrom.val() != '') {
                ymaps.route([inputFrom.val(), inputTo.val()], {
                    multiRoute: true,
                    mapStateAutoApply: true,
                })
                    .done(function (route) {
                        route.model.setParams({results: 1}, true);

                        route.model.events.add('requestsuccess', function () {
                            appMap.geoObjects.remove(appRoute);

                            var activeRoute = route.getActiveRoute();

                            if (activeRoute) {
                                appMap.geoObjects.add(route);
                                length = route.getActiveRoute().properties.get("distance");
                                lengthval = length.value / 1000;

                                route.options.set("zoomMargin", 35);
                                route.options.set("boundsAutoApply", true);

                                route.getRoutes().each(function (route) {
                                    route.options.set({
                                        strokeColor: appSettings['strokeColor'],
                                        opacity: 1
                                    });
                                });

                                distance.html('<span>' + parseInt(lengthval) + ' км</span>');
                                calculateDistancePrice(lengthval);
                                appRoute = route;
                            }
                        });
                        mask('#map', false);
                    }, function (err) {
                        throw err;
                    }, this); //end done
            }
        };

        function calculateDistancePrice(distance) {
            if (openDialog == false) {
                $.ajax({
                    type: "GET",
                    url: ajax_object.ajaxurl,
                    data: {
                        action: 'get_price',
                        distance: lengthval,
                        weight: inputWeight.val(),
                        volume: inputVolume.val(),
                        options: getSelectOptions(true)
                    },
                    success: function (response) {
                        var responseJSON = JSON.parse(response);
                        $('#calc-price').html(responseJSON.result.price);
                        $('#passingcargo').html('Если груз будет попутным ' + responseJSON.result.passingcargo + ' руб.');
                        //console.log('AJAX response : ',response);


                        if (responseJSON.result.message) {
                            openDialog = true;
                            $.alert({
                                title: responseJSON.result.message,
                                closeIcon: true,
                                theme: 'supervan',
                                content: htmlForm,

                                buttons: {
                                    formSubmit: {
                                        text: 'Отпавить',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                            return sendData(this);

                                        },
                                        cancel: function () {
                                            //close
                                        },
                                    }
                                },


                            });
                        }
                    }
                });
            }

        };


        function chkField(field) {
            if (field.val().length < 1) {
                field.css('border', 'solid 4px red');
                return false;
            } else {
                field.css('border', 'none');
            }
        }

        function sendData(thisform) {

            var apceptChk = thisform.$content.find('#apcept');

            if (apceptChk.prop('checked')) {


                var name = thisform.$content.find('.name').val();
                var phone = thisform.$content.find('.phone').val();
                var email = thisform.$content.find('.email').val();

                if (name.length < 1 ||
                    phone.length < 1 ||
                    email.length < 1) {
                    return false;
                }

                /*
                                 var regEx = /^[A-Z0-9][A-Z0-9._%+-]{0,63}@(?:[A-Z0-9-]{1,63}.){1,125}[A-Z]{2,63}$/;

                                   var validEmail = regEx.test(email);
                                   if (!validEmail) {
                                    thisform.$content.find('.email').css('border', 'red');
                                    return false;
                                   }*/

                //	$('$frompay').val();

                let formpay = "Наличный рассчет";

                if ($('#formpay').is(':checked') == true) {
                    formpay = "Безналичный рассчет";
                }

                //console.log(formpay);

                $.ajax({
                    type: "POST",
                    url: ajax_object.ajaxurl,
                    data: {
                        action: 'send_data',
                        name: name, //1
                        phone: phone, //2
                        email: email, //3
                        from: inputFrom.val(), //4
                        to: inputTo.val(), //5
                        weight: inputWeight.val(), //6
                        volume: inputVolume.val(), //7
                        distance: distance.text(), //8
                        price: $('#calc-price').text(), //9
                        options: getSelectOptions(false),//10
                        formpay: formpay
                    },
                    success: function (response) {

                        if (response == 1) {
                            $.alert({
                                title: 'Сообщение принято',
                                content: 'Наш менеджер свяжется с Вами в ближайшее время.',
                                theme: 'material',
                            });
                        }
                        //	console.log(response);
                    }
                });


            } else {
                alert('Для отправки формы необходимо согласится с обработкой персональных данных');
                return false;
            }

            return true;
        }

        function getSelectOptions(data = true) {

            var calcOptionsChk = $(".calc-options:checked");
            var countChekedOptions = calcOptionsChk.length;

            var options = [];

            var str = '';

            if (countChekedOptions > 0) {
                for (var i = 0; i < countChekedOptions; i++) {

                    if (data == true) {
                        options.push($(calcOptionsChk[i]).data());
                    } else {
                        str += $(calcOptionsChk[i]).val();
                    }

                }

            }

            if (data == true) {
                return JSON.stringify(options);
            } else {
                return str;
            }


        }

        function mask(element, condition) {
            var preloader = '<div class="preloader-holder"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';

            if (condition == true) {
                $(element).css('position', 'relative');
                $(element).append(preloader).fadeIn(300);

            }
            if (condition == false) {
                $('.preloader-holder').fadeOut(300);
            }
        }


        function tonnaToMcub(tonna) {
            for (var i = 0; i < tableConf.length; i++) {
                if (tonna <= tableConf[i][0]) return tableConf[i][1];
            }
        }

        function mcubTotonna(mCub) {
            for (var i = 0; i < tableConf.length; i++) {
                if (mCub <= tableConf[i][1]) return tableConf[i][0];
            }
        }

    });


});