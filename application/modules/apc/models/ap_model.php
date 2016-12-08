<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = null;
    
    function get_list($cur=null,$vendor=null)
    {
        $this->db->select('ap.id, ap.no, ap.vendor, vendor.prefix, vendor.name, ap.dates, ap.currency, ap.notes, ap.acc, ap.amount, ap.approved');
        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->where('ap.vendor', $vendor);
        $this->db->where('ap.currency', $cur);
        $this->db->where('ap.approved', 1);
        $this->db->where('ap.status', 0);
        $this->db->order_by('ap.dates','asc');
        return $this->db->get(); 
    }
    
    function report($vendor=null,$cur=null,$start=null,$end,$cat=null,$acc=null)
    {
        $this->db->select('ap.id, ap.no, ap.vendor, vendor.prefix, vendor.name, ap.dates, ap.currency, ap.notes, ap.acc, ap.amount, ap.approved');
        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null($vendor, 'vendor.name');
        $this->cek_cat($acc, 'ap.acc');
        $this->db->where('ap.currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->where('ap.approved', 1);
        $this->db->order_by('ap.dates','asc');
        return $this->db->get(); 
    }
    
    function report_category($vendor=null,$cur=null,$start=null,$end,$cat=null,$acc)
    {
       $this->db->select('ap.id, ap.no, vendor.name as vendor, ap.dates, ap.currency, ap.acc, ap.approved,
                          costs.name as cost, ap_trans.notes, ap_trans.staff, ap_trans.amount,
                          categories.name as category, categories.id as catid,
                          accounts.code');
        
        $this->db->from('ap, vendor, ap_trans, costs, categories, accounts');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->where('ap.id = ap_trans.ap_id');
        $this->db->where('ap_trans.cost = costs.id');
        $this->db->where('costs.category = categories.id');
        $this->db->where('costs.account_id = accounts.id');
        $this->cek_null($vendor, 'vendor.name');
        $this->cek_cat($acc, 'ap.acc');
        $this->cek_cat($cat, 'costs.category');
        $this->db->where('ap.currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->group_by('categories.id');
//        $this->db->where('ap.approved', 1);
       
        return $this->db->get();  
    }
    
    function total_chart($month,$year,$cur='IDR')
    {
        $this->db->select_sum('amount');

        $this->db->from('ap');
        $this->cek_null($cur,"currency");
        $this->db->where('approved', 1);
        $this->cek_null($month,"MONTH(dates)");
        $this->cek_null($year,"YEAR(dates)");
        $query = $this->db->get()->row_array();
        return $query['amount'];
    }
    
    private function cek_between($start,$end)
    {
        if ($start == null || $end == null ){return null;}
        else { return $this->db->where("ap.dates BETWEEN '".$start."' AND '".$end."'"); }
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