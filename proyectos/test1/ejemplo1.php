<?php

$nombre_alumno = "Hector";
$edad_alumno = 6;

echo "Hola, mundo ";
print "Hola, mundo <br><hr>";

$array = ["item 1", "item 2"];

$precio_matricula = 58.67676767;

print_r($array);
echo "<br><hr>";
var_dump($array);
echo "<br><hr>";
var_dump($edad_alumno);
echo "<br><hr>";

printf("Precio de matricula %.2f",$precio_matricula);

echo <<<HTML
        <p> texto: $nombre_alumno</p>
        HTML;

?>

<h1><?=  "Me llamo " , $nombre_alumno , " y tengo " ,$edad_alumno ," años" ?></h1>