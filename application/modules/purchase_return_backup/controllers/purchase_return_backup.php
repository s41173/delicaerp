<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_return_backup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Purchase_return_backup_model', '', TRUE);

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->load->library('unit');
        $this->load->library('currency');
        
        $this->vendor = $this->load->library('vendor');
        $this->user = $this->load->library('admin');
        $this->tax = $this->load->library('tax');
        $this->journal = $this->load->library('journal');
        $this->product = $this->load->library('products');
        $this->purchase = $this->load->library('purchase');
        $this->pitem = $this->load->library('purchase_item');
        $this->wt = $this->load->library('warehouse_transaction');

    }

    private $properti, $modul, $title;
    private $vendor,$user,$tax,$journal,$product,$purchase,$pitem,$wt;

    private $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
        $this->get_last_purchase_return();
    }

    function get_last_purchase_return()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_return_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('main/','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $purchase_returns = $this->Purchase_return_backup_model->get_last_purchase_return($this->modul['limit'], $offset)->result();
        $num_rows = $this->Purchase_return_backup_model->count_all_num_rows();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last_purchase_return');
            $config['total_rows'] = $num_rows;
            $config['per_page'] = $this->modul['limit'];
            $config['uri_segment'] = $uri_segment;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links(); //array menampilkan link untuk pagination.
            // akhir dari config untuk pagination
            

            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Code', 'Purchase', 'Date', 'Vendor', 'Notes', 'Total', 'Balance');

            $i = 0 + $offset;
            foreach ($purchase_returns as $purchase_return)
            {
                $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase_return->id,'checked'=> FALSE, 'style'=> 'margin:0px');
                
                $this->table->add_row
                (
                    ++$i, 'PR-00'.$purchase_return->no, 'PO-00'.$purchase_return->purchase, tgleng($purchase_return->dates), $purchase_return->prefix.' '.$purchase_return->name, $purchase_return->notes, number_format($purchase_return->total + $purchase_return->costs), number_format($purchase_return->balance)
                );
            }

            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }

        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }

    function search()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Find '.ucwords($this->modul['title']);
        $data['h2title'] = 'Find '.$this->modul['title'];
        $data['main_view'] = 'purchase_return_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('purchase_return_backup/','<span>back</span>', array('class' => 'back')));

        $purchase_returns = $this->Purchase_return_backup_model->search($this->input->post('tno'), $this->input->post('tpo'), $this->input->post('tcust'), $this->input->post('tstart'), $this->input->post('tend'))->result();
        
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Purchase', 'Date', 'Vendor', 'Notes', 'Total', 'Balance');

        $i = 0;
        foreach ($purchase_returns as $purchase_return)
        {
            $datax = array('name'=> 'cek[]','id'=> 'cek'.$i,'value'=> $purchase_return->id,'checked'=> FALSE, 'style'=> 'margin:0px');

            $this->table->add_row
            (
                ++$i, 'PR-00'.$purchase_return->no, 'PO-00'.$purchase_return->purchase, tgleng($purchase_return->dates), $purchase_return->prefix.' '.$purchase_return->name, $purchase_return->notes, number_format($purchase_return->total + $purchase_return->costs), number_format($purchase_return->balance)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('template', $data);
    }

    function get_list($currency=null,$vendor=null)
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'vendor_list';

        $purchase_returns = $this->Purchase_return_backup_model->get_purchase_return_list($currency,$vendor)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Code', 'Date', 'Notes', 'Total', 'Balance', 'Action');

        $i = 0;
        foreach ($purchase_returns as $purchase_return)
        {
           $data = array(
                            'name' => 'button',
                            'type' => 'button',
                            'content' => 'Select',
                            'onclick' => 'setvalue(\''.$purchase_return->no.'\',\'treturn\')'
                         );

            $this->table->add_row
            (
                ++$i, 'PR-00'.$purchase_return->no, tgleng($purchase_return->dates), $purchase_return->notes, number_format($purchase_return->total), number_format($purchase_return->balance),
                form_button($data)
            );
        }

        $data['table'] = $this->table->generate();
        $this->load->view('purchase_return_list', $data);
    }

    private function status($val=null)
    { switch ($val) { case 0: $val = 'C'; break; case 1: $val = 'S'; break; } return $val; }
    
