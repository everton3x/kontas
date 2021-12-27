<?php

/**
 * Funções para tags
 */

 function listarTags(): array
 {
     $con = conexao();
     $stmt = $con->prepare('SELECT * FROM listatags');
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }