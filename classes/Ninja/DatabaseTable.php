<?php
namespace Ninja;

class DatabaseTable
{
    private $db;
    private $table;
    private $primaryKey;

    public function __construct(\PDO $db, string $table, string $primaryKey)
    {
        $this->db         = $db;
        $this->table      = $table;
        $this->primaryKey = $primaryKey;
    }

    private function query($sql, $parameters = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parameters);
        return $stmt;
    }

    public function total()
    {
        $stmt = $this->query('SELECT COUNT(*) FROM `' . $this->table . '`');
        $row  = $stmt->fetch();
        return $row[0];
    }

    public function findById($value)
    {
        $sql        = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :value';
        $parameters = ['value' => $value];
        $stmt       = $this->query($sql, $parameters);

        echo 'foo claims the value of pk is: ' . $this->primaryKey;
        var_dump($stmt->fetch());
        return $stmt->fetch();
    }

    public function find($column, $value)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' =  :value';

        $parameters = ['value' => $value];
        $stmt       = $this->query($sql, $parameters);

        return $stmt->fetchAll();
    }

    private function insert($fields)
    {
        $sql = 'INSERT INTO `' . $this->table . '` (';
        foreach ($fields as $key => $value) {
            $sql .= '`' . $key . '`,';
        }
        $sql = rtrim($sql, ',');
        $sql .= ') VALUES (';
        foreach ($fields as $key => $value) {
            $sql .= ':' . $key . ',';
        }
        $sql = rtrim($sql, ',');
        $sql .= ')';
        $fields = $this->processDates($fields);
        $this->query($sql, $fields);
    }

    private function update($fields)
    {
        $sql = ' UPDATE `' . $this->table . '` SET ';
        foreach ($fields as $key => $value) {
            $sql .= '`' . $key . '` = :' . $key . ',';
        }
        $sql = rtrim($sql, ',');
        $sql .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';
        // Set the :primaryKey variable
        $fields['primaryKey'] = $fields['id'];
        $fields               = $this->processDates($fields);
        $this->query($sql, $fields);
    }

    public function delete($id)
    {
        $parameters = [':id' => $id];
        $this->query('DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id', $parameters);
    }

    public function findAll()
    {
        $result = $this->query('SELECT * FROM `' . $this->table . '`');

        return $result->fetchAll();
    }

    private function processDates($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d');
            }
        }

        return $fields;
    }

    public function save($record)
    {
        try {
            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }
            $this->insert($record);
        } catch (\PDOException $e) {
            $this->update($record);
        }
    }
}
