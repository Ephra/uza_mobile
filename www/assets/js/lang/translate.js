
var initial;
var controller;
var div_info = '.container';
// begin

function begin_translation(pg, default_div) {
    var def_p;
    // check_key(pg);
    if (pg == undefined) {
	var url = window.location.href;
	var url_param = url.split('/').length;
	var pg = url.split('/')[url_param - 1];
	initial = pg.charAt(0);
    } else {
	initial = pg.charAt(0);
    }
    // we have to store the initial somewhere so we can use different approach

    if (default_div == undefined) {
	def_p = 'body';
    } else {
	def_p = default_div;
    }
    var num = 1;
    $(def_p + " > *").each(function () {
	createNode(this);
	$(".trl").each(function () {
	    var classtag = $(this).attr('class').split(' ')[0];
	    var key_exist = $(this).attr('key');
	    if (classtag !== 'oneterm' && key_exist === undefined) {
		$(this).attr('key', initial + num);
		num = num + 1;
	    }
	});
    });
}

function set_lang_Cookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function get_lang_Cookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0) == ' ')
	    c = c.substring(1);
	if (c.indexOf(name) == 0)
	    return c.substring(name.length, c.length);
    }
    return "";
}

var lang_cookie = get_lang_Cookie('lang');


function translate(a) {
    if (lang_cookie === 'sw' || a === 'sw') {
	$('.dataTables_info').removeClass('trl');
	$('.select2-chosen').removeClass('trl');
	//$('.panel-body').removeClass('trl');
	$('.trl').each(function () {
	    var key = $(this).attr('key');

	    var classtag = $(this).attr('class').split(' ')[0];
	    if (classtag !== 'notranslate') {
		if (l[key] === undefined) {
		    $(this).text(l[key] + ' key no=' + key);
		} else {
		    $(this).text(l[key]);
		}
	    }
	});
	placeholder();
    }
}
function placeholder() {
    $("input[type=text], input[type=password], textarea").each(function ()
    {
	var val = $(this).attr('placeholder');
	var key = $(this).attr('key');

	if (typeof val !== undefined) {
	   // if (l[key] === undefined) {
	//	$(this).attr("placeholder", l[key] + ' key no=' + key);
	   // } else {
		$(this).attr("placeholder", l[key]);
	   // }
	}else{
	    $(this).attr("placeholder", '');
	}
	if(val=='Search'){
	    $(this).attr("placeholder",'Tafuta');
	}
    });
}

language_translate = function () {
    $('.lang').click(function () {
	var lang = $(this).attr('id'); /* obtain language id */
	//save language code in cookie now
	set_lang_Cookie('lang', lang, 1);
	if (lang == 'sw') {
	    /* translate all translatable elements */
	    translate('sw');
	} else {
	    setCookie('lang', '', -1);
	    window.location.reload();
	}
    });
};
var no = 0;
function addChildren(element) {
    var command = "";
    $(element).find("> *").each(function () {
	command += createNode(this);

    });
    return command;
}
// if the element has text, add the text
function addText(element) {
    var elementText = $(element).clone().children().remove().end().text().trim();
    var placeholder = $(element).clone().attr('placeholder');
    var tagname = $(element).clone().prop("tagName").toLowerCase(); //this is the tag name
    if (elementText !== '' && tagname !== 'script') {
	no = no + 1;
	//alert(elementText+no);
	var classname = $(element).clone().attr("class"); //this is the tag name
	var idname = $(element).clone().attr("id"); //this is the tag name
	var stringlen = '"' + classname + tagname + idname + '"'; //probabily to have this val the same is very very low
	//var no=stringlen.length;
	//you can now add a class you want
	//and add the ID you want
	//you can get all contents and send them to a page you want via ajax or mapping
	//you can even add a key with a specific tag name as you want
	//
	//FOR NOW, let us just add a key and class name
	//console.log(no + '=' + tagname + '=' + elementText + '==' + classname);
	//if (idname === 'undefined') {
	//get one class name now

	var oneclass = classname === undefined ? classname : classname.split(' ')[0];

	var exclude = ['td', 'notranslate'];

	if (tagname !== 'td' && oneclass !== 'notranslate' && oneclass !== '') {
	    $(tagname + '.' + oneclass).addClass('trl');
	}
	if (oneclass === 'notranslate') {
	    // $('.dataTables_info').removeClass('trl').removeAttr('key');
	}

	return ".addText(\"" + elementText + "\") and tag name=" + tagname + ")<br/>";
    } else {
	return "";
    }
}
// returns the 'CreateNode(...)' method call for a node and all its children.
function createNode(element) {
    var nodeName = element.nodeName.toLowerCase();
    var csharpCommand = nodeName;
    csharpCommand += addChildren(element);
    csharpCommand += addText(element);
    // console.log(csharpCommand);
    return csharpCommand;
}

$(document).ready(language_translate);
$(document).ready(translate);
