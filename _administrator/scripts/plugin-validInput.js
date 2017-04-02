// validate input numeric
$('input[type="number"],[data-type="number"]').live('keypress',function(e){
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
$('input[type="email"]').live('keypress',function(e){
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
$('input[type="date"],input[data-type="date"]').live('keypress',function(e){
    var a = e;
    var regex = new RegExp("^[0-9-\/]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// validate input telphone
$('input[type="tel"]').live('keypress',function(e){
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
$('input[data-type="digit"]').live('keypress',function(e){
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
$('input[data-type="usermail"]').live('keypress',function(e){
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
$('input[data-type="safename"]').live('keypress',function(e){
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
$('input[data-type="text"]').live('keypress',function(e){
    var a = e;
	var regex = new RegExp("^[a-zA-Z ]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str) || a.keyCode==46||a.keyCode==8||a.keyCode==9||a.keyCode==27||a.keyCode==13||(a.keyCode==65&&a.ctrlKey===true)||(a.keyCode>=35&&a.keyCode<=39)) {
        return true;
    }
    e.preventDefault();
    return false;
});



function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}

function str2slug(str) {
      return str.replace(/\s+/g, '-').replace(/([a-z\d])([A-Z])/g, '$1$2');
   }
