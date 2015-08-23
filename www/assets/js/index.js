/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */


var SITE_URL = 'http://uza.inetstz.com/index.php?';
var ROOT_LIVE_URL = 'http://uza.inetstz.com/';
var method_ = 'post';
// initialize global variables that tends to exist in most pages here
var user = '';
var name = '';
var cat_id = '';
var product_id = '';
var param = [];
//start default app functions
var app = {
    // Application Constructor
    initialize: function () {
	this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function () {
	document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function () {
	app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function (id) {
	var parentElement = document.getElementById(id);
	var listeningElement = parentElement.querySelector('.listening');
	var receivedElement = parentElement.querySelector('.received');
	listeningElement.setAttribute('style', 'display:none;');
	receivedElement.setAttribute('style', 'display:block;');
	console.log('Received Event: ' + id);
    }
};
app.initialize();
uza = {
    /**
     * 
     * @param {type} param
     * @param {type} callback
     * @returns {undefined}
     */
    init: function () {
	$(".megamenu").megamenu();
	this.loadPage('body.html');
    },
    get_remote: function (param, callback, method_) {
	window.method_ = (typeof method_ === "undefined") ? 'get' : method_;
	window.param = (typeof param === "undefined") ? [] : param;
	$.ajax({
	    cache: true,
	    // url: SITE_URL + $.param(param),
	    url: SITE_URL,
	    dataType: 'jsonp',
	    type: method_,
	    data: param,
	    async: true,
	    success: function (data) {
		callback.call(this, data);
	    }
	});
    },
    /**
     * 
     * @param {type} url
     * @returns {undefined}
     */
    loadPage: function (url, param) {
	//NProgress.start();
	var div = '.body_content';
	window.param = (typeof param === "undefined") ? null : param;
	window.cat_id = (typeof param === 'undefined') ? null : param.cat_id;
	window.name = (typeof param === 'undefined') ? null : param.name;
	window.product_id = (typeof param === 'undefined') ? null : param.product_id;
	$.ajax({
	    url: url,
	    dataType: 'html',
	    data: param,
	    async: true,
	    cache: true,
	    success: function (data, textStatus, XMLHttpRequest) {
		/*NProgress.done();*/
		$(div).off(); /*Calling .off() with no arguments removes all handlers attached to the elements.*/
		$(div).empty(); /*clearing the content of the div*/
		$(div).html(data);
		//NProgress.done();
//let us translate that part that comes with ajax
//alert(data);
	    },
	    error: function (xhr, textStatus, errorThrown) {
		/*Owden*/
		/* NProgress.done();*/
		if (xhr.responseText === undefined) {
		    alert('There was a problem on connecting to the network. Check your internet connection settings.');
		} else {
		    alert('Error occurs.');
		}
	    },
	    complete: function () {

	    }
	});
    },
    hash: function () {
	//$(window).hashchange();

//	var q = window.location.hash.substring(1);
//	if (q !== '') {
//	    this.loadPage(q + '.html');
//	} else {
//	    this.loadPage('body.html');
//	}
//	$(window).on('hashchange', function () {
//	    if (location.hash !== '') {
//		var q = window.location.hash.substring(1);
//		this.loadPage(q + '.html');
//	    }
//	});

    },
    /**
     * 
     * @param {String} cname: Name of that cookie
     * @param {String} cvalue: Cookie value to be stored
     * @param {integer} exdays: Cookie expired date
     * @returns {undefined}
     */
    setCookie: function (cname, value, exdays) {
	var d = new Date();
	/* 1= you set cookie to expire for 10 min*/
	var cvalue = JSON.stringify(value); // we force only json values to be stored
	d.setTime(d.getTime() + (exdays * 0.1 * 60 * 60 * 1000));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
    },
    /**
     * 
     * @param {String} cname
     * @returns {String}
     */
    getCookie: function (cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0) == ' ')
		c = c.substring(1);
	    if (c.indexOf(name) == 0)
		return JSON.parse(c.substring(name.length, c.length));
	}
	return "";
    },
    /*
     * 
     * @returns {undefined}
     */
    getNavigationPages: function () {
	var pages = {
	    'pg': 'landing',
	    'method': 'get_navigation'
	};
	/**this.get_remote(pages, function (data) {
	 // console.log(data);
	 $.each(data, function (i, val) {
	 //console.log(val);
	 var name = val.sales_cat_name.replace(/'/g, "\\'");
	 if ($('#' + val.sales_cat_id).length == 0) {
	 $('#nav_menu').append('<li id="' + val.sales_cat_id + '" class="active grid"><a class="color1" href="javascript:;" onmousedown="uza.loadPage(\'modules/product/product.html\',{cat_id:\'' + val.sales_cat_id + '\',type:\'product\',name:\''+ name+'\'})">' + val.sales_cat_name + '</a></li>');
	 }
	 });
	 }); */
    },
    logout: function () {
	this.setCookie('user', '', 0);
	window.location.reload();
    },
    addProduct: function (product) {
	//you will use cookies here, to store a product like 10min before expire
	//this will be more effective as cookie will be stored as objects
	//don't store data in a temporary div

	var products = this.getCookie('product_id');
	this.setCookie('product_id', product, 1);
	//console.log(products);
	if (typeof (products) === 'undefined' || products.length == '0') {
	    //if product is not available
	    this.setCookie('product_id', product, 1);
	} else {
	    //if product is available
	    var product_list = [products];
	    //console.log(product_list);
	    product_list.push(product);
	    //alert(pd);
	    console.log(product_list);
	    //var pd = $.extend({},product,products_id);
	    this.setCookie('product_id', product_list, 5);
	}
    },
    saleOrder: function () {
	var product_ids = '';
	$('.cart-items').each(function () {
	    product_ids += $(this).attr('data-id') + ',';
	});
	var quantity = '';
	$('.quantity').each(function () {
	    quantity += $(this).val() + ',';
	});
	user = this.getCookie('user');
	if (user !== '') {
	    var param = {
		product_id: product_ids,
		order_quantity: quantity,
		pg: 'sales',
		user_id: user.user_id,
		method: 'client_order'
	    }
	    this.get_remote(param, function (data) {
		console.log(data);
	    });
	} else {
	    this.loadPage('modules/login/login.html');
	}
    },
    emptyCart: function () {
	this.setCookie('product_id', '', 0);
	window.location.href = 'index.html';
    },
    find: function () {
	//$('#find').click(function () {
	// alert('search');
	var search = $('#search').val();
	//	alert(search);
	var param = {search: search, pg: 'products', method: 'search_product'};
	//  NProgress.start();
	this.get_remote(param, function (data) {
	    if (data.status === 1) {
		var results = data;
		uza.loadPage('modules/product/search_result.html',{param:results});
		//NProgress.done();
	    }
	    else if (data.status === 0) {
		$('#ajax_ad_results').html('<div class="alert alert-danger>' + data.message + '</div>"').fadeOut(7000);

		//   NProgress.done();
	    }
	});
	//});
    }
};
uza.init();
uza.hash();
user = uza.getCookie('user');
console.log(user);
//uza.getNavigationPages();


/**
 * how to call it
 */

/*
 * Example
 * 
 var param={  pg:'login',
 method:'testing',
 user:'mytetinsdata',
 id:'14'
 }
 uza.get_remote(param,function(data){
 alert(data);
 console.log(data); 
 
 });
 */