//    ===================== approval ===========================================

    private function post_status($val)
    { if ($val == 0) {$class = "notapprove"; }elseif ($val == 1){$class = "approve"; } return $class; }

    function confirmation($pid)
    {
        $purchase_return = $this->Purchase_return_backup_model->get_purchase_return_by_id($pid)->row();

        if ($purchase_return->approved == 1)
        {
           $this->session->set_flashdata('message', "$this->title already approved..!");
           redirect($this->title);
        }
        else
        {
            $this->cek_journal($purchase_return->dates,$purchase_return->currency);
            $total = $purchase_return->total;

            if ($total == 0)
            {
              $this->session->set_flashdata('message', "$this->title has no value..!");
              redirect($this->title);
            }
            else if ( $this->validation_qty($purchase_return->no) == FALSE )
            {
              $this->session->set_flashdata('message', "Invalid Return Qty..!"); 
              redirect($this->title);
            }
            else
            {
                $data = array('approved' => 1);
                $this->Purchase_return_backup_model->update_id($pid, $data);

                //  create journal
                $this->journal->create_journal( $purchase_return->dates,
                                                $purchase_return->currency,
                                                'PR-00'.$purchase_return->no.'-'.$purchase_return->notes,
                                                'PRJ', $purchase_return->no, 'AR',
                                                $purchase_return->balance);

                // create warehouse transaction
                $this->add_warehouse_transaction($purchase_return->no);

               $this->session->set_flashdata('message', "PR-00$purchase_return->no confirmed..!");
               redirect($this->title);
            }
        }

    }

    private function add_warehouse_transaction($po=0)
    {
        $val  = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();
        $list = $this->Purchase_return_backup_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->add($val->dates, 'PR-00'.$po, $this->product->get_id($value->name), 0, $value->qty, $this->session->userdata('log'));
           $this->product->min_qty($value->name,$value->qty);
        }
    }

    private function del_warehouse_transaction($po=0)
    {
        $val  = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();
        $list = $this->Purchase_return_backup_item_model->get_last_item($po)->result();

        foreach ($list as $value)
        {
           $this->wt->remove($val->dates, 'PR-00'.$po, $this->product->get_id($value->name));
           $this->product->add_qty($value->name,$value->qty);
        }
    }

    private function validation_qty($po=0)
    {
       $val = $this->Purchase_return_backup_item_model->get_last_item($po)->result();
       foreach ($val as $res)
       {
           if ( $this->valid_stock($res->name,$res->qty) == FALSE){ return FALSE; break; } else { return TRUE; }
       }
    }

    private function valid_stock($pname,$qty)
    {
        $val = $this->product->get_details($pname);
        if ($qty > $val->qty){ return FALSE;} else { return TRUE;}
    }

    private function cek_journal($date,$currency)
    {
        if ($this->journal->valid_journal($date,$currency) == FALSE)
        {
           $this->session->set_flashdata('message', "Journal for [".tgleng($date)."] - ".$currency." approved..!");
           redirect($this->title);
        }
    }

    private function cek_confirmation($po=null,$page=null)
    {
        $purchase_return = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();

        if ( $purchase_return->approved == 1 )
        {
           $this->session->set_flashdata('message', "Can't change value - PO-00$po approved..!"); // set flash data message dengan session
           if ($page){ redirect($this->title.'/'.$page.'/'.$po); } else { redirect($this->title); }
        }
    }
