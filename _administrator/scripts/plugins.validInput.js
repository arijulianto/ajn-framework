// validate input numeric
$('input[type="number"],input[data-type="number"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9-,.]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input email
$('input[type="email"],input[data-type="email"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[a-zA-Z0-9-_.@]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input date
$('input[type="date"],input[data-type="date"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9-\/]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input time
$('input[type="time"],input[data-type="time"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9:-]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input telphone
$('input[type="tel"],input[data-type="tel"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9+ ]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input digits (only numeric)
$('input[data-type="digit"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input usermail (username or email)
$('input[data-type="usermail"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[a-zA-Z0-9-_.@]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input safename (username, slug)
$('input[data-type="safename"]').on('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[a-zA-Z0-9-_]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input alpha
$('input[data-type="text"]').on('keypress',function(e){
    var a = e;
	var regex = new RegExp("^[a-zA-Z ]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$.fn.doRemove = function() {
	var $this = $(this);
	$(this).slideUp(400,function(){
		$this.remove();
	})
	return this;
};

String.prototype.toTitleCase = function() {
  var i, j, str, lowers, uppers;
  str = this.replace(/([^\W_]+[^\s-]*) */g, function(txt) {
    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
  });
  return str;
}

Number.prototype.toHHMMSS = function() {
  var hours = Math.floor(this / 3600) < 10 ? ("00" + Math.floor(this / 3600)).slice(-2) : Math.floor(this / 3600);
  var minutes = ("00" + Math.floor((this % 3600) / 60)).slice(-2);
  var seconds = ("00" + (this % 3600) % 60).slice(-2);
  var result = hours + ":" + minutes + ":" + seconds;
  return result;
}

Number.prototype.fileSizeName = function() {
    var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = 0;
    var size = this;
    while(size >= 1024) {
        size /= 1024;
        ++i;
    }
    return size.toFixed(1) + ' ' + units[i];
}

Number.prototype.numberFormat = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
}

// Base64
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}