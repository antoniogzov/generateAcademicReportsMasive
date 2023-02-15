function timer(ms) {
  return new Promise((res) => setTimeout(res, ms));
}

function RQBangFemHebPrim(q) {
  if (!isNaN(q)) {
    var q_arr = q.split(".");
    var q_int = parseInt(q_arr[0]);
    var q_dec = parseInt(q_arr[1]);
    if (q == 10) {
      q_int = 10;
    } else if (q_dec >= 6) {
      q_int = q_int + 1;
    }
    return q_int;
  } else {
    return q;
  }
}

function redondear(num) {
  var red = num.split(".");
  var final = parseInt(red[0]);
  if (red[1] != "undefined") {
    if (red[1] > "5") {
      final = final + 1;
    }
  }
  return final;
}

function reverse(s) {
  //console.log(s);
  if (s == null) {
    return "";
  } else {
    return s.split("").reverse().join("");
  }
}

function fixLetter(str) {
  const regex = /[a-zA-Z]/;
  if (regex.test(str)) {
    return str;
  } else {
    var final_str = "";
    var lines = str.split("\n");
    //console.log(lines);
    lines.forEach(function callback(currentValue, index, array) {
      final_str += currentValue.split("").reverse().join("");
      if (index + 1 < lines.length) {
        final_str += "\n";
      }
    });
    return final_str;
  }
}

function RQhighschoolLF(q) {
  if (!isNaN(q)) {
    var q_arr = q.split(".");
    var q_int = parseInt(q_arr[0]);
    var q_dec = parseInt(q_arr[1]);
    if (q == 10) {
      q_int = 10;
    } else if (q < 6) {
      if (q_dec > 0) {
        q_int = q_int;
      }
    } else if (q >= 6) {
      if (q_dec >= 5) {
        q_int = q_int + 1;
      } else {
        q_int = q_int;
      }
    }
    return q_int;
  } else {
    return q;
  }
}
function ParseFloatCero(q) {
    q = parseFloat(q);
    console.log(q);
  if (!isNaN(q)) {
    var q_arr = q.split(".");

    var q_int = parseInt(q_arr[0]);
    var q_dec = parseInt(q_arr[1]);

    if (q == 10) {
      q_int = 10;
    } else if ((q_dec = 0)) {
      q_int = q_int;
    } else if (q > 0) {
        q_int = q;
    }
    return q_int;
  } else {
    return q;
  }
}
//--- OBTENER IMAGENES ---//
function getImgFromUrl(logo_url, callback) {
  var img = new Image();
  img.src = logo_url;
  img.onload = function () {
    callback(img);
  };
}
