<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cash_demand_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'cash_demand';
    
    function report($cur=null,$start=null,$end,$cat=null)
    {
        $this->db->select('id, no, dates, currency, notes, amount, approved');
        $this->db->from($this->table);
        $this->db->where('currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->where('cash_demandproved', 1);
        $this->db->order_by('dates','asc');
        return $this->db->get(); 
    }
    
    function report_category($cur=null,$start=null,$end,$cat=null)
    {
       $this->db->select('id, no, dates, currency, notes, amount, approved,
                          costs.name as cost, cash_demand_trans.notes, cash_demand_trans.amount,
                          categories.name as category, categories.id as catid,
                          accounts.code');
        
        $this->db->from('cash_demand, cash_demand_trans, costs, categories, accounts');
        $this->db->where('cash_demand.id = cash_demand_trans.cash_demand_id');
        $this->db->where('cash_demand_trans.cost = costs.id');
        $this->db->where('costs.category = categories.id');
        $this->db->where('costs.account_id = accounts.id');
        $this->cek_cat($cat, 'costs.category');
        $this->db->where('currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->group_by('categories.id');
//        $this->db->where('cash_demandproved', 1);
       
        return $this->db->get();  
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("dates BETWEEN '".$start."' AND '".$end."'"); }
    }

    private function cek_null($val,$field)
    { if (isset($val)){ return $this->db->where($field, $val); } }
    
    private function cek_cat($val,$field)
    {
        if ($val == ""){return null;}
        else {return $this->db->where($field, $val);}
    }

}

?>