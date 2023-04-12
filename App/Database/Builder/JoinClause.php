<?php

namespace App\Database\Builder;

class JoinClause extends QueryBuilder
{

    private string $type;
    private string $join_table;
    private string $constraint;
    private string $constraint2;
    private string $operator;


    public function __construct(string $type, string $join_table, string $constraint, string $operator, $constraint2)
    {
        $this->type = $type;
        $this->table = $join_table;
        $this->constraint = $constraint;
        $this->constraint2 = $constraint2;
        $this->operator = $operator;
    }

    public function __toString(): string
    {
        return $this->type . ' JOIN '. $this->table
            . ' ON ' . $this->constraint . $this->operator . $this->constraint2 . ' ' ;
    }



}