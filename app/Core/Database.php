<?php

namespace App\Core;

use PDO;
use PDOException;
use ReflectionProperty;

class Database extends PDO
{
    private $select = ['*'];
    private $table = [];
    private $condition = [];
    private $cond_operators = ['=', '!=', '<>', '<', '>', '<=', '>='];
    private $order = [];
    private $order_mode = ['asc', 'desc', 'random'];
    private $join_type = ['inner', 'outer', 'left', 'right', 'cross'];
    private $wildcard_position = ['first', 'last', 'none', 'both'];
    private $join = [];
    private $group_by = [];
    private $limit = [];
    private $like = [];
    /**
     * @var string
     */
    private $group_start = [];
    /**
     * @var string
     */
    private $group_end = [];

    private $set = [];

    public function __construct($dsn, $user, $pass)
    {
        try {
            parent::__construct($dsn, $user, $pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->display_error("Connection failed: " . $e->getMessage());
        }
    }

    private function display_error($error = "An error occurred")
    {
        throw new \Exception($error);
//		echo "<h2><center>DB Error: ".$error."</center></h2>";
        exit();
    }

    public function select($columns = ''): Database
    {
        $this->select = [];
        $columns = empty($columns) ? '*' : $columns;

        $columns = explode(',', $columns);

        foreach ($columns as $column) {
            $this->select[] = $column;
        }

        return $this;
    }

    public function table($table = ''): Database
    {
        if (empty($table)) {
            $this->display_error('Table cannot be empty');
        }

        $this->table = [];
        $this->table[] = trim($table);

        return $this;
    }

    public function group_start(): Database
    {
        $this->group_start[] = count($this->condition);
        return $this;
    }

    public function group_end(): Database
    {
        $this->group_end[] = count($this->condition) - 1;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function where($cond, $value = ''): Database
    {
        if (!empty($cond)) {
            if (is_array($cond)) {

                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val, null, null, null);
                }

            } else {

                $this->whereConditionBuild($cond, $value, null, null, null);

            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function or_where($cond, $value = ''): Database
    {
        if (!empty($cond)) {
            if (is_array($cond)) {

                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val, 'OR', null, null, null);
                }

            } else {

                $this->whereConditionBuild($cond, $value, 'OR', null, null, null);

            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function like($cond, $value = '', $position = "both"): Database
    {
        if (!empty($cond)) {
            if (is_array($cond)) {

                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val, 'AND', "LIKE", 'like', $position);
                }

            } else {

                $this->whereConditionBuild($cond, $value, 'AND', "LIKE", 'like', $position);

            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function or_like($cond, $value = '', $position = "both"): Database
    {
        if (!empty($cond)) {
            if (is_array($cond)) {

                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val, 'OR', "LIKE", 'like', $position);
                }

            } else {

                $this->whereConditionBuild($cond, $value, 'OR', "LIKE", 'like', $position);

            }
        }

        return $this;
    }

    /**
     * @param $cond
     * @param $value
     * @return void
     * @throws Exception
     */
    private function whereConditionBuild($cond, $value, $type = 'AND', $operator = "=", $query = "where", $position = "")
    {
        $cond = explode(' ', $cond);

        $operator = (isset($cond[1]) && !empty($cond[1])) ?
            (in_array($cond[1], $this->cond_operators) ?
                $cond[1] : $this->display_error("Invalid condition operator")) : $operator;

        $this->condition[] = [
            'position' => $position,
            'query' => $query,
            'type' => $type ?? "AND",
            'key' => $cond[0],
            'operator' => $operator,
            'value' => $value
        ];
    }

    /**
     * @throws Exception
     */
    public function order(string $column = 'id', string $mode = ''): Database
    {
        if (!empty($column)) {

            $mode = empty($mode) ? 'desc' : $mode;

            $mode = (in_array($mode, $this->order_mode)) ? $mode : $this->display_error("Invalid order mode");

            $this->order[$column] = $mode;
        }

        return $this;
    }

    public function limit($start = '', $limit = ''): Database
    {
        if (!empty($start)) {
            if (empty($limit)) {
                $this->limit['limit'] = $start;
            } else {
                $this->limit['start'] = $start;
                $this->limit['limit'] = $limit;
            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function join($table = '', $cond = '', $type = ''): Database
    {
        if (!empty($table)) {
            $type = empty($type) ? (empty($cond) ? 'cross' : 'inner') : $type;
            $type = (in_array($type, $this->join_type)) ? $type : $this->display_error("Invalid JOIN Type");

            $this->join[] = [
                'table' => $table,
                'condition' => $cond,
                'type' => $type
            ];

        } else {
            $this->display_error("Table cannot be empty");
        }

        return $this;
    }

    public function group_by($columns = ''): Database
    {
        $columns = empty($columns) ? $this->display_error('Group by column cannot be empty') : $columns;

        $columns = explode(',', $columns);

        $this->group_by = [];

        foreach ($columns as $column) {
            $this->group_by[] = $column;
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function set($cond = '', $value = ''): Database
    {
        if (!empty($cond)) {

            if (is_array($cond)) {
                foreach ($cond as $key => $val) {
                    $this->setConditionBuild($key, $val);
                }
            } else {
                $this->setConditionBuild($cond, $value);
            }

        } else {
            $this->display_error("Set condition is required");
        }

        return $this;
    }

    private function setConditionBuild($key = '', $value = '')
    {
        $this->set[$key] = $value;
    }

    private function buildSelectQuery(): string
    {
        $sql = '';

        if (!empty($this->select)) {
            $sql .= 'SELECT ';

            $sql .= implode(', ', $this->select);
        }

        return $sql;
    }

    private function buildFromQuery(): string
    {
        $sql = '';

        if (!empty($this->table)) {
            $sql .= ' FROM ';

            $sql .= implode(', ', $this->table);
        }

        return $sql;
    }

    private function buildJoinQuery(): string
    {
        $sql = '';

        if (!empty($this->join)) {

            foreach ($this->join as $join) {

                switch ($join['type']) {
                    case 'left':
                        $sql .= " LEFT JOIN ";
                        break;
                    case 'right':
                        $sql .= " RIGHT JOIN ";
                        break;
                    case 'cross':
                        $sql .= " CROSS JOIN ";
                        break;
                    case 'outer':
                        $sql .= " FULL OUTER JOIN ";
                        break;
                    default:
                        $sql .= " INNER JOIN ";
                        break;
                }

                $sql .= $join['table'];

                if (!empty($join['condition'])) {
                    $sql .= ' ON ';

                    $sql .= $join['condition'];
                }
            }
        }

        return $sql;
    }

    private function buildWhereQuery(): string
    {
        $sql = '';

        if (!empty($this->condition)) {
            $sql .= ' WHERE ';

            foreach ($this->condition as $key => $condition) {

                $group_start = in_array($key, $this->group_start) ? '(' : '';
                $group_end = in_array($key, $this->group_end) ? ')' : '';

                $sql .= (($key > 0) ? ' ' . $condition['type'] . ' ' : '')
                    . $group_start . $condition['key'] . ' '
                    . (!empty($condition['operator']) ? $condition['operator'] : "=")
                    . ' :' . $condition['key'] . $group_end;

            }
        }

        return $sql;
    }

    private function buildOrderByQuery(): string
    {
        $sql = '';

        if (!empty($this->order)) {
            $sql .= ' ORDER BY ';

            foreach ($this->order as $key => $value) {
                $keys = array_keys($this->order);

                if ($value === 'random') {
                    $sql .= ' RAND(' . $key . ') '
                        . (end($keys) === $key ? '' : ', ');
                } else {
                    $sql .= $key . ' ' . $value
                        . (end($keys) === $key ? '' : ', ');
                }

            }
        }

        return $sql;
    }

    private function buildGroupByQuery(): string
    {
        $sql = '';

        if (!empty($this->group_by)) {
            $sql .= 'GROUP BY ';

            $sql .= implode(', ', $this->group_by);
        }

        return $sql;
    }

    private function buildLimitQuery(): string
    {
        $sql = '';

        if (!empty($this->limit)) {
            $sql .= ' LIMIT ';

            if (empty($this->limit['start'])) {
                $sql .= $this->limit['limit'];
            } else {
                $sql .= $this->limit['start'] . ',' . $this->limit['limit'];
            }
        }

        return $sql;
    }

    public function buildFetchQuery($table = '', $start = '', $limit = ''): string
    {
        if (!empty($table)) {
            $this->table = [];
            $this->table[] = trim($table);
        }

        if (!empty($start)) {
            if (empty($limit)) {
                $this->limit['limit'] = $start;
            } else {
                $this->limit['start'] = $start;
                $this->limit['limit'] = $limit;
            }
        }

        $sql = $this->buildSelectQuery();

        $sql .= $this->buildFromQuery();

        $sql .= $this->buildJoinQuery();

        $sql .= $this->buildWhereQuery();

        $sql .= $this->buildOrderByQuery();

        $sql .= $this->buildGroupByQuery();

        $sql .= $this->buildLimitQuery();

//        $sql .= $this->buildLikeQuery();

        return $sql;
    }

    public function buildInsertQuery($table = '', $data = []): string
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->setConditionBuild($key, $val);
            }
        }

        $sql = 'INSERT INTO ' . $table . '('
            . (!empty($this->set) ? implode(",", array_keys($this->set)) : '') .
            ') VALUES('
            . (!empty($this->set) ? ":" . implode(", :", array_keys($this->set)) : '') .
            ')';

        return $sql;
    }

    /**
     * @throws Exception
     */
    public function buildUpdateQuery($table = '', $data = [], $cond = []): string
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->setConditionBuild($key, $val);
            }
        }

        if (is_array($cond)) {
            foreach ($cond as $key => $val) {
                $this->whereConditionBuild($key, $val);
            }
        }

        $set = null;
        $sql = "UPDATE " . $table;

        foreach ($this->set as $key => $val) {
            $set .= "$key=:$key,";
        }

        $sql .= " SET " . rtrim($set, ",");

        $sql .= $this->buildWhereQuery();

        return $sql;
    }

    /**
     * @throws Exception
     */
    public function buildDeleteQuery($table = '', $cond = []): string
    {
        if (!empty($cond)) {
            if (is_array($cond)) {
                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val);
                }
            }
        }

        $sql = "DELETE FROM " . $table;

        $sql .= $this->buildWhereQuery();

        return $sql;
    }

