// validate input numeric
$('input[type="number"],input[data-type="number"]').live('keypress',function(e){
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
$('input[type="email"],input[data-type="email"]').live('keypress',function(e){
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
// validate input time
$('input[type="time"],input[data-type="time"]').live('keypress',function(e){
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
$('input[type="tel"],input[data-type="tel"]').live('keypress',function(e){
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
  lowers = ['A', 'An', 'The', 'And', 'But', 'Or', 'For', 'Nor', 'As', 'At', 
  'By', 'For', 'From', 'In', 'Into', 'Near', 'Of', 'On', 'Onto', 'To', 'With',
  ];
  for (i = 0, j = lowers.length; i < j; i++)
    str = str.replace(new RegExp('\\s' + lowers[i] + '\\s', 'g'), 
      function(txt) {
        return txt.toLowerCase();
      });
  uppers = ['Id', 'Tv','Pc'];
  for (i = 0, j = uppers.length; i < j; i++)
    str = str.replace(new RegExp('\\b' + uppers[i] + '\\b', 'g'), 
      uppers[i].toUpperCase());

  return str;
}

String.prototype.toTitleCase = function() {
  var i, j, str, lowers, uppers;
  str = this.replace(/([^\W_]+[^\s-]*) */g, function(txt) {
    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
  });
  lowers = ['A', 'An', 'The', 'And', 'But', 'Or', 'For', 'Nor', 'As', 'At', 'By', 'For', 'From', 'In', 'Into', 'Near', 'Of', 'On', 'Onto', 'To', 'With', 'Dan'];
  for (i = 0, j = lowers.length; i < j; i++)
    str = str.replace(new RegExp('\\s' + lowers[i] + '\\s', 'g'), 
      function(txt) {
        return txt.toLowerCase();
      });
  uppers = ['Id', 'Tv','Pc'];
  for (i = 0, j = uppers.length; i < j; i++)
    str = str.replace(new RegExp('\\b' + uppers[i] + '\\b', 'g'), 
      uppers[i].toUpperCase());

  return str;
}



function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}

function str2slug(str) {
      return str.replace(/\s+/g, '-').replace(/([a-z\d])([A-Z])/g, '$1$2');
   }