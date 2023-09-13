<?php

namespace App\Core\DataBase;

use PDO;
use PDOStatement;

/**
 * @property array $where
 */
class QueryBuilder
{

    protected static $table;

    protected string $action;
    protected array $condition = [];
    protected array $bindValues = [];
    protected array $bindTypes = [];
    protected ?string $where = null;
    protected ?string $limit = null;
    protected ?string $offset = null;
    protected ?string $order = null;
    protected ?string $group = null;
    protected ?string $join = null;
    protected ?string $having = null;

    public function __construct(protected DataBase $db)
    {
        $this->action = "SELECT * FROM " . static::$table;
    }

    public static function table($table): static
    {
        $instance = new static(new DataBase());
        static::$table = $table;
        return $instance;
    }

    public function select($select = '*'): static
    {
        $this->action = "SELECT ";
        $this->action .= is_array($select) ? implode(',', $select) : $select;
        $this->action .= " FROM " . static::$table;
        return $this;
    }

    public function selectWithConcatenation($select = '*', $concat, $fieldMask, $separator = ' '): static
    {
        $this->action = "SELECT ";
        $this->action .= is_array($select) ? implode(',', $select) : $select;
        $this->action .= ', GROUP_CONCAT(' . $concat . ' SEPARATOR "' . $separator . '") AS ' . $fieldMask;
        $this->action .= " FROM " . static::$table;
        return $this;
    }

    public function selectCount($field): static
    {
        $countBy = $field ?? static::$table;
        $this->action = "SELECT ";
        $this->action .= 'COUNT(DISTINCT ' . $countBy . ') AS total_count';
        $this->action .= " FROM " . static::$table;
        return $this;
    }

    public function delete(): static
    {
        $this->action = "DELETE ";
        return $this;
    }

    public function update(array $update = []): static
    {
        $set = "";
        foreach ($update as $column => $value) {
            $set .= "$column = $value, ";
            $this->bindValues[$column] = $value;
            $this->bindTypes[$column] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
        }


        $this->action = "UPDATE " . static::$table . "SET " . $set;
        return $this;
    }

    public function insert(array $insert = []): static
    {
        $columns = "";
        $values = "";
        foreach ($insert as $column => $value) {
            $columns .= "$column, ";
            $values .= ":$value, ";
            $this->bindValues[$column] = $value;
            $this->bindTypes[$column] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
        }
        $this->action = "INSERT INTO " . static::$table . "($columns) VALUES ($values)";
        return $this;
    }

    public function where(string $field, string $sign, $paramName, $value): static
    {
        if(!$this->where) {
            $this->where = "WHERE $field$sign:$paramName";
        } else {
            $this->where .= " AND $field$sign:$paramName";
        }
        $this->bindValues[$paramName] = $value;

        return $this;
    }

    public function whereIn(string $field, $mask, array $values): static
    {
        $placeholders = [];
        foreach ($values as $index => $value) {
            $paramName = $mask . $index;
            $placeholders[] = ':' . $paramName;
            $this->bindValues[$paramName] = $value;
            $this->bindTypes[$paramName] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
        }
        $placeholdersStr = implode(", ", $placeholders);

        $condition = "$field IN ($placeholdersStr)";
        $this->where = $this->where ? $this->where . " AND " . $condition : "WHERE " . $condition;

        return $this;
    }


    public function andWhere(string $field, string $sign, $value): static
    {
        $param = ":" . preg_replace("/[^a-zA-Z0-9_]/", "", $field);
        $this->where .= $this->where ? " AND $field $sign $param" : "WHERE $field $sign $param";
        $this->bindValues[$param] = $value;
        return $this;
    }

    public function orWhere(string $field, string $sign, $value): static
    {
        $param = ":" . preg_replace("/[^a-zA-Z0-9_]/", "", $field);
        $this->where .= $this->where ? " OR $field $sign $param" : "WHERE $field $sign $param";
        $this->bindValues[$param] = $value;
        return $this;
    }

    public function join(string $type, string $table, string $onCondition): static
    {
        $this->join .= " $type JOIN $table ON $onCondition";
        return $this;
    }

    public function innerJoin(string $table, string $onCondition): static
    {
        return $this->join("INNER", $table, $onCondition);
    }

    public function leftJoin(string $table, string $onCondition): static
    {
        return $this->join("LEFT", $table, $onCondition);
    }

    public function groupBy($groupBy): static
    {
        $this->group = 'GROUP BY ' . $groupBy;
        return $this;
    }

    public function having(string $field, string $sign, $value): static
    {
        $param = ":" . preg_replace("/[^a-zA-Z0-9_]/", "", $field) . "_having";
        $this->having = "HAVING $field $sign $param";
        $this->bindValues[$param] = $value;
        return $this;
    }

    public function andHaving(string $field, string $sign, $value): static
    {
        $param = ":" . preg_replace("/[^a-zA-Z0-9_]/", "", $field) . "_having";
        $this->having .= $this->having ? " AND $field $sign $param" : "HAVING $field $sign $param";
        $this->bindValues[$param] = $value;
        return $this;
    }

    public function orHaving(string $field, string $sign, $value): static
    {
        $param = ":" . preg_replace("/[^a-zA-Z0-9_]/", "", $field) . "_having";
        $this->having .= $this->having ? " OR $field $sign $param" : "HAVING $field $sign $param";
        $this->bindValues[$param] = $value;
        return $this;
    }

    public function orderBy(string $field, string $order = 'asc'): static
    {
        $order = strtoupper($order);
        $this->order = 'ORDER BY ' . $field . ' ' . $order;
        return $this;
    }

    public function offset(int $page): static
    {
        $limit = (int)$this->limit;
        $offset = ($page - 1) * $limit;
        $this->offset = 'OFFSET :offset';
        $this->bindValues['offset'] = $offset;
        $this->bindTypes['offset'] = PDO::PARAM_INT;
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = 'LIMIT :limit';
        $this->bindValues['limit'] = $limit;
        $this->bindTypes['limit'] = PDO::PARAM_INT;
        return $this;
    }

    public function get(): PDOStatement
    {
        $sqlParts = [
            $this->action,
            $this->join,
            $this->where,
            $this->group,
            $this->having,
            $this->order,
            $this->limit,
            $this->offset,
        ];

        $sql = implode(' ', array_filter($sqlParts));

        $query = $this->db->dbQuery($sql, $this->bindValues, $this->bindTypes);

        $this->clear();
        return $query;
    }

    public function clear(): static
    {
        $this->action = "SELECT * FROM " . static::$table;
        $this->condition = [];
        $this->bindValues = [];
        $this->bindTypes = [];
        $this->where = null;
        $this->limit = null;
        $this->offset = null;
        $this->order = null;
        $this->group = null;
        $this->join = null;
        $this->having = null;

        return $this;
    }
}