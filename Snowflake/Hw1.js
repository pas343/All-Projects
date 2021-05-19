/**********************
 * CS428 - Introduction to Computer Graphics
 * 14 Feb 2021
 * Homework 1
 * Koch SnowFlake
***********************/

var canvas;
var gl;

var positions = [];
var numTimesToSubdivide = 7; //global iteration value


window.onload = function init() {
    canvas = document.getElementById("gl-canvas");
    
    gl = canvas.getContext('webgl2');
    if (!gl) { alert("WebGL isn't available"); }

    
    //initalizing data for the koch snowflake 
    
    
    //first initalizing three verticies
    var vertices = [          
      vec2(-0.5, -0.5),
      vec2(0, Math.sqrt(3)*0.5-0.5),
      vec2(0.5, -0.5)
  ];
    //Calling snowflake function to divide 3 lines
    snowflake(vertices[0],vertices[1],vertices[2],numTimesToSubdivide);


    // Configure WebGl
    gl.viewport(0, 0, canvas.width, canvas.height);
    gl.clearColor(1.0, 1.0, 1.0, 1.0);

    // Load shaders and initialize attribute buffers

    var program = initShaders(gl, "vertex-shader", "fragment-shader");
    gl.useProgram(program);

    // Load the data into the GPU

    var bufferId = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, bufferId);
    gl.bufferData(gl.ARRAY_BUFFER, flatten(positions), gl.STATIC_DRAW);


    // Associate out shader variables with our data buffer

    var positionLoc = gl.getAttribLocation(program, "aPosition");
    gl.vertexAttribPointer(positionLoc, 2, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(positionLoc);

    render();
};

function triangle(a, b, c) 
{
  positions.push(a, b,c);
}
//Dividing in three lines
function snowflake(i,j,k,cnt)
{
    threeLines(i, j, cnt);
    threeLines(j,k, cnt);
    threeLines(k,i,cnt);
}
//divide each lines of the triangle and draw koch lines
function threeLines(l, m, sum)
{
  if(sum === 0)
  {
    var lhs, rhs;
    lhs = mix(l, m, 1/3);
    rhs = mix(l, m , 2/3);
    var result = sumPoints(lhs, rhs);
    
    draw(l, lhs, result, rhs, m);
  }
  else 
  {
    var a = mix (l, m, 1/3);
    var b = mix (m, l, 1/3);
    var k = sumPoints(a, b);
    
    --sum;
    
    threeLines(l, a, sum);
    threeLines(b, m, sum);
    threeLines(a, k, sum);
    threeLines(k, b, sum);
  }
    return result;
}
//Rotate the line to find a new point and than calculate them
function sumPoints(x, y)
{
  var degree = 30;
  var radian = degree * Math.PI / 90;
  var sin1 = Math.sin(radian);
  var cos1 = Math.cos(radian);

  var a = (y[0] - x[0]) * cos1 - (y[1] - x[1]) * sin1 + x[0];
  var b = (y[0] - x[0]) * sin1 + (y[1] - x[1]) * cos1 + x[1];
  var total = vec2(a,b);
  return total;
}
//draw lines with the 5 points
function draw(a,b,c,d,e)
{
    positions.push(a,b);
    positions.push(b,c);
    positions.push(c,d);
    positions.push(d,e);
}

function render() {
    gl.clear(gl.COLOR_BUFFER_BIT);
    gl.drawArrays(gl.LINES, 0, positions.length);

}