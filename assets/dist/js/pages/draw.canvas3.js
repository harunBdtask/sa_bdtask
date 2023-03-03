$(document).ready(function(){
    prepareCanvas2();
    $("#erase3").click(resetCanvas2);
});
// seconds sign
function addClick2(x, y, dragging2)
{
  clickX2.push(x);
  clickY2.push(y);
  clickDrag2.push(dragging2);
  clickColor2.push(currentColor);
  //console.log(clickX+", "+clickY+", "+clickDrag+", "+clickColor);
}

function prepareCanvas2(){
    canvas2 = $('#sign_w > canvas')[0];
    context2 = canvas2.getContext("2d");
    // clearCanvas();
    clickX2 = new Array();
    clickY2 = new Array();
    clickDrag2 = new Array();
    clickColor2 = new Array();

      $(canvas2).mousedown(function(e){
          console.log(this.offsetLeft);
          var mouseX = e.pageX - this.offsetLeft - $(this).parent().offset().left;
          var mouseY = e.pageY - this.offsetTop - $(this).parent().offset().top ;
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;
          paint2 = true;
          addClick2(e.pageX - offsetL, e.pageY - offsetT,false);
          redraw2();   
    });

    $(canvas2).mousemove(function(e){
      if(paint2){
          var offsetL = this.offsetLeft + $(this).parent().offset().left - 4;
          var offsetT = this.offsetTop + $(this).parent().offset().top - 4;        
        addClick2(e.pageX - offsetL, e.pageY - offsetT, true);
        redraw2();
      }
    });

    $(canvas2).mouseup(function(e){
      paint2 = false;
    });

    $(canvas2).mouseleave(function(e){
      paint2 = false;
    });
}

function redraw2(){
  clearCanvas2();
  context2.lineJoin = "round";
  context2.lineWidth = 2;
  for(var i=0; i < clickX2.length; i++)
      {        
        if(clickDrag2[i]){
                  context2.beginPath();
                  context2.moveTo(clickX2[i-1], clickY2[i-1]);
                  context2.lineTo(clickX2[i], clickY2[i]);
                  context2.closePath();
                  context2.strokeStyle = clickColor2[i];
                  context2.stroke();
        }
      }
}

function clearCanvas2(){
    context2.clearRect(0, 0, canvas2.width, canvas2.height);
    document.getElementById("canvasimg3").src="";
    document.getElementById("canvas_img3").value ="";
}

function save2() {
    var dataURL = canvas2.toDataURL();
    
    document.getElementById("canvasimg3").src = dataURL;
    document.getElementById("canvasimg3").style.display = "inline";
    document.getElementById("canvas_img3").value = dataURL;
}

function resetCanvas2()
{
    clickX2=[];
    clickY2=[];
    clickDrag2=[];
    clearCanvas2();
}