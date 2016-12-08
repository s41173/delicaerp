<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assembly_product_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'assembly_product';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($pid,$assembly=null)
    {
        $this->db->select('id, project, assembly, product, qty, unit, desc');
        $this->db->from($this->table);
        $this->cek_null($assembly, 'assembly');
        $this->db->where('project', $pid);
        $this->db->order_by('assembly', 'asc');
        return $this->db->get(); 
    }


    private function cek_null($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }
    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function delete_by_project($uid)
    {
        $this->db->where('project', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function delete_by_assembly($assembly,$pid)
    {
        $this->db->where('assembly', $assembly);
        $this->db->where('project', $pid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_product_by_id($uid)
    {
       $this->db->select('id, project, assembly, product, qty, unit, desc');
       $this->db->where('id', $uid);
       return $this->db->get($this->table);
    }

    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_product($product,$assembly,$project)
    {
        $this->db->where('product', $product);
        $this->db->where('assembly', $assembly);
        $this->db->where('project', $project);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

}

?>