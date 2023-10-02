<?php

namespace App\Core\DataBase;

use PDO;
use PDOStatement;

/**
 * @property array $where
 */
class QueryBuilder
{

    protected ?string $table = null;

    protected string $action;
    protected array $condition = [];
    protected array $bindValues = [];
    protected array $bindTypes = [];
    protected ?string $where = null;
    protected ?string $distinct = null;
    protected ?string $limit = null;
    protected ?string $offset = null;
    protected ?string $order = null;
    protected ?string $group = null;
    protected ?string $join = null;
    protected ?string $having = null;

    const DEFAULT_LIMIT = 10;

    public function __construct(protected DataBase $db)
    {
    }

    public function clone(): self
    {
        return clone $this;
    }

    public function table($table): static
    {
        $this->table = $table;

        return $this;
    }

    public function select(string $select = '*'): static
    {
        $field = $this->distinct ?? $select;
        $this->action = "SELECT " . $field;
        $this->action .= " FROM " . $this->table;

        return $this;
    }

    public function selectCount($select = '*'): static
    {
        $countBy = $this->distinct ?? $select;
        $this->action = "SELECT ";
        $this->action .= 'COUNT(' . $countBy . ') AS total_count';
        $this->action .= " FROM " . $this->table;

        $this->distinct ? $this->group = null : '';
        $this->order = null;
        $this->limit = null;
        $this->offset = null;
        unset($this->bindValues['limit']);
        unset($this->bindValues['offset']);
        unset($this->bindTypes['limit']);
        unset($this->bindTypes['offset']);

        return $this;
    }

    public function distinct($field): static
    {
        $this->distinct = 'DISTINCT ' . $field;

        return $this;
    }

    public function delete(): static
    {
        $this->action = "DELETE FROM " . $this->table;;

        return $this;
    }

    public function update(array $update = []): static
    {
        $set = "";
        foreach ($update as $column => $value) {
            $set .= "$column = :$column, ";
            $this->bindValues[$column] = $value;
            $this->bindTypes[$column] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
        }

        $set = rtrim($set, ', ');
        $this->action = "UPDATE " . $this->table . " SET " . $set;

        return $this;
    }

    public function insert(array $insert = []): static
    {
        $columns = "";
        $values = "";

        foreach ($insert as $column => $value) {
            $columns .= "$column, ";
            $values .= ":$column, ";
            $this->bindValues[$column] = $value;
            $this->bindTypes[$column] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
        }

        $columns = rtrim($columns, ', ');
        $values = rtrim($values, ', ');
        $this->action = "INSERT INTO " . $this->table . "($columns) VALUES ($values)";

        return $this;
    }

    public function whereGroup(callable $groupFunction): static
    {
        $groupFunction($this);

        if ($this->where) {
            $withoutWhere = str_replace('WHERE', '', $this->where);
            $this->where = 'WHERE (' . $withoutWhere . ')';
        }

        return $this;
    }


    public function where(string $field, string $sign, $paramName, $value): static
    {
        $this->where .= $this->where ? " AND $field $sign :$paramName" : "WHERE $field $sign :$paramName";

        $this->bindValues[$paramName] = $value;
        $this->bindTypes[$paramName] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;

        return $this;
    }

    public function whereOr(string $field, string $sign, $paramName, $value): static
    {
        $this->where .= $this->where ? " OR $field $sign :$paramName" : "WHERE $field $sign :$paramName";

        $this->bindValues[$paramName] = $value;
        $this->bindTypes[$paramName] = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;

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

    public function orderBy(string $field = 'id', string $order = 'asc'): static
    {
        if($field === 'random') {
            $this->order = 'ORDER BY RAND()';
        } else {
            $order = strtoupper($order);
            $this->order = 'ORDER BY ' . $field . ' ' . $order;
        }

        return $this;
    }

    public function offset(int $page, int $limit): static
    {
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

    public function paginate(int $limit = self::DEFAULT_LIMIT, int $page = null): array
    {
        (int)$page = $page ?? request()->get("page", 1);
        $this->limit($limit);
        $this->offset($page, $limit);
        $count = $this->clone()->selectCount()->getOne()['total_count'];

        $totalPages = $this->calculateTotalPages($count, $limit);
        $data = $this->get();

        return [
            'data' => $data,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ];
    }

    protected function calculateTotalPages(int $count, int $limit): int
    {

        return ceil($count / $limit);
    }

    public function getSQL(): string
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

        return implode(' ', array_filter($sqlParts));
    }

    private function prepareQuery(): PDOStatement
    {
        return $this
            ->db
            ->dbQuery($this->getSQL(), $this->bindValues, $this->bindTypes);

    }

    public function get($PDO = null, ?int $args = null): array
    {
        if ($PDO) {
            $data = $this->prepareQuery()->fetchAll($PDO, $args);
        } else {
            $data = $this->prepareQuery()->fetchAll();
        }

        $this->clear();

        return $data;
    }

    public function getOne()
    {
        $data = $this->prepareQuery()->fetch();

        $this->clear();

        return $data;
    }

    public function insertGetId(): ?int
    {
        $id = $this
            ->db
            ->insertAndGetId($this->getSQL(), $this->bindValues, $this->bindTypes);

        $this->clear();

        return $id;
    }

    public function clear(): static
    {
        $this->action = "SELECT * FROM " . $this->table;
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
        $this->distinct = null;

        return $this;
    }
}