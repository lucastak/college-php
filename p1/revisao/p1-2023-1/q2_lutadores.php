<?php
// q2_lutadores.php
// Questão 2 - 2023/1

try {
    $pdo = new PDO('mysql:host=localhost;dbname=mma;charset=utf8', 'dev', '123456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== LUTADORES ===\n";
    $stmt = $pdo->query("SELECT * FROM lutador");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | Nome: {$row['nome']} | Peso: {$row['peso_em_quilos']} kg | Altura: {$row['altura_em_metros']} m\n";
    }
    
    echo "\n=== ESTATÍSTICAS ===\n";
    $stmtStats = $pdo->query("
        SELECT 
            COUNT(id) as total_lutadores,
            AVG(altura_em_metros) as media_alturas,
            MAX(altura_em_metros) as maior_altura,
            MAX(peso_em_quilos) as maior_peso
        FROM lutador
    ");
    
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
    
    echo "Total de lutadores: {$stats['total_lutadores']}\n";
    
    $media = number_format((float)$stats['media_alturas'], 2, ',', '.');
    $altura = number_format((float)$stats['maior_altura'], 2, ',', '.');
    $peso = number_format((float)$stats['maior_peso'], 2, ',', '.');
    
    echo "Média de alturas: {$media} m\n";
    echo "Maior altura: {$altura} m\n";
    echo "Maior peso: {$peso} kg\n";

} catch (PDOException $e) {
    die("Erro ao conectar ou consultar o banco: " . $e->getMessage() . "\n");
}
