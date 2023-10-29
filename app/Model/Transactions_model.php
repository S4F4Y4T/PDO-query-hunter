<?php

namespace App\Model;

use App\Core\Main_model;

class Transactions_model extends Main_model{

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch()
    {
        $this->db->select('*');
        $this->db->table('transactions');
        $this->db->group_start();
        $this->db->where('id', 1);
        $this->db->group_end();
        $this->db->limit(1);
        $result = $this->db->execute();

        return [
            'obj' => $result->fetch(),
            'arr' => $result->fetch_array(),
            'count' => $result->count()
        ];

//        return $result->fetch_array();

    }
}