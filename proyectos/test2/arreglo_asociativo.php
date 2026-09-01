<?php

$listaAlumnos = [
    "TPI"=>[["Nombre" =>"Dayna","Carnet" =>"MS21017"],
            ["Nombre"=>"Jairo","Carnet"=> "AA23027"]],
    "SO"=>[["Nombre"=>"Vilma 1","Carnet"=>"VV24067"],
            ["Nombre"=>"Vilma 2","Carnet"=>"VV24068"]]
]

?>

<h1>Listado de estudiantes por  materias</h1>

<?php

foreach( $listaAlumnos as $listaAlumno => $valor) {
    echo "<h2>$listaAlumno</h2>";

    foreach( $valor as $Alumno ) {
        ?>
        <p><?=$Alumno["Nombre"]  ," ",$Alumno["Carnet"]?></p>
        <?php
    }
}
?>