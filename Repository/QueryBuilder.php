<?php

class QueryBuilder
{
    private $fields = [];
    private $condition = [];
    private $from = [];

    public function createConn()
    {
        try {
            return new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        }
        catch (PDOException $e){
            print "Can't connect: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function select(string ...$select): self
    {
        $this->fields = $select;
        return $this;
    }

    public function where(string ...$where): self
    {
        foreach($where as $arg)
            $this->condition = $arg;
        return $this;
    }

    public function from(string $table, ?string $alias = null): self
    {
        if($alias == null)
            $this->from[] = $table;
        else
            $this->from[] = "${table} AS ${alias}";
        return $this;
    }

    public function __toString(): string
    {
        $where = $this->condition === [] ? '' : ' WHERE ' . $this->condition;
        return 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . $where;
    }

}