//    ===================== approval ===========================================


    function delete($uid,$po)
    {
        $this->acl->otentikasi_admin($this->title);

        if ( $this->journal->cek_approval('PR',$po) == TRUE ) // cek journal harian sudah di approve atau belum
        {
            $this->journal->remove_journal('PRJ',$po); // delete journal
            $this->del_warehouse_transaction($po); // delete wt

            $this->Purchase_return_backup_item_model->delete_po($po); // model to delete purchase_return item
            $this->Purchase_return_backup_model->delete($uid); // memanggil model untuk mendelete data

            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
            redirect($this->title);
        }
        else
        {
           $this->session->set_flashdata('message', "1 $this->title can't removed, journal approved..!");
           redirect($this->title);
        } 
    }

    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['code'] = $this->Purchase_return_backup_model->counter();
        $data['user'] = $this->session->userdata("username");
        
        $this->load->view('purchase_return_form', $data);
    }

    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'purchase_return_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	
        $data['code'] = $this->Purchase_return_backup_model->counter();
        $data['user'] = $this->session->userdata("username");
        $data['currency'] = $this->currency->combo();

	// Form validation
        $this->form_validation->set_rules('tpo', 'PO', 'required');
        $this->form_validation->set_rules('tno', 'PR - No', 'required|numeric|callback_valid_no');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');

        if ($this->form_validation->run($this) == TRUE)
        {
            $vendor = $this->purchase->get_po($this->input->post('tpo'));
            $purchase_return = array('vendor' => $vendor->vendor, 'currency' => $vendor->currency, 'purchase' => $this->input->post('tpo'), 'no' => $this->input->post('tno'), 'status' => 0, 'docno' => $this->input->post('tdocno'),
                              'dates' => $this->input->post('tdate'), 'notes' => $this->input->post('tnote'),
                              'user' => $this->user->get_userid($this->input->post('tuser')), 'log' => $this->session->userdata('log'));
            
            $this->Purchase_return_backup_model->add($purchase_return);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
            redirect($this->title.'/add_trans/'.$this->input->post('tno'));
//            echo 'true';
        }
        else
        {
              $this->load->view('purchase_return_form', $data);
//            echo validation_errors();
        }

    }

    function add_trans($po=null)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator '.ucwords($this->modul['title']);
        $data['h2title'] = 'Create New '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process/'.$po);
        $data['form_action_item'] = site_url($this->title.'/add_item/'.$po);
        $data['unit'] = $this->unit->combo();
        $data['tax'] = $this->tax->combo();
        $data['code'] = $po;
        $data['user'] = $this->session->userdata("username");

        $purchase_return = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();

        $data['product'] = $this->pitem->combo($purchase_return->purchase);

        $data['po'] = $purchase_return->purchase;
        $data['default']['vendor'] = $purchase_return->name;
        $data['default']['date'] = $purchase_return->dates;
        $data['default']['note'] = $purchase_return->notes;
        $data['default']['user'] = $this->user->get_username($purchase_return->user);
        $data['default']['docno'] = $purchase_return->docno;
        $data['default']['currency'] = $purchase_return->currency;

        $data['default']['tax']      = $purchase_return->tax;
        $data['default']['totaltax'] = $purchase_return->total;
        $data['default']['balance']  = $purchase_return->total+$purchase_return->costs;
        $data['default']['costs']    = $purchase_return->costs;

//        ============================ Purchase Item  ===============================================
        $pitems = $this->pitem->get_last_item($purchase_return->purchase)->result();

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Qty', 'Unit', 'Unit price', 'Tax', 'Amount');

        $i = 0;
        foreach ($pitems as $pitem)
        {
            $this->table->add_row
            ( ++$i, $pitem->name, $pitem->qty, $pitem->unit, number_format($pitem->price), number_format($pitem->tax), number_format($pitem->amount+$pitem->tax));
        }

        $data['table2'] = $this->table->generate();

//        ============================ Purchase_return_backup Item  =========================================
        $items = $this->Purchase_return_backup_item_model->get_last_item($po)->result();

        // library HTML table untuk membuat template table class zebra
        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        //Set heading untuk table
        $this->table->set_heading('No', 'Item Name', 'Qty', 'Unit', 'Unit price', 'Tax', 'Amount', 'Action');

        $i = 0;
        foreach ($items as $item)
        {
            $this->table->add_row
            (
                ++$i, $item->name, $item->qty, $item->unit, number_format($item->price), number_format($item->tax), number_format($item->amount+$item->tax),
                anchor($this->title.'/delete_item/'.$item->id.'/'.$po,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
            );
        }

        $data['table'] = $this->table->generate();
        
        $this->load->view('purchase_return_transform', $data);
    }


