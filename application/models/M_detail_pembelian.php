<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_detail_pembelian extends CI_Model
{

    var $table           = 'tbl_detail_pembelian';
    var $column_order    =  array(null, 'id_pembelian', 'id_barang', 'noball', null, null); //set column field database untuk datatable order
    var $column_search   =  array('id_pembelian', 'id_barang', 'noball'); //set column field database untuk datatable search
    var $order = array('id_pembelian' => 'asc'); // default order

    function __construct()
    {
        parent::__construct();
    }

    public function get_row($id){
        
        $this->db->from('tbl_detail_pembelian');
        $this->db->where(['id'=>$id]);

        return $this->db->get()->row_array();
    }


    function getData($table = null)
    {
        // $this->db->distinct();
        // $this->db->select('noball');
        $this->db->where('noball is not null');
        $this->db->or_where('noball ','<>','');
        $this->db->from($table);

        return $this->db->get();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // Jika datatable mengirim POST untuk search
            {
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket.

                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) // Proses order
        {

            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {

            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();

            return $query->result();
        }
    }


    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    function count_all()
    {
        $this->db->from($this->table);

        return $this->db->count_all_results();
    }

    function update($table = null, $data = null, $where = null)
    {
        return $this->db->update($table, $data, $where);
    }
}
