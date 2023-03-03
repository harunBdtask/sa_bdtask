$(document).ready(function(){
    prepareCanvas1();
    $("#erase2").click(resetCanvas1);
});
// seconds sign
function addClick1(x, y, dragging1)
{
  clickX1.push(x);
  clickY1.push(y);
  clickDrag1.push(dragging1);
  clickColor1.push(currentColor);
  //console.log(clickX+", "+clickY+", "+clickDrag+", "+clickColor);
}

function prepareCanvas1(){
    canvas1 = $('#sign_d > canvas')[0];
    context1 = canvas1.getContext("2d");
    // clearCanvas();
    clickX1 = new Array();
    clickY1 = new Array();
    clickDrag1 = new Array();
    clickColor1 = new Array();

      $(canvas1).mousedown(function(e){
          console.log(this.offsetLeft);
          var mouseX = e.pageX - this.offsetLeft - $(this).parent().offset().left;
          var mouseY = e.pageY - this.offsetTop - $(this).parent().offset().top ;
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;
          paint1 = true;
          addClick1(e.pageX - offsetL, e.pageY - offsetT,false);
          redraw1();   
    });

    $(canvas1).mousemove(function(e){
      if(paint1){
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;        
        addClick1(e.pageX - offsetL, e.pageY - offsetT, true);
        redraw1();
      }
    });

    $(canvas1).mouseup(function(e){
      paint1 = false;
    });

    $(canvas1).mouseleave(function(e){
      paint1 = false;
    });
}

function redraw1(){
  clearCanvas1();
  context1.lineJoin = "round";
  context1.lineWidth = 2;
  for(var i=0; i < clickX1.length; i++)
      {        
        if(clickDrag1[i]){
                  context1.beginPath();
                  context1.moveTo(clickX1[i-1], clickY1[i-1]);
                  context1.lineTo(clickX1[i], clickY1[i]);
                  context1.closePath();
                  context1.strokeStyle = clickColor1[i];
                  context1.stroke();
        }
      }
}

function clearCanvas1(){
    context1.clearRect(0, 0, canvas1.width, canvas1.height);
    document.getElementById("canvasimg2").src="";
    document.getElementById("canvas_img2").value ="";
}

function save1() {
    var dataURL = canvas1.toDataURL();
    
    document.getElementById("canvasimg2").src = dataURL;
    document.getElementById("canvasimg2").style.display = "inline";
    document.getElementById("canvas_img2").value = dataURL;
}

function resetCanvas1()
{
    clickX1=[];
    clickY1=[];
    clickDrag1=[];
    clearCanvas1();
}