function jah(url,target) {
  var req;
  // native XMLHttpRequest object
  document.getElementById(target).innerHTML = '&nbsp;';
  if (window.XMLHttpRequest) {
    req = new XMLHttpRequest();
    req.onreadystatechange = function() {jahDone(target, req);};
    req.open("GET", url, true);
    req.send(null);
  // IE/Windows ActiveX version
  } else if (window.ActiveXObject) {
    req = new ActiveXObject("Microsoft.XMLHTTP");
    if (req) {
      req.onreadystatechange = function() {jahDone(target, req);};
      req.open("GET", url, true);
      req.send();
    }
  }
}

function jahDone(target, req) {
  var results = "";
  // only if req is "loaded"
  if (req.readyState == 4) {
    // only if "OK"
    if (req.status == 200) {
      results = req.responseText;
      document.getElementById(target).innerHTML = results;
    } else {
      document.getElementById(target).innerHTML="jah error:\n" +
          req.statusText;
    }
  }
}