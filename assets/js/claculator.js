console.log('JS ready..');

jQuery(function ($) {

$(document).ready(function(){ 
	'use strict';

let calculateMap = document.querySelector('.calculate-map'),
        distance = $('.calculate-from-position__dist');

 // controls
 
 var inputFrom =  $('.calculate-from-position__from input');
 var inputTo = $('.calculate-from-position__to input');
 var inputSize = $('input#size');
 var inputMass = $('input#mass');

if (calculateMap) {
	var myRoute,
		m3,
		sum=0,
		total=0,
		length,
		lengthval,
		priceForMcube,
		currency = 'ru';

    ymaps.ready(function () {

		var myMap = new ymaps.Map('map', { 	center: [53.677834, 23.829529], zoom: 5 });

		myMap.behaviors.disable('drag');
		myMap.controls.remove('fullscreenControl');
		myMap.controls.remove('trafficControl');
		myMap.controls.remove('typeSelector');
		myMap.controls.remove('searchControl');
		myMap.behaviors.disable('scrollZoom');

			// инициируем расчёт если указан город, страна, адрес
		inputFrom.on('change', function(){ initRoute(); });

		inputTo.on('change', function(){initRoute();});

			// инициируем расчёт если кг и м3 ввели
		inputSize.bind("keyup change wheel", function(){
			var c1, c2, b1, b2;

			c1 = inputSize.val();
			c2 = inputMass.val();

			b1 = kgToMcub(c2);
			b2 = mcubTokg(c1);

			inputMass.val(b2);
			calculateDistancePrice(lengthval);});

		inputMass.bind("keyup change wheel", function(){
			var val = $(this).val();

			if (val <= 150) {
				inputSize.val(1);}
			else {
				inputSize.val(kgToMcub(val)); }

				calculateDistancePrice(lengthval);
		});

			// отключил сабмит по нажатию на enter
		$(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; }});

		var fromInput = $('.calculate-from-position__from input');
		var toInput = $('.calculate-from-position__to input');

			// строим маршрут
		function initRoute(){

			mask('#map', true);

			if (toInput.val() != '' && fromInput.val() != '') {
				ymaps.route([fromInput.val(), toInput.val()], {
					multiRoute: true,
					mapStateAutoApply: true,
				})
				.done(function (route) {
					// Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
					route.model.setParams({results: 1}, true);

					// Повесим обработчик на событие построения маршрута.
				route.model.events.add('requestsuccess', function () {
					myMap.geoObjects.remove(myRoute);

					var activeRoute = route.getActiveRoute();

					if (activeRoute) {
						myMap.geoObjects.add(route);
						length = route.getActiveRoute().properties.get("distance");
						lengthval = length.value / 1000;
						// Создадим макет содержимого балуна маршрута.

						route.options.set("zoomMargin", 35);
           				route.options.set("boundsAutoApply", true);

						// Зададим этот макет для содержимого балуна.
						route.getRoutes().each(function (route) {
							route.options.set({
								strokeColor: '2e3191',
								opacity: 1});
						});

						distance.html('<span>'+parseInt(lengthval) + ' км</span>');
						calculateDistancePrice(lengthval);
						myRoute = route;
					}
				});
				mask('#map', false);
				}, function (err) {	throw err; }, this); //end done
			}
		}; //initRoute

				//переключатель валюты
		$('input#slideThree').on('change', function(){
					if ($(this).is(":checked"))	{
						$('.currency-symbol').text('₽');
						currency = 'ru';
					}	else {
						$('.currency-symbol').text('€');
						currency = 'eu';
					}

					calculateDistancePrice(lengthval);
		});

				//ковертируем кг в м3
				function kgToMcub(kg){
					return parseFloat(kg * 0.00666667).toFixed(2);
				}

				//ковертируем м3 в кг
				function mcubTokg(mCub){
					return parseInt(mCub / 0.0066665);
				}

		//получаем скидку от м3
				function getMcubDiscount(mCub){
					if (mCub <= 4) {
						return 0;
					} else if (mCub > 4 && mCub <= 8) {
						return 10;
					} else if (mCub > 8 && mCub <= 14) {
						return 20;
					} else if (mCub > 14) {
						return 30;
					}
				}

				//основной расчёт стоимости
				function calculateDistancePrice(lengthval) {

					var a,b,c,d,e,mCub,kg,g,l,p,discount,symbol;

					//кг и м3
					kg = $('input#mass').val();
					mCub = $('input#size').val();

					//приводим кг к м3
					if (mCub == '') {
						mCub = kgToMcub(kg);
					}

					//проверяем валюту
					if (currency == 'ru') {
						symbol = '₽';
						d = 72.98; // курс eu к rub
						c = d * 180; // множитель
						p = 3890;
					} else {
						symbol = '€';
						d = 1; // курс
						c = d * 180; // множитель
						p = parseInt(3890/d);
					}

					//расчёт цены за м3
					if (lengthval < 600) {
						priceForMcube = c;
					} else {
						a = parseInt(lengthval - 600);
						b = Math.floor(a / 10);
						priceForMcube = c + (b * d);
					}

					//расчёт скидки за м3
					discount = getMcubDiscount(mCub);	// получаю скидку от объёма м3

					if (discount > 0) {
						// console.log('Цена за м3 = ' + parseInt(priceForMcube));
						l = parseInt((priceForMcube/100) * discount); // получаем скидку
						g = parseInt(priceForMcube - l); // вычитаем скидку
						total = parseInt(g * mCub); // результат с скидкой
						// console.log(discount + '% скидка на кубатуру умноженную на расстояние  = ' + l);
						// console.log('Цена за м3 со скидкой = ' + g);
					} else {
						// console.log('Цена за м3 = ' + parseInt(priceForMcube));
						// console.log('Скидки нет');
						total = parseInt(priceForMcube * mCub);
					}

					//выводим результат
					if (mCub > 0 && lengthval > 0 && $('.calculate-from-position__from input').val() != "" && $('.calculate-from-position__to input').val() != "") {
						// console.log('Результат м3 * цену за кубатуру = ' + total);
						// console.log('------------------------');
					}

					recalc();
				}
			});

		function recalc(el = $('.calculate-from-detailed-block__check input:checked')){

				//console.log(el);
				var m3,f,p,r,o,pl,sl,e,d,a,l,b,t,cl=0,symbol;

				//проверяем валюту
				if (currency == 'ru') {
					symbol = '₽';
					l = 72.98; // курс eu к rub
				} else {
					symbol = '€';
					l = 1; // курс
				}

				for (var y = 0; y<el.length; y++) {

					var s = el[y];

					p = $(s).parents('.calculate-from-detailed-block');
					r = p.find('.calculate-from-detailed-block__price');
					e = p.find('.calculate-from-detailed-block-list input:checked').val();
					d = p.find('.calculate-from-detailed-block-list input.default');
					f = p.find('.floors option:selected').val();
					t = $('.calculate-from-detailed-block__price');
					m3 = $('input#size').val();

					if (p.find('input.lading-discharging').is(":checked")) {
						p.addClass('active');

						if ($(p).hasClass( "packing-block" )) {
							switch(e) {
								case "стандарт":
									//console.log("стандарт");
									a = l*10;
									b = parseInt(a*m3);
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');
									break;
								case "эконом":
									//console.log("эконом");
									a = l*5;
									b = parseInt(a*m3);
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');
									break;
								case "vip":
									//console.log("vip");
									a = l*15;
									b = parseInt(a*m3);
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');
									break;
								default:
									//console.log("упаковка не выбрана");
									r.html( '+ 0 <span class="currency-symbol">'+ symbol +'</span>');
							}

						} else {

							switch(e) {
								case "негабарит":
									//console.log("негабарит");
									b = 0;
									r.html( b + ' <span class="currency-symbol">'+ symbol +'</span>');
									break;
								case "пасажирский лифт":
									//console.log("пасажирский лифт");
									a = l*10;
									b = parseInt(a*m3);
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');

									break;
								case "грузовой лифт":
									//console.log("грузовой лифт");
									a = l*6;
									b = parseInt(a*m3);
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');
									break;
								default:
									//console.log("без лифта");
									a = l*5;
									b = parseInt(a*m3)*f;
									r.html( '+ ' + parseInt(b).toLocaleString() + ' <span class="currency-symbol">'+ symbol +'</span>');
							}
						}
						sum = 0;
						for (var i = 0; i < t.length; i++) {
							cl = $(t[i]).text().replace(/[^0-9]/g,'');
							sum += parseInt(cl);
						}


					} else {
						r.html( '0 <span class="currency-symbol">'+ symbol +'</span>');
						sum = 0;
						for (var i = 0; i < t.length; i++) {
							cl = $(t[i]).text().replace(/[^0-9]/g,'');
							sum += parseInt(cl);
						}

						d.prop("checked", true);
						p.removeClass('active');
					}

				} //endfor

			if (el.length>0) {
				getResult(symbol);
			} else {
				sum = 0;
				getResult(symbol);
			}
		}

		function getResult(symbol){
			var p,r,u,e1,e2;

			if ($('.loding-block input.lading-discharging').is(":checked") ) {
				e1 = $('.loding-block .floors option:selected').val();
				p = $('.loding-block .calculate-from-detailed-block-list input:checked').val() + ', этаж ' + e1 ;
			} else {
				p = 'нет';
			}

			if ($('.discharging-block input.lading-discharging').is(":checked") ) {
				e2 = $('.discharging-block .floors option:selected').val();
				r = $('.discharging-block .calculate-from-detailed-block-list input:checked').val() + ', этаж ' + e2 ;
			} else {
				r = 'нет';
			}

			if ($('.packing-block input.lading-discharging').is(":checked") ) {
				u = $('.packing-block .calculate-from-detailed-block-list input:checked').val();
			} else {
				u = 'нет';
			}

			var resultText = 'Откуда: ' + $('.calculate-from-position__from input').val() + '\n';
			resultText += 'Куда: ' + $('.calculate-from-position__to input').val() + '\n';
		  resultText += 'Расстояние: ' + parseInt(lengthval) + ' км\n';

			if (total) {
				$('.button-price').html( parseInt(total+sum).toLocaleString() + ' <span>' + symbol + '</span>');

			} else {
				$('.button-price').html( parseInt(sum).toLocaleString() + ' <span>' + symbol + '</span>');
			}

			resultText += 'Объём: ' + $('input#size').val() + ' м3\n';
			resultText += 'Вес: ' + $('input#mass').val() + ' кг\n';
			resultText += 'Погрузка: ' + p + '\n';
			resultText += 'Разгрузка: ' + r + '\n';
			resultText += 'Упаковка: ' + u + '\n';
			resultText += 'Итого: ' + $('.button-price').text() + '\n';

			if ( lengthval>0 && $('.calculate-from-position__from input').val() !='' &&  $('.calculate-from-position__to input').val() != '' ) {
				$('#calcresult').val(resultText);
			}

			//console.log($('#calcresult').val());
		};

		$('input.lading-discharging, select.floors, .calculate-from-detailed-block-list input, calculate-from-detailed-block-wrap input').change(function(){
			var el = $(this);
			recalc(el);
		});

		// активируем разгрузку
		$('#lading').change(function(){
			if ($(this).is(":checked")) {
				$('.discharging-block').removeClass('calculate-disabled');
			} else {
				$('.discharging-block').addClass('calculate-disabled');
				if ($('.discharging-block .lading-discharging').is(":checked")) {
				 $('.discharging-block .lading-discharging').click();
				}
			}
		});

    } else {

        ymaps.ready(function () {
            var myMap = new ymaps.Map('map', {
                    center: [55.919458, 37.747630],
                    zoom: 16,
                    controls: []
                }, {
                    searchControlProvider: 'yandex#search'
                }),
                // Создаём макет содержимого.
                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                ),
                myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                    hintContent: '',
                    balloonContent: ''
                }, {
                    // Опции.
                    // Необходимо указать данный тип макета.
                    iconLayout: 'default#image',
                    // Своё изображение иконки метки.
                    iconImageHref: '/wp-content/themes/kangor/assets/img/mapIcon.png',
                    // Размеры метки.
                    iconImageSize: [75, 80],
                    // Смещение левого верхнего угла иконки относительно
                    // её "ножки" (точки привязки).
                    iconImageOffset: [-37, -80]
                });
            myMap.geoObjects
                .add(myPlacemark);
        });

		}

		// preloder mask
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