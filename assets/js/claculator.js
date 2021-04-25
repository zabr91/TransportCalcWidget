jQuery(function ($) {

	$(document).ready(function(){ 
		'use strict';

		var appSettings = {
			'map' : $('.calculate-map'),
			'center' : [53.677834, 23.829529],
			'zoom' : 5,
			'strokeColor' : '2e3191',
			'mapBehaviorsDisables' : ['drag', 'scrollZoom'],
			'mapControlsRemove' : ['fullscreenControl', 'trafficControl', 'typeSelector', 'searchControl'],
		};

		
		//Controls
		var appMap, appRoute, lengthval;
		var inputFrom =  $('.calculate-from-position__from input');
        var inputTo = $('.calculate-from-position__to input');
        var distance = $('#distance');
        var inputSize = $('input#size');
        var inputMass = $('input#mass');


		if(appSettings['map']) { appInit();};

		function appInit(){
		 $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; }});
		 mapInit();
		};


		function mapInit(argument) {
		   ymaps.ready(function () {

			appMap = new ymaps.Map('map', { 	center: appSettings['center'], zoom: appSettings['zoom'] });

			appSettings['mapBehaviorsDisables'].forEach(element => appMap.behaviors.disable(element));
			appSettings['mapControlsRemove'].forEach(element => appMap.controls.remove(element));

		
			inputFrom.on('change', function(){ routeInit();});
			inputTo.on  ('change', function(){ routeInit();});

			inputSize.bind("keyup change wheel", function(){
			  var c1, c2, b1, b2;

			   c1 = inputSize.val();
			   c2 = inputMass.val();

			   b1 = tonnaToMcub(c2);
			   b2 = mcubTotonna(c1);

			   inputMass.val(b2);
			   calculateDistancePrice(lengthval);
			});

		    inputMass.bind("keyup change wheel", function(){
			var val = $(this).val();

				inputSize.val(tonnaToMcub(val)); 

				calculateDistancePrice(lengthval);
		   });
		
		   });	
		};

		function routeInit(){

		mask('#map', true);

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
								opacity: 1});
						});

						distance.html('<span>'+parseInt(lengthval) + ' км</span>');
						calculateDistancePrice(lengthval);
						appRoute = route;
					}
				});
				mask('#map', false);
				}, function (err) {	throw err; }, this); //end done
			}
		};

		function calculateDistancePrice(distance) {
		$.ajax({
        	type: "GET",
        	url:  ajax_object.ajaxurl,
        	data: {
            	action : 'get_price',
            	distance : lengthval,
            	weight : inputMass.val(),
            	volume : inputSize.val()
        	},
        	success: function (response) {
        		var responseJSON = JSON.parse(response);
        		$('#calc-price').html(responseJSON.result);
            	console.log('AJAX response : ',responseJSON.result );
        	}
    	});

		};

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


		//function kgToMcub(kg){ return parseFloat(kg * 0.00666667).toFixed(2);}

		function tonnaToMcub(tonna){ return parseFloat((tonna * 1000) * 0.00666667).toFixed(2);}


		//function mcubTokg(mCub){ return parseInt(mCub / 0.0066665).toFixed(2); }

		function mcubTotonna(mCub){ return parseFloat((mCub / 0.0066665) / 1000).toFixed(2); }

	});

});