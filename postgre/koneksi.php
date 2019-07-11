<?php
$conn= pg_connect("host=localhost port=5432 dbname=00f_unsadasentimen_analis user=postgres password='pgadmin'");


if ($conn) {echo 'berhasil';
} else{echo 'gagal';}
?>