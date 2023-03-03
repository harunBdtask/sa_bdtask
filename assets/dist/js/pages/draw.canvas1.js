var canvas, canvas1, canvas2;
var context, context1, context2;
var clickX, clickX1, clickX2 = new Array();
var clickY, clickY1, clickY2 = new Array();
var clickDrag, clickDrag1,clickDrag2 = new Array();
var clickColor, clickColor1, clickColor2 = new Array();
var paint, paint1, paint2 = false;
var currentColor = "#000000";

$(document).ready(function(){
    prepareCanvas();
    $("#erase").click(resetCanvas);
});

function addClick(x, y, dragging)
{
  clickX.push(x);
  clickY.push(y);
  clickDrag.push(dragging);
  clickColor.push(currentColor);
  //console.log(clickX+", "+clickY+", "+clickDrag+", "+clickColor);
}

function prepareCanvas(){
    canvas = $('#sign_p > canvas')[0];
    context = canvas.getContext("2d");
    // clearCanvas();
    clickX = new Array();
    clickY = new Array();
    clickDrag = new Array();
    clickColor = new Array();

      $(canvas).mousedown(function(e){
          console.log(this.offsetLeft);
          var mouseX = e.pageX - this.offsetLeft - $(this).parent().offset().left;
          var mouseY = e.pageY - this.offsetTop - $(this).parent().offset().top ;
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;
          paint = true;
          addClick(e.pageX - offsetL, e.pageY - offsetT,false);
          redraw();   
    });

    $(canvas).mousemove(function(e){
      if(paint){
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;        
        addClick(e.pageX - offsetL, e.pageY - offsetT, true);
        redraw();
      }
    });

    $(canvas).mouseup(function(e){
      paint = false;
    });

    $(canvas).mouseleave(function(e){
      paint = false;
    });
}

function redraw(){
  clearCanvas();
  context.lineJoin = "round";
  context.lineWidth = 2;
  for(var i=0; i < clickX.length; i++)
      {        
        if(clickDrag[i]){
                  context.beginPath();
                  context.moveTo(clickX[i-1], clickY[i-1]);
                  context.lineTo(clickX[i], clickY[i]);
                  context.closePath();
                  context.strokeStyle = clickColor[i];
                  context.stroke();
        }
      }
}

function clearCanvas(){
    context.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById("canvasimg1").src="";
    document.getElementById("canvas_img1").value ="";
}

function save() {
    var dataURL = canvas.toDataURL();
    
    document.getElementById("canvasimg1").src = dataURL;
    document.getElementById("canvasimg1").style.display = "inline";
    document.getElementById("canvas_img1").value = dataURL;
}

function resetCanvas()
{
    clickX=[];
    clickY=[];
    clickDrag=[];
    clearCanvas();
}
