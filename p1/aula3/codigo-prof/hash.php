<?php
$nome = 'Thiago!';
echo "CRC32:\t\t",   crc32( $nome ), PHP_EOL;
echo "MD-5:\t\t",    md5( $nome ), PHP_EOL;
echo "SHA-1:\t\t",   sha1( $nome ), PHP_EOL;
echo "SHA-256:\t", hash( 'sha256', $nome ), PHP_EOL;