<?php
$nascimento = '01/02/1970';
$partes = explode( '/', $nascimento );
print_r( $partes ); // ['01', '02, '1970']

$nascimento2 = implode( '/', $partes );
echo $nascimento2, "\n"; // '01/02/1970'
