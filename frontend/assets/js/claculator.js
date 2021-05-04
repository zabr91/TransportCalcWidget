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

        var modalWindow = $('#transport_calc_popup');
        var submitBtn = $('#send-calc-result');
        var closeWindow = $('#close-modal');
        var calcOptions = $(".calc-options");

        calcOptions.on("click", function(e){
        console.log("add options"); 
		calculateDistancePrice(0);
		});


        submitBtn.on("click", function(e){ 
		e.preventDefault();
		modalWindow.fadeIn(300);
		});

		closeWindow.on("click", function(e){ 
		e.preventDefault();
		modalWindow.fadeOut(300);
		});


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

			  /* if(inputMass.val() < 0.5) {
			   	inputMass.value = 0.5;
			   }*/

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

			var calcOptionsChk = $(".calc-options:checked");
			var countChekedOptions = calcOptionsChk.length;

			var options = [];

			if(countChekedOptions > 0) {
				for (var i = 0; i < countChekedOptions; i++) {
					options.push( $(calcOptionsChk[i]).data());
				}
				
			}
			console.log(options);

		$.ajax({
        	type: "GET",
        	url:  ajax_object.ajaxurl,
        	data: {
            	action : 'get_price',
            	distance : lengthval,
            	weight : inputSize.val(),
            	volume : inputMass.val(),
            	options : JSON.stringify(options)
        	},
        	success: function (response) {
        		var responseJSON = JSON.parse(response);
        		$('#calc-price').html(responseJSON.result.price);
            	console.log('AJAX response : ',response );
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

		var tableConf = [
		 [1.5,  10],
		 [3.5,  20],
 		 [5.5,  35],
		 [9.5,	50],
		 [20,	82]
		];

		function tonnaToMcub(tonna){
			for (var i = 0; i < tableConf.length; i++) {
				if(tonna <= tableConf[i][1]) return tableConf[i][0];
			}
		}

		function mcubTotonna(mCub){
			for (var i = 0; i < tableConf.length; i++) {
				if(mCub <= tableConf[i][0]) return tableConf[i][1];
			}			
		}

	});

	

});