<?php

namespace Kontas\Entity;

class Projeto extends EntityBase {

    protected \PTK\Console\Flow\Program\ProgramInterface $program;
    protected \PDO $dbh;
    protected int $id = 0;

    public function __construct(\PTK\Console\Flow\Program\ProgramInterface $program) {
        $this->program = $program;
        $this->dbh = $program->dbh();
    }

    protected function exists(string $nome): bool {
        $stmt = $this->dbh->prepare('SELECT * FROM projetos WHERE nome LIKE :nome');
        $stmt->bindValue(':nome', $nome);
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        $rows = $stmt->fetchAll();

        if (sizeof($rows) === 0) {
            return false;
        }

        if (sizeof($rows) === 1) {
            return true;
        }
    }

    public function add(string $nome): int|bool {
        //nome ainda não existe?
        if ($this->exists($nome)) {
            $this->program->console()->error("$nome já está cadastrado.");
            return false;
        }

        //calcula nova id
        $this->id = $this->nextIdFor('projetos');

        //adiciona

        $this->dbh->beginTransaction();
        $stmt = $this->dbh->prepare('INSERT INTO projetos (id, nome, ativo) VALUES (:id, :nome, 1)');
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':nome', $nome);

        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        try {
            $this->dbh->commit();
        } catch (\Exception $ex) {
            $this->dbh->rollBack();
            throw $ex;
        }

        return $this->id;
    }

    public function load(int $id): bool {
        $stmt = $this->dbh->prepare('SELECT * FROM projetos WHERE id LIKE :id');
        $stmt->bindValue(':id', $id);
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        $rows = $stmt->fetchAll();

        if (sizeof($rows) === 0) {
            $this->program->console()->error("Id [$id] não encontrada.");
            return false;
        }


        $this->id = $id;
        return true;
    }

    public function nome(): string {
        $stmt = $this->dbh->prepare('SELECT * FROM projetos WHERE id = :id');
        $stmt->bindValue(':id', $this->id);
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        $row = $stmt->fetch();
        return $row['nome'];
    }

    public function id(): int {
        return $this->id;
    }

    public function ativo(): bool {
        $stmt = $this->dbh->prepare('SELECT * FROM projetos WHERE id = :id');
        $stmt->bindValue(':id', $this->id);
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        $row = $stmt->fetch();
        return (bool) $row['ativo'];
    }

    public function update(string|null $nome = null, bool|null $ativo): bool {
        //nome ainda não existe?
        if ($nome !== null) {
            if ($this->exists($nome)) {
                $this->program->console()->error("$nome já está cadastrado.");
                return false;
            }
        }

        $this->dbh->beginTransaction();

        if ($nome !== null && $ativo !== null) {
            $stmt = $this->dbh->prepare('UPDATE projetos SET nome = :nome, ativo = :ativo WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':ativo', $ativo, \PDO::PARAM_INT);
        } elseif ($nome !== null && $ativo === null) {
            $stmt = $this->dbh->prepare('UPDATE projetos SET nome = :nome WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':nome', $nome);
        } elseif ($nome === null && $ativo !== null) {
            $stmt = $this->dbh->prepare('UPDATE projetos SET ativo = :ativo WHERE id = :id');
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':ativo', $ativo, \PDO::PARAM_INT);
        } else {
            return false;
        }


        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        try {
            $this->dbh->commit();
        } catch (\Exception $ex) {
            $this->dbh->rollBack();
            throw $ex;
        }

        return true;
    }

    public function list(\PDOStatement|null $stmt = null): array {
        if ($stmt === null) {
            $stmt = $this->dbh->prepare('SELECT * FROM projetos ORDER BY ativo DESC, nome ASC');
        }
        if ($stmt->execute() === false) {
            throw new \PDOException("Falha ao executar consulta: {$stmt->errorInfo()}");
        }

        return $stmt->fetchAll();
    }

}
