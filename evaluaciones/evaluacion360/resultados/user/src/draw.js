// Objeto que mostrará los resultados del empleado
var contexto;

// Establece el color
function color(color)
{
	contexto.fillStyle = color;
}

function line(x1, y1, x2, y2)
{
	contexto.moveTo(x1, y1);
	contexto.lineTo(x2, y2);
	contexto.stroke();
}

function rect(x, y, w, h)
{
	contexto.fillRect(x, y, w, h);
	contexto.stroke();
}

function write(texto, x, y)
{
	contexto.fillText(texto, x, y);
}

function translate(x, y)
{
	contexto.translate(x, y);
}