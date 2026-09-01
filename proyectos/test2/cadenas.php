<?php

$cadena_prueba = "Hola mundo";

echo "<br><hr>";
echo "Longitud de la cadena: ".strlen($cadena_prueba);

echo "<br><hr>";
echo "Existe la palabra 'hola' : ", str_contains($cadena_prueba,"Hola") ? "Si existe" : "No existe";

echo "<br><hr>";
echo "Numero de palabras:  ", str_word_count($cadena_prueba);

echo "<br><hr>";
echo "Cadena en mayusculas: ", strtoupper($cadena_prueba);

echo "<br><hr>";
$vector_cadena = explode(" ", $cadena_prueba); 
var_dump($vector_cadena);

echo "<br><hr>";
$arreglo_letras = ["A","B","C"];
echo "arreglo convertido: ", implode(" ", $arreglo_letras);

$arreglo_numeros = array(1,2,3,4);

$arreglo_numeros[] = 100;


echo "<br><hr>";
$arreglo_filtrado = array_filter($arreglo_numeros, fn($value)=> $value % 2 == 0);

print_r($arreglo_filtrado);

echo "<br><hr>";
$arreglo_map = array_map(function ($item) {return strtolower($item);},$arreglo_letras);
print_r($arreglo_map);

echo "<br><hr>";
$union_arreglo = array_merge($arreglo_letras, $arreglo_numeros);
print_r($union_arreglo);
?>

<h1>Numeros</h1>
<ul>
    <?php 
    foreach( $arreglo_numeros as $item){
        ?>
        <li>
            <p style="display: block; background-color: red; color: white;"><?= $item ?></p>
        </li>
        <?php
    }
    ?>
</ul>

<p> numero de elementos en el arreglo <?= count($arreglo_numeros); ?></p>
<p> suma de elementos en el arreglo <?= array_sum($arreglo_numeros); ?></p>