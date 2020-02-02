<?php

namespace App\Table;

use App\Model\Post;
use App\Table\Exception\NotFoundException;
use \PDO;

/**
 * Class Table
 * @package App\Table
 *
 * abstract ( une class qui a pour vocation de servir de model/ a être héritée pour les class enfant )
 */
abstract class Table
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected $table = null;

    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if ($this->table === null) {
            throw new \Exception("La class " . get_class($this) . " n'a pas de propriété \$table");
        }
        if ($this->class === null) {
            throw new \Exception("La class ". get_class($this) . " n'a pas de propriété \$class");
        }
        $this->pdo = $pdo;
    }

    /**
     * Permet de récupérer un enregistrement par rapport a la clé primaire ( id )
     */
    public function find(int $id)
    {
        /* Récupération de l'article par l'id */
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute([
            "id" => $id
        ]);
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        /** @var Post|false */
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }

    /**
     * Permet de vérifier si une valeur éxiste déjà dans la table
     *
     * @param string $field Champs à vérifier
     * @param mixed $value Valeur associé au champs
     * @param int|null $except
     * @return bool
     */
    public function exist(string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $params = [$value];
        if ($except !== null) {
            $sql .= " AND id != ?";
            $params[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
    }

    /**
     * Permet de récupérer tous les enregistrements
     *
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

    /**
     * Permet de supprimer un enregistrement dans la base de données
     *
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $deletion = $query->execute([$id]);
        if ($deletion === false) {
            throw new \Exception(
                "Impossible de supprimer l'enregistrement numéro {$id}, dans la table {$this->table}
                ");
        }
    }

    /**
     * Permet de créer un enregistrement dans la base de données
     *
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function create(array $data): int
    {
        $sqlFields = [];
        /* $key = nom du champ, value = la valeur associée */
        foreach ($data as $key => $value) {
            /* ex : name = :name */
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare(
            "INSERT INTO {$this->table} 
                    SET ". implode(", ", $sqlFields));
        $create = $query->execute($data);
        if ($create === false) {
            throw new \Exception("Impossible créer l'enregistrement dans la table {$this->table}");
        }
        return (int)$this->pdo->lastInsertId(); // retourne l'id du derniere enregistrement
    }

    /**
     * Permet de faire des modifications d'un enregistrement
     *
     * @param array $data
     * @param int $id
     * @throws \Exception
     */
    public function update(array $data, int $id)
    {
        $sqlFields = [];
        /* $key = nom du champ, value = la valeur associée */
        foreach ($data as $key => $value) {
            /* ex : name = :name */
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare(
            "UPDATE {$this->table} 
                    SET ". implode(", ", $sqlFields) . " WHERE id = :id");
        /* array_merge , fusionne les deux tableaux */
        $create = $query->execute(array_merge($data, ["id" => $id]));
        if ($create === false) {
            throw new \Exception("Impossible modifer l'enregistrement dans la table {$this->table}");
        }
    }

    public function queryAndFetchAll(string $sql)
    {
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }
}