    private function resetQuery()
    {
        $this->select = ['*'];
        $this->table = [];
        $this->condition = [];
        $this->order = [];
        $this->join = [];
        $this->group_by = [];
        $this->group_start = [];
        $this->group_end = [];
        $this->limit = [];
        $this->set = [];
    }


    public function execute($table = '', $start = '', $limit = '')
    {
        if (!empty($table)) {
            $this->table = [];
            $this->table[] = trim($table);
        }

        if (!empty($start)) {
            if (empty($limit)) {
                $this->limit['limit'] = $start;
            } else {
                $this->limit['start'] = $start;
                $this->limit['limit'] = $limit;
            }
        }

        try {
            $stmt = $this->prepare($this->buildFetchQuery());

            if (!empty($this->condition)) {
                foreach ($this->condition as $condition) {

                    $value = $condition['value'];

                    if (!empty($condition['query']) && $condition['query'] === "like") {
                        $position = (in_array($condition['position'], $this->wildcard_position) ?
                            $condition['position'] : $this->display_error("Invalid wildcard position"));

                        switch ($position) {
                            case 'first':
                                $value = '%' . $value;
                                break;
                            case 'last':
                                $value = $value . '%';
                                break;
                            case 'none':
                                break;
                            default:
                                $value = '%' . $value . '%';
                                break;
                        }
                    }
                    $stmt->bindValue(':' . $condition['key'], $value);
                }
            }

            $this->resetQuery();
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $db_result = new DB_Result();
            $reflector = new ReflectionProperty($db_result, 'result');
            $reflector->setAccessible(true);

            $reflector->setValue($db_result, [
                'object' => (object)$result,
                'array' => $result,
                'count' => $stmt->rowCount()
            ]);

            return $db_result;

        } catch (Exception $e) {
            $this->display_error("Query error: " . $e->getMessage());
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function insert($table = '', $data = []): bool
    {
        $table = !empty($table) ? $table : $this->display_error("Table cannot be empty");
        empty($this->set) && empty($data) ? $this->display_error("Set cannot be empty") : '';

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->setConditionBuild($key, $val);
            }
        }

        $stmt = $this->prepare($this->buildInsertQuery($table));

        foreach ($this->set as $key => $val) {
            $stmt->bindValue(":$key", $val);
        }

        $this->resetQuery();
        return $stmt->execute();
    }

