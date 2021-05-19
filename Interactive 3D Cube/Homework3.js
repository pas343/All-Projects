"use strict";

var canvas;
var gl;

var numPositions  = 36;

var positions = [];
var colors = [];

var xAxis = 0;
var yAxis = 1;
var zAxis = 2;
var axis = xAxis;
var theta = [0, 0, 0];
//same as in the colored cube example

var left = -2;
var right = -2;
var bottom = -2;
var ytop = -2;
var fov = 75;
var aspect = 1;
var near = 0.1;
var far = 10 ;

//here are some definitions that may be needed.
var modelMatrix=mat4();
var viewMatrix=mat4();
var projectionMatrix=mat4();
var resultMatrix=mat4();
var identityMatrix=mat4();
var matrixLoc;

var origin=vec3(0,0,0);
var cameraUp=vec3(0,1,0);
var cameraPosition=vec3(0,0,1);
var cameraLookingAtCube=false;
var cameraText;
var useProjection=false;
var usePerspective=false;
var userOrthographic = false;



window.onload = function init()
{
    canvas = document.getElementById("gl-canvas");

    gl = canvas.getContext('webgl2');
    if (!gl) alert("WebGL 2.0 isn't available");

    colorCube();

    gl.viewport(0, 0, canvas.width, canvas.height);
    gl.clearColor(1.0, 1.0, 1.0, 1.0);

    gl.enable(gl.DEPTH_TEST);

    //
    //  Load shaders and initialize attribute buffers
    //
    var program = initShaders(gl, "vertex-shader", "fragment-shader");
    gl.useProgram(program);

    var cBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, flatten(colors), gl.STATIC_DRAW);

    var colorLoc = gl.getAttribLocation( program, "aColor" );
    gl.vertexAttribPointer( colorLoc, 4, gl.FLOAT, false, 0, 0 );
    gl.enableVertexAttribArray( colorLoc );

    var vBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, vBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, flatten(positions), gl.STATIC_DRAW);


    var positionLoc = gl.getAttribLocation(program, "aPosition");
    gl.vertexAttribPointer(positionLoc, 4, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(positionLoc);

    matrixLoc = gl.getUniformLocation(program, "uMatrix");

    document.getElementById('x').oninput = function(e) {
        cameraPosition[0] = e.target.value
    }

    document.getElementById('y').oninput = function(e) {
        cameraPosition[1] = e.target.value
    }

    document.getElementById('z').oninput = function(e) {
        cameraPosition[2] = e.target.value
    }


	//2. switching between looking at the cube and at the default direction

    document.getElementById('atCube').onclick = function(e) {
        cameraLookingAtCube = e.target.checked
    }

	//3. switching between camera projections

    document.getElementById('useProjection').onclick = function(e) {
        useProjection = e.target.checked
    }

    document.getElementById('usePerspective').onclick = function(e) {
        usePerspective = e.target.checked
    }

    //keys to control the camera and cube for example. You can also use the mouse or sliders.
    
	window.addEventListener("keydown", e => {
        if (e.isComposing || e.keyCode === 229) 
        {
          return;
        }
      
        const speed = 4
        if (e.keyCode === 37) 
        {
            theta[0] -= speed
        }
        else if(e.keyCode === 39)
        {
            theta[0] += speed
        } 
        else if (e.keyCode === 40) 
        {
            theta[1] -= speed
        }
        else if (e.keyCode === 38) 
        {
            theta[1] += speed
        }
      })
    render();
}

function colorCube()
{
    quad(1, 0, 3, 2);
    quad(2, 3, 7, 6);
    quad(3, 0, 4, 7);
    quad(6, 5, 1, 2);
    quad(4, 5, 6, 7);
    quad(5, 4, 0, 1);
}

function quad(a, b, c, d)
{
    var vertices = [
        vec4(-0.5, -0.5,  0.5, 1.0),
        vec4(-0.5,  0.5,  0.5, 1.0),
        vec4(0.5,  0.5,  0.5, 1.0),
        vec4(0.5, -0.5,  0.5, 1.0),
        vec4(-0.5, -0.5, -0.5, 1.0),
        vec4(-0.5,  0.5, -0.5, 1.0),
        vec4(0.5,  0.5, -0.5, 1.0),
        vec4(0.5, -0.5, -0.5, 1.0)
    ];

    var vertexColors = [
        vec4(0.0, 0.0, 0.0, 1.0),  // black
        vec4(1.0, 0.0, 0.0, 1.0),  // red
        vec4(1.0, 1.0, 0.0, 1.0),  // yellow
        vec4(0.0, 1.0, 0.0, 1.0),  // green
        vec4(0.0, 0.0, 1.0, 1.0),  // blue
        vec4(1.0, 0.0, 1.0, 1.0),  // magenta
        vec4(0.0, 1.0, 1.0, 1.0),  // cyan
        vec4(1.0, 1.0, 1.0, 1.0)   // white
    ];

    // We need to parition the quad into two triangles in order for
    // WebGL to be able to render it.  In this case, we create two
    // triangles from the quad indices

    //vertex color assigned by the index of the vertex

    var indices = [a, b, c, a, c, d];

    for ( var i = 0; i < indices.length; ++i ) {
        positions.push( vertices[indices[i]] );
        //colors.push( vertexColors[indices[i]] );

        // for solid colored faces use
        colors.push(vertexColors[a]);
    }
}



function render()
{
    gl.clear( gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
	
	//compute the model matrix here instead of in the shader
    
    modelMatrix = mat4()
    modelMatrix = mult(modelMatrix, rotateX(theta[xAxis]))
    modelMatrix = mult(modelMatrix, rotateY(theta[yAxis]))
    modelMatrix = mult(modelMatrix, rotateZ(theta[zAxis]))

	//1. compute the view matrix when the camera is pointing at the -z direction.
    var cameraMatrix = translate(cameraPosition[0], cameraPosition[1], cameraPosition[2])
    viewMatrix = inverse(cameraMatrix)

    //2. add support for looking at the cube

    if (cameraLookingAtCube) 
    {
        viewMatrix = lookAt(cameraPosition, origin, cameraUp)
    }
    
    //3. add orthographic or perspective projection if it's enabled, and multiply the projection matrix with the model-view matrix.
    if (useProjection) 
    {

        if (usePerspective) 
        {
            projectionMatrix = perspective(fov, aspect, near, far)
        }
        else
        {
            projectionMatrix = ortho(left, right, bottom, ytop, near, far)
        }
    } 
    else 
    {
        projectionMatrix = mat4()
    }

    resultMatrix = mult(viewMatrix, modelMatrix)
    resultMatrix = mult(projectionMatrix, resultMatrix)

	//set the matrix uniform - flatten() already transposes into column-major order.
	
    gl.uniformMatrix4fv(matrixLoc, false, flatten(resultMatrix));

    gl.drawArrays(gl.TRIANGLES, 0, numPositions);

    // Update camera position text
    document.getElementById('camPosition').innerHTML = 
        'Camera position<br>' + 
        ' X: ' + cameraPosition[0] + 
        ' Y: ' + cameraPosition[1] + 
        ' Z: ' + cameraPosition[2] +
        '<br>Cube rotation<br>' +
        ' Theta: ' + theta[0] 

    requestAnimationFrame(render);
}