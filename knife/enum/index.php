<!DOCTYPE html>
<html lang="en" >

<head>

  <meta charset="UTF-8">
 

  <title> Emergent Medical Idea</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  
  
<style>
html,body {
  font-family: 'Raleway', sans-serif;
  padding: 0;
  font-size: 18px;
  /*background: rgb(50, 120, 186);*/
  background: #FFF;
  color: #fff;
}

#menu{
  color: #000;
  max-width: 100%;
  text-align: right;
  font-size: 18px;
  padding: 20px;
  position: relative;
}

#menu ul li{
  display: inline-block;
  margin: 0 5px;
}

#menu ul li:first-child{
  position: absolute;
  top: 0;
  left: 20px;
}

.wrapper{
  max-width: 1000px;
  margin: 0 auto;
}
#heartRate{
  max-width: 500px;
}
.quote{
  max-width: 500px;
  margin-top: 10%;
}

h1,h2 {

  margin: 0.4em 0;
}
h1 { 
  font-size: 3.5em;
  font-weight: 700;

    /* Shadows are visible under slightly transparent text color */
    color: rgba(10,60,150, 0.8);
    text-shadow: 1px 4px 6px #fff, 0 0 0 #000, 1px 4px 6px #fff;
}

h2 {
  color: rgba(10,60,150, 1);
  font-size: 2em;
  font-weight: 200;
}
::-moz-selection { background: #5af; color: #fff; text-shadow: none; }
::selection { background: #5af; color: #fff; text-shadow: none; }
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>


</head>

<body translate="no" >
  <link href="https://fonts.googleapis.com/css?family=Raleway:200,100,700,4004" rel="stylesheet" type="text/css" />
<div id="menu">
  <ul>
    <li></li>
    <li>About EMA</li>
    <li>/</li>
    <li>Patients</li>
    <li>/</li>
    <li>Hospitals</li>
    <li>/</li>
    <li>Providers</li>
    <li>/</li>
    <li>E-MSO</li>
  </ul>
</div>
<div class="wrapper">
<div class ="quote">
<svg version="1.1" id="heartRate" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 699 114.3" enable-background="new 0 0 699 114.3" xml:space="preserve">
<path class="pather1" fill="none" stroke="#0A3C96" stroke-width="1" stroke-miterlimit="10" d="M707.9,78c0,0-17.1-0.6-31.1-0.6
	s-30,3.1-31.5,0.6S641,49.3,641,49.3l-10.5,58.5L619.3,7.5c0,0-11.3,66.8-12.5,70.5c0,0-17.1-0.6-31.1-0.6s-30,3.1-31.5,0.6
	s-4.3-28.8-4.3-28.8l-10.5,58.5L518.1,7.5c0,0-11.3,66.8-12.5,70.5c0,0-17.1-0.6-31.1-0.6s-30,3.1-31.5,0.6s-4.3-28.8-4.3-28.8
	l-10.5,58.5L417,7.5c0,0-11.3,66.8-12.5,70.5c0,0-17.1-0.6-31.1-0.6s-30,3.1-31.5,0.6s-4.3-28.8-4.3-28.8l-10.5,58.5L315.9,7.5
	c0,0-11.3,66.8-12.5,70.5c0,0-17.1-0.6-31.1-0.6s-30,3.1-31.5,0.6s-4.3-28.8-4.3-28.8L226,107.8L214.8,7.5c0,0-11.3,66.8-12.5,70.5
	c0,0-17.1-0.6-31.1-0.6s-30,3.1-31.5,0.6s-4.3-28.8-4.3-28.8l-10.5,58.5L113.6,7.5c0,0-11.3,66.8-12.5,70.5c0,0-17.1-0.6-31.1-0.6
	S40,80.5,38.5,78s-4.3-28.8-4.3-28.8l-10.5,58.5L12.5,7.5C12.5,7.5,1.3,74.3,0,78"/>
</svg>

<h2>At EMA we're taking care to a whole new level . . .</h2>
<h1>Taking care of our
  <span
     class="txt-rotate"
     data-period="2000"
     data-rotate='[ "patients.", "hospitals.", "providers." ]'></span>
</h1>
</div>
 </div>
    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>

  
      <script id="rendered-js" >
var TxtRotate = function (el, toRotate, period) {
  this.toRotate = toRotate;
  this.el = el;
  this.loopNum = 0;
  this.period = parseInt(period, 10) || 2000;
  this.txt = '';
  this.tick();
  this.isDeleting = false;
};

TxtRotate.prototype.tick = function () {
  var i = this.loopNum % this.toRotate.length;
  var fullTxt = this.toRotate[i];

  if (this.isDeleting) {
    this.txt = fullTxt.substring(0, this.txt.length - 1);
  } else {
    this.txt = fullTxt.substring(0, this.txt.length + 1);
  }

  this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

  var that = this;
  var delta = 300 - Math.random() * 100;

  if (this.isDeleting) {delta /= 2;}

  if (!this.isDeleting && this.txt === fullTxt) {
    delta = this.period;
    this.isDeleting = true;
  } else if (this.isDeleting && this.txt === '') {
    this.isDeleting = false;
    this.loopNum++;
    delta = 500;
  }

  setTimeout(function () {
    that.tick();
  }, delta);
};


window.onload = function () {
  var elements = document.getElementsByClassName('txt-rotate');
  for (var i = 0; i < elements.length; i++) {if (window.CP.shouldStopExecution(0)) break;
    var toRotate = elements[i].getAttribute('data-rotate');
    var period = elements[i].getAttribute('data-period');
    if (toRotate) {
      new TxtRotate(elements[i], JSON.parse(toRotate), period);
    }
  }
  // INJECT CSS
  window.CP.exitedLoop(0);var css = document.createElement("style");
  css.type = "text/css";
  css.innerHTML = ".txt-rotate > .wrap { border-right: 0.04em solid #666 }";
  document.body.appendChild(css);
};


var path = document.querySelector('path.pather1');
var length = path.getTotalLength();

// Clear any previous transition
path.style.transition = path.style.WebkitTransition =
'none';
// Set up the starting positions
path.style.strokeDasharray = length + ' ' + length;
path.style.strokeDashoffset = -length;
// Trigger a layout so styles are calculated & the browser
// picks up the starting position before animating
path.getBoundingClientRect();
// Define our transition
path.style.transition = path.style.WebkitTransition =
'stroke-dashoffset 4s linear';
// Go!
path.style.strokeDashoffset = '0';
//# sourceURL=pen.js
    </script>

  

</body>

</html>
 
