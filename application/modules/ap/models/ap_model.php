<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ap_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    var $table = 'ap';
    
    function count_all_num_rows()
    {
        //method untuk mengembalikan nilai jumlah baris dari database.
        return $this->db->count_all($this->table);
    }
    
    function get_last($limit, $offset)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.vendor, ap.currency, ap.dates, ap.acc, ap.user, ap.status,
                           ap.amount, ap.notes, ap.approved');
        
        $this->db->from('ap');
        $this->db->order_by('ap.id', 'desc');
        $this->db->limit($limit, $offset);
        return $this->db->get(); 
    }
    
    function search($no,$vendor,$date)
    {
        $this->db->select('ap.id, ap.no, ap.docno, ap.vendor, ap.currency, ap.dates, ap.acc, ap.user, ap.status,
                           ap.amount, ap.notes, ap.approved');

        $this->db->from('ap,vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null($no,"ap.no");
        $this->cek_null($vendor,"vendor.name");
        $this->cek_null($date,"ap.dates");
        return $this->db->get();
    }
    
    function counter()
    {
        $this->db->select_max('no');
        $test = $this->db->get($this->table)->row_array();
        $userid=$test['no'];
	$userid = $userid+1;
	return $userid;
    }
    
    function report($vendor=null,$cur=null,$start=null,$end,$cat=null,$acc=null)
    {
        $this->db->select('ap.id, ap.no, ap.vendor, vendor.prefix, vendor.name, ap.dates, ap.currency, ap.notes, 
                           ap.acc, ap.amount, ap.post_dated, ap.post_dated_stts, ap.approved');
        $this->db->from('ap, vendor');
        $this->db->where('ap.vendor = vendor.id');
        $this->cek_null_report($vendor, 'vendor.name');
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
                          costs.name as cost, costs.account_id as account, ap_trans.notes, ap.post_dated, ap.post_dated_stts,
                          ap_trans.staff, ap_trans.amount,
                          categories.name as category, categories.id as catid');
        
        $this->db->from('ap, vendor, ap_trans, costs, categories');
        $this->db->where('ap.vendor = vendor.id');
        $this->db->where('ap.id = ap_trans.ap_id');
        $this->db->where('ap_trans.cost = costs.id');
        $this->db->where('costs.category = categories.id');
        $this->cek_null_report($vendor, 'vendor.name');
        $this->cek_cat($acc, 'ap.acc');
        $this->cek_cat($cat, 'costs.category');
        $this->db->where('ap.currency', $cur);
        $this->cek_between($start, $end);
//        $this->db->group_by('categories.id');
//        $this->db->where('ap.approved', 1);
       
        return $this->db->get();  
    }
    
    private function cek_null_report($val,$field)
    { if ($val != ""){ return $this->db->where($field, $val); } }
    
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
    
    function valid_no($no)
    {
        $this->db->where('no', $no);
        $query = $this->db->get($this->table)->num_rows();
        if($query > 0) { return FALSE; } else { return TRUE; }
    }
    
    function add($users)
    {
        $this->db->insert($this->table, $users);
    }

}

?>