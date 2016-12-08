<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assembly_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'assembly';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($pid)
    {
        $this->db->select('id, no, project, image, notes, desc');
        $this->db->from($this->table);
        $this->db->where('project', $pid);
        $this->db->order_by('project', 'desc');
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
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }
    
    function get_assembly_by_id($uid)
    {
       $this->db->select('id, no, project, image, notes, desc');
       $this->db->where('id', $uid);
       return $this->db->get($this->table);
    }

    function update($uid, $users)
    {
        $this->db->where('id', $uid);
        $this->db->update($this->table, $users);
    }
    
    function valid_no($no,$pid)
    {
        $this->db->where('no', $no);
        $this->db->where('project', $pid);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }

}

?>