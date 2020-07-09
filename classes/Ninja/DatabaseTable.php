<?php
namespace Ninja;

class DatabaseTable
{
    private $db;
    private $table;
    private $primaryKey;
    private $className;
    private $constructorArgs;

    public function __construct(\PDO $db, string $table, string $primaryKey, string $className = '\stdClass', array $constructorArgs = [])
    {
        $this->db              = $db;
        $this->table           = $table;
        $this->primaryKey      = $primaryKey;
        $this->className       = $className;
        $this->constructorArgs = $constructorArgs;
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

        return $stmt->fetchObject($this->className, $this->constructorArgs);
    }

    public function find($column, $value)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' =  :value';

        $parameters = ['value' => $value];
        $stmt       = $this->query($sql, $parameters);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
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

        return $this->db->lastInsertId();
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

        return $result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
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
        $entity = new $this->className(...$this->constructorArgs);
        try {
            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }
            $insertId                    = $this->insert($record);
            $entity->{$this->primaryKey} = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }

        foreach ($record as $key => $value) {
            if (!empty($value)) {
                $entity->$key = $value;
            }
        }
        return $entity;
    }

    public function deleteWhere($column, $value)
    {
        $sql = 'DELETE FROM ' . $this->table . ' WhERE ' . $column . ' = :value';

        $parameters = [
            'value' => $value,
        ];

        $sql = $this->query($sql, $parameters);

    }
}
