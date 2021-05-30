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

        var htmlForm =
            '<form class="tcw-form">' +
            '<div>' +
            '<label>Имя</label>' +
            '<input type="text" size="40"  value="" name="name" class="name" placeholder="Имя" required/>' +
            '</div>' +
            '<div>' +
            '<label>Телефон</label>' +
            '<input type="tel" size="40"  value="" name="phone" class="phone" data-grouplength="5,"  placeholder="+7(__)__-__-__" required/>' +
            '</div>' +
            '<div>' +
            '<label>e-mail</label>' +
            '<input type="email" size="40" value="" name="email" class="email" placeholder="Почта" required/>' +
            '</div>' +
            ' <div class="flex">Наличныe <label class="switch"><input type="checkbox" id="formpay" value="Наличные"><span class="slider round"></span></label>Безналичные</div>' +
            '<label><input type="checkbox" id="apcept"/> Заполняя форму обратной связи я даю согласие на обработку своих персональных данных</label>' +
            '<form>';


        // Enter point in app
        if (appSettings['map']) {
            appInit();
        }


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
                theme: 'modern',
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

                let timeout = null;

                inputFrom.on('input', function () {
                    clearTimeout(timeout);

                    timeout = setTimeout(function () {
                        routeInit();
                    }, 1000);

                });

                inputTo.on('input', function () {
                    clearTimeout(timeout);

                    timeout = setTimeout(function () {
                        routeInit();
                    }, 1000);

                });

                inputVolume.bind("keyup input wheel", function () {
                    clearTimeout(timeout);

                    timeout = setTimeout(function () {
                        calculateDistancePrice(lengthval)
                    }, 1000);


                });

                inputWeight.bind("keyup input wheel", function () {
                    clearTimeout(timeout);

                    timeout = setTimeout(function () {
                        calculateDistancePrice(lengthval)
                    }, 1000);

                });

            });
        };

        /**
         * Init route in map
         */
        function routeInit() {

        //    mask('#map', true);

            if (inputTo.val() !== '' && inputFrom.val() !== '') {
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
                     //   mask('#map', false);
                    }, function (err) {
                        throw err;
                    }, this); //end done
            }
        };

        /**
         * Get data
         * @param distance
         * @returns {boolean}
         */
        function calculateDistancePrice(distance) {
          //  if (openDialog == false) {

                $('#passingcargo').html('Идет загрузка...');
                
                $.ajax({
                    type: "GET",
                    url: ajax_object.ajaxurl,
                    async: false,
                    data: {
                        action: 'get_price',
                        distance: lengthval,
                        weight: inputWeight.val(),
                        volume: inputVolume.val(),
                        options: getSelectOptions(true)
                    },
                    success: function (response) {
                        let responseJSON = JSON.parse(response);
                        $('#calc-price').html(responseJSON.result.price);
                        $('#passingcargo').html('Если груз будет попутным ' + responseJSON.result.passingcargo + ' руб.');
                        //console.log('AJAX response : ',response);


                        if (responseJSON.result.message) {
                            openDialog = true;
                            $.alert({
                                title: responseJSON.result.message,
                                closeIcon: true,
                                theme: 'modern',
                                content: htmlForm,

                                buttons: {
                                    formSubmit: {
                                        text: 'Отправить',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                          //  openDialog = false;
                                            return sendData(this);

                                        },
                                        cancel: function () {
                                            //close
                                            //openDialog = false;
                                        },
                                    }
                                },


                            });
                        }
                    }
                });
           // }
            return true;

        };


        /**
         * Show in the form errors
         * @param thisform
         * @param selector
         * @returns {boolean}
         */
        function validateField(thisform, selector, patern){

            let value = thisform.$content.find(selector).val();

            let regEx = new RegExp(patern);
            let validate = regEx.test(value);

            if (!validate) {
                thisform.$content.find(selector).css('border', 'solid 2px red');
                return false;
            }
            else {
                thisform.$content.find(selector).css('border', 'none');
                return true;
            }
        }

        /**
         * Send data to server
         * @param thisform
         * @returns {boolean}
         */
        function sendData(thisform) {

            let apceptChk = thisform.$content.find('#apcept');

            if (apceptChk.prop('checked')) {

            if(!validateField(thisform, '.name',
                '^[A-Za-zА-ЯЁа-яё]{2,60}(.[A-Za-zА-ЯЁа-яё]{2,60})?(.?[A-Za-zА-ЯЁа-яё]{2,60})?$')){
                return false;
            }

            if(!validateField(thisform, '.phone',
                '^[\\d+()\\s]{7,30}$')) {
                return false;
            }

            if(!validateField(thisform, '.email',
                '^[a-zA-Z0-9.!#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\\.[a-zA-Z0-9-]+)*$')) {
                return false;
            }



                let name = thisform.$content.find('.name').val();
                let phone = thisform.$content.find('.phone').val();
                let email = thisform.$content.find('.email').val();

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

    });


});