    public function insert_id()
    {
        $last_id = $this->lastInsertId();

        if (!empty($last_id) && $last_id > 0) {
            return $last_id;
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function update($table = '', $data = [], $cond = []): bool
    {
        $table = !empty($table) ? $table : $this->display_error("Table cannot be empty");
        empty($this->set) ? $this->display_error("Set cannot be empty") : '';

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->setConditionBuild($key, $val);
            }
        }

        if (is_array($cond)) {
            foreach ($cond as $key => $val) {
                $this->whereConditionBuild($key, $val);
            }
        }

        $stmt = $this->prepare($this->buildUpdateQuery($table));

        if (!empty($this->condition)) {
            foreach ($this->condition as $condition) {
                $stmt->bindValue(':' . $condition['key'], $condition['value']);
            }
        }

        if (!empty($this->set)) {
            foreach ($this->set as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
        }

        $this->resetQuery();
        return $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public function delete($table = '', $cond = []): bool
    {
        $table = !empty($table) ? $table : $this->display_error("Table cannot be empty");

        if (!empty($cond)) {
            if (is_array($cond)) {
                foreach ($cond as $key => $val) {
                    $this->whereConditionBuild($key, $val);
                }
            }
        }

        $stmt = $this->prepare($this->buildDeleteQuery($table));

        if (!empty($this->condition)) {
            foreach ($this->condition as $condition) {
                $stmt->bindValue(':' . $condition['key'], $condition['value']);
            }
        }

        $this->resetQuery();
        return $stmt->execute();
    }

    private function print_debug($debug)
    {
        echo "<pre>";
        var_dump($debug);
        exit();
    }
}

class DB_Result
{

    protected $result = [];

    public function count()
    {
        return $this->result['count'] ?? 0;
    }

    public function fetch()
    {
        return $this->result['object'] ?? [];
    }

    public function fetch_array()
    {
        return $this->result['array'] ?? [];
    }
}




