<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demand_item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'demand_item';
    
    function get_last_item($po)
    {
        $this->db->select('id, demand, product, qty, desc, demand_date, user, vendor');
        $this->db->from($this->table);
        $this->db->where('demand', $po);
        $this->db->order_by('id', 'asc'); 
        return $this->db->get(); 
    }

    function total($po)
    {
        $this->db->select_sum('tax');
        $this->db->select_sum('amount');
        $this->db->where('demand', $po);
        return $this->db->get($this->table)->row_array();
    }

    
    function delete($uid)
    {
        $this->db->where('id', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }

    function delete_po($uid)
    {
        $this->db->where('demand', $uid);
        $this->db->delete($this->table); // perintah untuk delete data dari db
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

    function report($po)
    {
        $this->db->select("$this->table.id, $this->table.demand, product.name as product, product.unit, $this->table.qty, 
                           $this->table.desc, $this->table.demand_date, $this->table.vendor, $this->table.user");
        $this->db->from("$this->table,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.demand", $po);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    
    function report_category($start,$end)
    {
        $this->db->select("$this->table.id, $this->table.demand, product.name as product, product.id as pid, product.unit, $this->table.qty, 
                           $this->table.desc, $this->table.demand_date, $this->table.vendor, $this->table.user,
                           demand.no, demand.dates 
                          ");
        $this->db->from("$this->table,demand,product");
        $this->db->where("$this->table.product = product.id");
        $this->db->where("$this->table.demand = demand.no");
        $this->db->where("demand.dates BETWEEN '".setnull($start)."' AND '".setnull($end)."'");
        $this->db->where("demand.approved", 1);
        $this->db->order_by("$this->table.id", 'asc');
        return $this->db->get();
    }
    

}

?>