//    ======================  Item Transaction   ===============================================================

    function add_item($po=null)
    {
        $this->cek_confirmation($po,'add_trans');
        
        $this->form_validation->set_rules('cproduct', 'Product', 'required|callback_valid_item['.$po.']');
        $this->form_validation->set_rules('tqty', 'Qty', 'required|numeric');
        $this->form_validation->set_rules('treturn', 'Return', 'required|numeric|callback_valid_qty');
        $this->form_validation->set_rules('tamount', 'Unit Price', 'required');

        if ($this->form_validation->run($this) == TRUE)
        {
            $pitem = array('name' => $this->product->get_name($this->input->post('cproduct')), 'purchase_return_id' => $po, 'qty' => $this->input->post('treturn'),
                           'unit' => $this->product->get_unit($this->input->post('cproduct')),
                           'price' => $this->input->post('tamount'), 'amount' => $this->input->post('treturn') * $this->input->post('tamount'),
                           'tax' => $this->tax->calculate($this->input->post('ctax'),$this->input->post('treturn'),$this->input->post('tamount')));
            $this->Purchase_return_backup_item_model->add($pitem);
            $this->update_trans($po);

            echo 'true';
        }
        else{   echo validation_errors(); }
    }

    private function update_trans($po)
    {
        $totals = $this->Purchase_return_backup_item_model->total($po);
        $purchase_return = array('tax' => $totals['tax'], 'total' => $totals['amount'] + $totals['tax']);
	$this->Purchase_return_backup_model->update($po, $purchase_return);
    }

    function delete_item($id,$po)
    {
        $this->cek_confirmation($po,'add_trans');
        $this->acl->otentikasi2($this->title);
        
        $this->Purchase_return_backup_item_model->delete($id); // memanggil model untuk mendelete data
        $this->update_trans($po);
        $this->session->set_flashdata('message', "1 item successfully removed..!"); // set flash data message dengan session
        redirect($this->title.'/add_trans/'.$po);
    }
