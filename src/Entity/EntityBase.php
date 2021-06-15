<?php

namespace Kontas\Entity;

class EntityBase {
    protected function nextIdFor(string $table): int {
        $stmt = $this->dbh->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT 1");
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        $row = $stmt->fetch();

        if ($row === false) {
            return 1;
        }

        $currentId = $row['id'];

        return ($currentId + 1);
    }
}
