<?php
require_once(__DIR__ . "/../utils/_init.php");
class Model
{
    protected \PDO $db;
    public function __construct()
    {
        global $db;
        $this->db = $db;
    }
    protected function get(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column = :value");
        $stmt->execute([
            ":value" => $value
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    protected function set(string $table, string $column, string $value, string $id)
    {
        $stmt = $this->db->prepare("UPDATE $table SET $column = :value WHERE id = :id");
        $stmt->execute([
            ":value" => $value,
            ":id" => $id
        ]);
    }
    protected function create(string $table, array $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $stmt = $this->db->prepare("INSERT INTO $table (" . implode(", ", $keys) . ") VALUES (" . implode(", ", array_fill(0, count($keys), "?")) . ")");
        if($stmt->execute($values))
            return $this->db->lastInsertId();
        return false;
    }
    protected function read(string $table, array $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE " . implode(" = ? AND ", $keys) . " = ?");
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function updateWhere(string $table, array $data, string $column, string $value)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $stmt = $this->db->prepare("UPDATE $table SET " . implode(", ", array_map(function ($key) {
            return "$key = ?";
        }, $keys)) . " WHERE $column = ? ");
        return $stmt->execute(array_merge($values, [$value]));
    }
    protected function deleteWhere(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE $column = :value");
        return $stmt->execute([
            ":value" => $value
        ]);
    }
    protected function search(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column LIKE :value");
        $stmt->execute([
            ":value" => "%$value%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private function _cond(string $cond)
    {
        $conds = [
            'ne' => function (string $key) {
                return "$key != ?";
            },
            'eq' => function (string $key) {
                return "$key = ?";
            },
            'contains' => function (string $key) {
                return "$key LIKE ?";
            },
            'not_contains' => function (string $key) {
                return "$key NOT LIKE ?";
            },
            '!contains' => function (string $key) {
                return "$key NOT LIKE ?";
            },
            'lt' => function (string $key) {
                return "$key < ?";
            },
            'gt' => function (string $key) {
                return "$key > ?";
            },
            'lte' => function (string $key) {
                return "$key <= ?";
            },
            'gte' => function (string $key) {
                return "$key >= ?";
            },
            'r' => function (string $key) {
                return "$key BETWEEN ? AND ?";
            },
            'in' => function (string $key) {
                return "$key IN (?)";
            },
            'like' => function (string $key) {
                return "$key LIKE ?";
            },
            'not_between' => function (string $key) {
                return "$key NOT BETWEEN ?";
            },
            'not_in' => function (string $key) {
                return "$key NOT IN (?)";
            },
            'not_like' => function (string $key) {
                return "$key NOT LIKE ?";
            },
            'is_null' => function (string $key) {
                return "$key IS NULL";
            },
            'is_not_null' => function (string $key) {
                return "$key IS NOT NULL";
            },
            'is_empty' => function (string $key) {
                return "$key = ''";
            },
            'is_not_empty' => function (string $key) {
                return "$key != ''";
            },
            'is_true' => function (string $key) {
                return "$key = 1";
            },
            'is_false' => function (string $key) {
                return "$key = 0";
            },
        ];
        if (isset($conds[$cond])) {
            return $conds[$cond];
        }
        return $conds['eq'];
    }
    private function _data(string $cond, mixed $data)
    {
        $conds = [
            'contains' => function (mixed $data) {
                return "%$data%";
            },
            'not_contains' => function (mixed $data) {
                return "%$data%";
            },
            '!contains' => function (mixed $data) {
                return "%$data%";
            },
            'r' => function (mixed $data) {
                return [$data[0], $data[1]];
            },
        ];
        if (isset($conds[$cond])) {
            return $conds[$cond]($data);
        }
        if (is_array($data)) {
            return implode(" ", $data);
        }
        return $data;
    }
    protected function _parse(array $data)
    {
        $sql = [];
        $_data = [];
        foreach ($data as $key => $value) {
            $d = explode("?", $key);
            if (count($d) > 1) {
                $k = $d[0];
                $c = $d[1];
                $sql[] = $this->_cond($c)($k, $value);
                $d = $this->_data($c, $value);
                if (is_array($d)) {
                    $_data = array_merge($_data, $d);
                } else {
                    $_data[] = $d;
                }
            } else {
                if(is_numeric($key)) {
                    $d = $this->_parse($value);
                    $sql[] = $d['sql'];
                    $_data = array_merge($_data, $d['data']);
                }else{
                    $sql[] = $this->_cond('eq')($key, $value);
                    $_data[] = $value;
                }
            }
        }
        if (array_keys($data) !== range(0, count($data) - 1)){
            $_sql = implode(" AND ", $sql);
        } else {
            if(count($sql) > 1) {
                    $_sql = "( " . implode(" OR ", $sql) . " )";
            }else{
                $_sql = $sql[0];
            }
        }
        return [
            'sql' => $_sql,
            'data' => $_data
        ];
    }
    protected function parseWhere(array $data)
    {
        $sql = [];
        $_data = [];
        foreach ($data as $key => $value) {
            $d = explode("?", $key);
            if (count($d) > 1) {
                $k = $d[0];
                $c = $d[1];
                $sql[] = $this->_cond($c)($k, $value);
                $d = $this->_data($c, $value);
                if (is_array($d)) {
                    $_data = array_merge($_data, $d);
                } else {
                    $_data[] = $d;
                }
            } else {
                if(is_numeric($key)) {
                    $d = $this->parseWhere($value);
                    $sql[] = $d['sql'];
                    $_data = array_merge($_data, $d['data']);
                }else{
                    $sql[] = $this->_cond('eq')($key, $value);
                    $_data[] = $value;
                }
            }
        }
        if (array_keys($data) !== range(0, count($data) - 1)){
            $_sql = implode(" AND ", $sql);
        } else {
            if(count($sql) > 1) {
                    $_sql = "( " . implode(" OR ", $sql) . " )";
            }else{
                $_sql = $sql[0];
            }
        }
        return [
            'sql' => $_sql,
            'data' => $_data
        ];
    }
    protected function _fetch(string $table, array $data, string $select, string $order = null, string $asc = null, int $limit = null, int $offset = null)
    {
        $_data = [];
        $_d = $this->_parse($data);
        $sql = $_d['sql'];
        $_data = $_d['data'];
        if(empty($sql)) {
            $sql = "1";
        }
        $sql = "SELECT $select FROM `$table` WHERE " . $sql;
        
        if ($offset) {
            $sql .= " AND id < ?";
            $_data[] = $offset;
        }
        if ($order) {
            $sql .= " ORDER BY $order";
            if ($asc) {
                $sql .= " $asc";
            }
        }
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        if(count($_data) > 0) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($_data);
        }else{
            $stmt = $this->db->query($sql);
        }
        // $stmt = $this->db->prepare("SELECT * FROM $table WHERE " . $sql);
        // $stmt->execute($_data);
        return $stmt;
    }
    protected function fetch(string $table, array $data)
    {
        $stmt = $this->_fetch($table, $data, '*');
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    protected function fetchColumn(string $table, array $data, string $column, string $order = null, string $asc = null)
    {
        $stmt = $this->_fetch($table, $data, $column, $order, $asc);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function fetchAll(string $table, array $data, string $order = null, string $asc = null, int $limit = null, int $offset = null)
    {
        $stmt = $this->_fetch($table, $data, '*', $order, $asc, $limit, $offset);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getLast(string $table, array $data)
    {
        $stmt = $this->_fetch($table, $data, '*', 'id', 'DESC', 1);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    protected function countRows(string $table, array $data)
    {
        $stmt = $this->_fetch($table, $data, 'COUNT(*) AS count');
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    protected function query(string $sql, array $data)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAll(string $table)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhere(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column = :value");
        $stmt->execute([
            ":value" => $value
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereIn(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column IN (" . implode(", ", array_fill(0, count($values), "?")) . ")");
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotIn(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column NOT IN (" . implode(", ", array_fill(0, count($values), "?")) . ")");
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereLike(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column LIKE :value");
        $stmt->execute([
            ":value" => "%$value%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotLike(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column NOT LIKE :value");
        $stmt->execute([
            ":value" => "%$value%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereBetween(string $table, string $column, string $value1, string $value2)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column BETWEEN :value1 AND :value2");
        $stmt->execute([
            ":value1" => $value1,
            ":value2" => $value2
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotBetween(string $table, string $column, string $value1, string $value2)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column NOT BETWEEN :value1 AND :value2");
        $stmt->execute([
            ":value1" => $value1,
            ":value2" => $value2
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNull(string $table, string $column)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotNull(string $table, string $column)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column IS NOT NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereLikeAll(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column LIKE '%" . implode("%' AND $column LIKE '%", $values) . "%'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotLikeAll(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column NOT LIKE '%" . implode("%' AND $column NOT LIKE '%", $values) . "%'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereLikeAny(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column LIKE '%" . implode("%' OR $column LIKE '%", $values) . "%'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereNotLikeAny(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column NOT LIKE '%" . implode("%' OR $column NOT LIKE '%", $values) . "%'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getAllWhereLikeAllAnd(string $table, array $columns, array $values)
    {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE " . implode(" AND ", array_map(function ($column, $value) {
            return "$column LIKE '%$value%'";
        }, $columns, $values)));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function countWhere(string $table, string $column, string $value)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
        $stmt->execute([
            ":value" => $value
        ]);
        return $stmt->fetchColumn();
    }
    protected function countAll(string $table)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    protected function countWhereIn(string $table, string $column, array $values)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $column IN (" . implode(", ", array_fill(0, count($values), "?")) . ")");
        $stmt->execute($values);
        return $stmt->fetchColumn();
    }
}