//    ==========================================================================================

    // Fungsi update untuk mengupdate db
    function update_process($po=null)
    {
        $this->acl->otentikasi2($this->title);
        $this->cek_confirmation($po,'add_trans');

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('purchase_return/','<span>back</span>', array('class' => 'back')));

	// Form validation

        $this->form_validation->set_rules('tpo', 'PO', 'required');
        $this->form_validation->set_rules('tno', 'PR - No', 'required|numeric');
        $this->form_validation->set_rules('tdate', 'Invoice Date', 'required');
        $this->form_validation->set_rules('tnote', 'Note', 'required');
        $this->form_validation->set_rules('tuser', 'User', 'required');
        $this->form_validation->set_rules('tdocno', 'Doc NO', '');
        $this->form_validation->set_rules('tcosts', 'Landed Cost', 'required|numeric');

        if ($this->form_validation->run($this) == TRUE)
        {
            $purchase_returns = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();

            $purchase_return = array('log' => $this->session->userdata('log'), 'docno' => $this->input->post('tdocno'),
                                     'dates' => $this->input->post('tdate'), 'notes' => $this->input->post('tnote'),
                                     'user' => $this->user->get_userid($this->input->post('tuser')), 'costs' => $this->input->post('tcosts'),
                                     'balance' => $this->input->post('tcosts')+$purchase_returns->total
                             );

            $this->Purchase_return_backup_model->update($po, $purchase_return);
//            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/add_trans/'.$po);
            echo 'true';
        }
        else
        {
//            $this->load->view('purchase_return_transform', $data);
            echo validation_errors();
        }
    }

    public function valid_qty($return)
    {
        $qty = $this->input->post('tqty');
        if ($return > $qty)
        {
            $this->form_validation->set_message('valid_qty', "Invalid Return Qty.!");
            return FALSE;
        }
        else{ return TRUE; }
    }

    public function valid_no($no)
    {
        if ($this->Purchase_return_backup_model->valid_no($no) == FALSE)
        {
            $this->form_validation->set_message('valid_no', "Order No already registered.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function valid_item($product,$po)
    {
        $product = $this->product->get_name($product);
        if ($this->Purchase_return_backup_item_model->valid_item($product,$po) == FALSE)
        {
            $this->form_validation->set_message('valid_item', "Product already listed.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

// ===================================== PRINT ===========================================
    
   function invoice($po=null)
   {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Invoice '.ucwords($this->modul['title']);
        $data['h2title'] = 'Print Invoice'.$this->modul['title'];

        $atts = array('width'=> '800','height'=> '600',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 800)/2)+\'',
                      'screeny'=> '0','class'=> 'tombolprint','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 600)/2)+\'');

        $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

        $this->table->set_template($tmpl);
        $this->table->set_empty("&nbsp;");

        $data['pono'] = $po;
        $this->load->view('purchase_return_invoice_form', $data);
   }

   function print_invoice($po=null,$type=null)
   {
       $this->acl->otentikasi2($this->title);

       $data['h2title'] = 'Print Invoice'.$this->modul['title'];

       $purchase_return = $this->Purchase_return_backup_model->get_purchase_return_by_no($po)->row();

       $data['pono'] = $po;
       $data['logo'] = $this->properti['logo'];
       $data['logo'] = $this->properti['logo'];
       $data['podate'] = tgleng($purchase_return->dates);
       $data['vendor'] = $purchase_return->prefix.' '.$purchase_return->name;
       $data['address'] = $purchase_return->address;
       $data['city'] = $purchase_return->city;
       $data['phone'] = $purchase_return->phone1;
       $data['phone2'] = $purchase_return->phone2;
       $data['user'] = $this->user->get_username($purchase_return->user);
       $data['currency'] = $this->currency->get_code($purchase_return->currency);
       $data['docno'] = $purchase_return->docno;

       $data['cost'] = $purchase_return->costs;
       $data['balance'] = $purchase_return->balance;

       $data['items'] = $this->Purchase_return_backup_item_model->get_last_item($po)->result();

       // property display
       $data['paddress'] = $this->properti['address'];
       $data['p_phone1'] = $this->properti['phone1'];
       $data['p_phone2'] = $this->properti['phone2'];
       $data['p_city'] = ucfirst($this->properti['city']);
       $data['p_zip'] = $this->properti['zip'];
       $data['p_npwp'] = $this->properti['npwp'];

       $this->load->view('purchase_return_invoice', $data);
   }

// ===================================== PRINT ===========================================

// ====================================== REPORT =========================================

    function report()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator Report '.ucwords($this->modul['title']);
        $data['h2title'] = 'Report '.$this->modul['title'];
	$data['form_action'] = site_url($this->title.'/report_process');
        $data['link'] = array('link_back' => anchor('purchase_return/','<span>back</span>', array('class' => 'back')));

        $data['currency'] = $this->currency->combo();
        
        $this->load->view('purchase_return_report_panel', $data);
    }

    function report_process()
    {
        $this->acl->otentikasi2($this->title);
        $data['title'] = $this->properti['name'].' | Report '.ucwords($this->modul['title']);

        $vendor = $this->input->post('tvendor');
        $cur = $this->input->post('ccurrency');
        $start = $this->input->post('tstart');
        $end = $this->input->post('tend');
        $status = $this->input->post('cstatus');

        $data['currency'] = $cur;
        $data['start'] = $start;
        $data['end'] = $end;
        $data['rundate'] = tgleng(date('Y-m-d'));
        $data['log'] = $this->session->userdata('log');

//        Property Details
        $data['company'] = $this->properti['name'];

        $data['purchase_returns'] = $this->Purchase_return_backup_model->report($cur,$vendor,$start,$end,$status)->result();
        $total = $this->Purchase_return_backup_model->total($cur,$vendor,$start,$end,$status);
        
        $data['total'] = $total['total'] - $total['tax'];
        $data['tax'] = $total['tax'];
        $data['costs'] = $total['costs'];
        $data['balance'] = $total['total'] + $total['costs'];

        $this->load->view('purchase_return_report_details', $data);
        
    }


// ====================================== REPORT =========================================

}

?>