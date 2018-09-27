<?php

/*
 * DB: Support to DB implementation.
 *
 * @package Avant
 */

class avdb {
    private $conn, $dns, $db, $db_type, $host, $user, $pass;

    public function __construct()
    {
        if ($this->checkDatabaseIsActive()) {
            $this->db = DB_NAME;
            $this->db_type = "mysql";
            $this->host = DB_HOST;
            $this->user = DB_USER;
            $this->pass = DB_PASS;

            $this->dns = $this->db_type . ":host=" . $this->host . ";dbname=" . $this->db;

            try {
                $this->conn = new PDO($this->dns, $this->user, $this->pass);
            } catch (PDOException $ex) {
                if (DEBUG) {
                    print_r($ex);
                }
                die("Error to connect with Database");
            }
        }
    }

    public function checkDatabaseIsActive()
    {
        if (defined('DB_NAME') && defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && (DB_NAME != '') ) {
            return true;
        } else if (DEBUG && defined('DB_NAME') && DB_NAME != '') {
            die("Database is not configured");
        }

        return false;
    }

    protected function __createTable($table, $columnsParams)
    {
        $q = "CREATE TABLE IF NOT EXISTS $table (";

        foreach ($columnsParams as $key => $columnParam) {
            if ($key > 0) {
                $q .= ", ";
            }

            $q .= $columnParam;
        }

        $q .= ")";

        $result = $this->conn->exec($q);
    }


    protected function __checkTableExists($table)
    {
        $tables = $this->conn->query("SHOW TABLES LIKE '$table'");
        if ($tables->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function __insert($table, $fields, $values)
    {
        if ($this->__checkTableExists($table)) {
            if (count($fields) == count($values)) {
                $s = 'INSERT INTO ' . $table . ' (';

                foreach ($fields as $key => $field) {
                    if ($key == 0) {
                        $s .= $field;
                    } else {
                        $s .= ", " . $field;
                    }
                }

                $s .= ') VALUES (';

                for ($i = 0; $i < count($fields) - 1; $i++) {
                    $s .= '?, ';
                }

                $s .= '?)';

                $s = $this->conn->prepare($s);

                foreach ($values as $key => $value) {
                    $s->bindValue($key + 1, $value);
                }

                return $this->executeAndReturn($s, 'insert');
            } else if(DEBUG) {
                die('__inser() error: $fields and $values must have same lenght.');
            } else {
                die();
            }
        } else if(DEBUG) {
            die('The table <code>' . $table . '</code> does not exist.');
        }
    }

    protected function __update($table, $fields, $values, $whereField, $whereValue)
    {
        if ($this->__checkTableExists($table)) {
            if (count($fields) == count($values)) {
                $s = 'UPDATE ' . $table . ' SET ';

                foreach ($fields as $key => $field) {
                    if ($key == 0) {
                        $s .= $field . ' = ?';
                    } else {
                        $s .= ", " . $field . ' = ?';
                    }
                }

                $s .= ' WHERE ' . $whereField . ' = ?';

                $s = $this->conn->prepare($s);

                foreach ($values as $key => $value) {
                    $s->bindValue($key + 1, $value);
                }

                $s->bindValue( count($values) + 1 , $whereValue);

                return $this->executeAndReturn($s, 'update');
            } else if(DEBUG) {
                die('__update() error: $fields and $values must have same lenght.');
            } else {
                die();
            }
        } else if(DEBUG) {
            die('The table <code>' . $table . '</code> does not exist.');
        }
    }

    private function __select($table, $columns = null, $where = null, $logical = 'AND')
    {
        if ($this->__checkTableExists($table)) {
            $s = 'SELECT ';

            if ($columns == null) {
                $s .= '*';
            } else {
                foreach ($columns as $key => $column) {
                    if ($key == 0) {
                        $s .= $column;
                    } else {
                        $s .= ", " . $column;
                    }
                }
            }

            $s .= ' FROM ' . $table;

            if ($where == null) {
                $s = $this->conn->prepare($s);
            } else {
                if ($logical != 'OR') {
                    $logical = 'AND';
                }

                $s .= ' WHERE ';

                $i = 0;
                foreach ($where as $field => $value) {
                    $i++;

                    if ( is_array( $value ) ) continue;

                    if ($i == 1) {
                        $s .= $field . ' = ? ';
                    } else {
                        $s .= $logical . ' ' . $field . ' = ? ';
                    }
                }

                // Array Params
                $i = 0;
                foreach ($where as $field => $value) {
                    $i++;

                    if ( ! is_array( $value ) ) continue;

                    if ($i == 1) {
                        $s .= $field . " IN ('" . implode( "','", $value ) . "') ";
                    } else {
                        $s .= $logical . " " . $field . " IN ('" . implode( "','", $value ) . "') ";
                    }
                }

                $s = $this->conn->prepare($s);

                $i = 0;
                foreach ($where as $field => $value) {
                    $i++;

                    if ( is_array( $value ) ) continue;

                    $s->bindValue($i, $value);
                }
            }

            return $this->executeAndReturn($s, 'select');

        } else if(DEBUG) {
            die('The table <code>' . $table . '</code> does not exist.');
        }
    }

    private function __delete($table, $where = null, $logical = 'AND')
    {
        if ($this->__checkTableExists($table)) {
            $s = 'DELETE FROM ' . $table;

            if ($where == null) {
                $s = $this->conn->prepare($s);
            } else {
                if ($logical != 'OR') {
                    $logical = 'AND';
                }

                $s .= ' WHERE ';

                $i = 1;
                foreach ($where as $field => $value) {
                    if ($i == 1) {
                        $s .= $field . ' = ? ';
                    } else {
                        $s .= $logical . ' ' . $field . ' = ? ';
                    }
                    $i++;
                }

                $s = $this->conn->prepare($s);

                $i = 1;
                foreach ($where as $field => $value) {
                    $s->bindValue($i, $value);
                    $i++;
                }
            }

            return $this->executeAndReturn($s, 'delete');
        } else if(DEBUG) {
            die('The table <code>' . $table . '</code> does not exist.');
        }
    }

    private function executeAndReturn($pdo, $method)
    {
        if (!$pdo->execute()) return [];

        if ($pdo->rowCount() == 0) return [];

        if ( $method == 'select' ) {
            return $pdo->fetchAll(PDO::FETCH_OBJ);
        }

        if ( $method == 'insert' ) {
            return $this->conn->lastInsertId();
        }

        return $pdo->rowCount();
    }

    public function createTable($table, $columnsParams)
    {
        return $this->__createTable($table, $columnsParams);
    }

    public function insert($table, $fields, $values)
    {
        return $this->__insert($table, $fields, $values);
    }

    public function update($table, $fields, $values, $whereField, $whereValue)
    {
        return $this->__update($table, $fields, $values, $whereField, $whereValue);
    }

    public function select($table, $columns = null, $where = null, $logical = 'AND')
    {
        return $this->__select($table, $columns, $where, $logical);
    }

    public function delete($table, $where = null, $logical = 'AND')
    {
        return $this->__delete($table, $where, $logical);
    }
}