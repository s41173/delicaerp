<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dept extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();
        $this->acl->otentikasi();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->journalgl = $this->load->library('journalgl_lib');
        $this->account  = $this->load->library('account_lib');

        $this->load->model('Dept_model', 'model', TRUE);
    }

    private $properti, $modul, $title, $account;
    private $user,$journalgl,$currency;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
      $this->get_last();
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'finance_view';
	$data['form_action'] = site_url($this->title.'/add_process');
        $data['link'] = array('link_back' => anchor('students','<span>back</span>', array('class' => 'back')));
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

	// ---------------------------------------- //
        $costs = $this->model->get($this->modul['limit'], $offset)->result();
        $num_rows = $this->model->count();

        if ($num_rows > 0)
        {
	    $config['base_url'] = site_url($this->title.'/get_last');
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
            $this->table->set_heading('No', 'Desc', 'Action');

            $i = 0 + $offset;
            foreach ($costs as $cost)
            {
                $this->table->add_row
                (
                    ++$i, $cost->name,
                    anchor($this->title.'/update/'.$cost->dept_id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.
                    anchor($this->title.'/delete/'.$cost->dept_id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
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
   
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'cost_view';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tdesc', 'Desc', 'required|callback_valid_department');

        if ($this->form_validation->run($this) == TRUE)
        {
            $val = array('name' => $this->input->post('tdesc'));
            $this->model->add($val);
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title);
            echo 'true';
        }
        else
        {
//               $this->load->view('template', $data);
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $st = new Student_lib();
        if ($st->cek_relation($uid, 'dept_id') == TRUE)
        {
            $this->model->delete($uid);
            $this->session->set_flashdata('message', "1 $this->title successfully removed..!");
        }
        else{ $this->session->set_flashdata('message', "$this->title related to another component..!"); }        
        redirect($this->title);
    }
    
    function update($uid)
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'finance_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor('bank/','<span>back</span>', array('class' => 'back')));

        $cost = $this->model->get_dept_by_id($uid)->row();
        
        $data['default']['desc']  = $cost->name;
	$this->session->set_userdata('curid', $cost->dept_id);
        $this->load->view('finance_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'finance_update';
	$data['form_action'] = site_url($this->title.'/update_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));

	// Form validation
        $this->form_validation->set_rules('tdesc', 'Desc', 'required|callback_validation_department');

        if ($this->form_validation->run($this) == TRUE)
        {
            $val = array('name' => $this->input->post('tdesc'));
            $this->model->update($this->session->userdata('curid'),$val);

            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->session->unset_userdata('curid');
//            echo 'true';
        }
        else
        {
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            $this->load->view('finance_update', $data);
//            echo validation_errors();
        }
    }

    public function valid_department($name)
    {
        if ($this->model->valid_dept($name) == FALSE)
        {
            $this->form_validation->set_message('valid_department', "Invalid Department..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
    
    public function validation_department($name)
    {
        $id = $this->session->userdata('curid');
        if ($this->model->validating_dept($name,$id) == FALSE)
        {
            $this->form_validation->set_message('validation_department', "Invalid Department..!");
            return FALSE;
        }
        else{ return TRUE; }
    }
   

}

?>