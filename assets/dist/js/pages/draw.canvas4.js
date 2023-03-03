var canvas4;
var context4;
var clickX4 = new Array();
var clickY4 = new Array();
var clickDrag4 = new Array();
var clickColor4 = new Array();
var paint4 = false;
var currentColor = "black";
$(document).ready(function(){
    prepareCanvas4();
    $("#erase4").click(resetCanvas4);
});
function addColor(obj) {
      switch (obj) {
          case "green":
              currentColor = "green";
              break;
          case "blue":
              currentColor = "blue";
              break;
          case "red":
              currentColor = "red";
              break;
          case "yellow":
              currentColor = "yellow";
              break;
          case "orange":
              currentColor = "orange";
              break;
          case "black":
              currentColor = "black";
              break;
          case "white":
              currentColor = "white";
              break;
      }
      clickColor4.push(currentColor);
  }
// seconds sign
function addClick4(x, y, dragging4)
{
  clickX4.push(x);
  clickY4.push(y);
  clickDrag4.push(dragging4);
  clickColor4.push(currentColor);
  //console.log(clickX+", "+clickY+", "+clickDrag+", "+clickColor);
}

function prepareCanvas4(){
    canvas4 = $('#sign_temp > canvas')[0];
    context4 = canvas4.getContext("2d");
    // clearCanvas();
    clickX4 = new Array();
    clickY4 = new Array();
    clickDrag4 = new Array();
    clickColor4 = new Array();

    canvas4.addEventListener("mousedown", mouseDown4, false);
    canvas4.addEventListener("mousemove", mouseXY4, false);
    canvas4.addEventListener("mouseleave", mouseLeave4, false);
    document.body.addEventListener("mouseup", mouseUp4, false);

    //For mobile
    canvas4.addEventListener("touchstart", mouseDown4, false);
    canvas4.addEventListener("touchmove", mouseXYTouch4, true);
    canvas4.addEventListener("mouseleave", mouseLeave4, false);
    canvas4.addEventListener("touchend", mouseUp4, false);
    document.body.addEventListener("touchcancel", mouseUp4, false);
}

function mouseXYTouch4(e) {
    e.preventDefault(); 
    var touches = e.touches || [];
    var touch = touches[0] || {};
   if(paint4){
      var offsetL = this.offsetLeft + $(this).parent().offset().left - 8;
      var offsetT = this.offsetTop + $(this).parent().offset().top - 16;        
    addClick4(touch.pageX - offsetL, touch.pageY - offsetT, true);
    redraw4();
  }
}

function mouseXY4(e) {
   if(paint4){
      var offsetL = this.offsetLeft + $(this).parent().offset().left - 8;
      var offsetT = this.offsetTop + $(this).parent().offset().top - 16;        
    addClick4(e.pageX - offsetL, e.pageY - offsetT, true);
    redraw4();
  }
}

function mouseUp4() {
  paint4 = false;
}

function mouseLeave4() {
  paint4 = false;
}

function mouseDown4(e)
{
  var mouseX = e.pageX - this.offsetLeft - $(this).parent().offset().left;
  var mouseY = e.pageY - this.offsetTop - $(this).parent().offset().top ;
  var offsetL = this.offsetLeft + $(this).parent().offset().left - 8;
  var offsetT = this.offsetTop + $(this).parent().offset().top - 16;
  paint4 = true;
  addClick4(e.pageX - offsetL, e.pageY - offsetT,false);
  redraw4();   
}

function redraw4(){
  clearCanvas4();
  context4.lineJoin = "round";
  context4.lineWidth = 2;
  var background = new Image();
  background.src = template_img_url;   
  context4.drawImage(background, 0, 0);
  // create pattern
  var ptrn = context4.createPattern(background, 'repeat'); // Create a pattern with this image, and set it to "repeat".
  for(var i=0; i < clickX4.length; i++)
      {        
        if(clickDrag4[i]){
                  context4.beginPath();
                  context4.moveTo(clickX4[i-1], clickY4[i-1]);
                  context4.lineTo(clickX4[i], clickY4[i]);
                  context4.closePath();
                  context4.strokeStyle = clickColor4[i];
                  context4.fillStyle = ptrn;
                  context4.fillRect(0, 0, context4.width, context4.height);
                  context4.stroke();
        }
      }
}

function clearCanvas4(){
    context4.clearRect(0, 0, canvas4.width, canvas4.height);
    document.getElementById("canvasimg4").src="";
    document.getElementById("canvas_img4").value ="";
}

function save4() {
    var dataURL = canvas4.toDataURL("image/png");
    
    document.getElementById("canvasimg4").src = dataURL;
    document.getElementById("canvasimg4").style.display = "inline";
    document.getElementById("canvas_img4").value = dataURL;
}

function resetCanvas4()
{
    clickX4=[];
    clickY4=[];
    clickDrag4=[];
    clickColor4=[];
    clearCanvas4();
}