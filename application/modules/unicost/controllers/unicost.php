<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unicost extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->properti = $this->property->get();

        $this->modul = $this->components->get(strtolower(get_class($this)));
        $this->title = strtolower(get_class($this));

        $this->currency = $this->load->library('currency_lib');
        $this->user = $this->load->library('admin_lib');
        $this->dept = $this->load->library('dept_lib');
        $this->employee = $this->load->library('employee_lib');
        $this->model = new Unicosts();
    }

    private $properti, $modul, $title,$dept,$employee;
    private $user,$currency,$model;

    private  $atts = array('width'=> '400','height'=> '200',
                      'scrollbars' => 'yes','status'=> 'yes',
                      'resizable'=> 'yes','screenx'=> '0','screenx' => '\'+((parseInt(screen.width) - 400)/2)+\'',
                      'screeny'=> '0','class'=> 'print','title'=> 'print', 'screeny' => '\'+((parseInt(screen.height) - 200)/2)+\'');

    function index()
    {
       $this->get_last();
    }
    
    function autocomplete()
    {
      $keyword = $this->uri->segment(3);

      // cari di database
      $data = $this->db->from('students')->like('name',$keyword,'after')->get();

      // format keluaran di dalam array
      foreach($data->result() as $row)
      {
         $arr['query'] = $keyword;
         $arr['suggestions'][] = array(
            'value'  =>$row->name,
            'data'   =>$row->students_id
         );
      }

      // minimal PHP 5.2
      echo json_encode($arr);
    }

    function get_last()
    {
        $this->acl->otentikasi1($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'unicost_view';
	$data['form_action'] = site_url($this->title.'/search');
        $data['link'] = array('link_back' => anchor('registration_reference/','<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo_all();
        
	$uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);
        
	// ---------------------------------------- //
        $result = $this->model->get($this->modul['limit'], $offset);
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
//            
//
            // library HTML table untuk membuat template table class zebra
            $tmpl = array('table_open' => '<table cellpadding="2" cellspacing="1" class="tablemaster">');

            $this->table->set_template($tmpl);
            $this->table->set_empty("&nbsp;");

            //Set heading untuk table
            $this->table->set_heading('No', 'Department', 'Male', 'Female', 'Practice', 'Scout', 'Additional Cost', 'Pairs', '#');
//
            $i = 0 + $offset;
            foreach ($result as $res)
            {
                $this->table->add_row
                (
                    ++$i, $this->dept->get_name($res->dept_id), number_format($res->m), number_format($res->f), number_format($res->practice), number_format($res->scout), number_format($res->additional), $res->pair,
                    anchor($this->title.'/update/'.$res->id,'<span>details</span>',array('class' => 'update', 'title' => '')).' '.    
                    anchor($this->title.'/delete/'.$res->id,'<span>delete</span>',array('class'=> 'delete', 'title' => 'delete' ,'onclick'=>"return confirm('Are you sure you will delete this data?')"))
                );
            }
//
            $data['table'] = $this->table->generate();
        }
        else
        {
            $data['message'] = "No $this->title data was found!";
        }
//
        // Load absen view dengan melewatkan var $data sbgai parameter
	$this->load->view('template', $data);
    }
    
    
    function add()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'unicost_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
        
        $data['dept'] = $this->dept->combo();
        $this->load->view('unicost_form', $data);
    }
    
    function add_process()
    {
        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'honorfee_form';
	$data['form_action'] = site_url($this->title.'/add_process');
	$data['link'] = array('link_back' => anchor($this->title,'<span>back</span>', array('class' => 'back')));
         
        $data['dept'] = $this->dept->combo(); 
        
	// Form validation
        $this->form_validation->set_rules('cdept', 'Department', 'required|callback_valid_cost');
        $this->form_validation->set_rules('tmale', 'Male Cost', 'required|numeric');
        $this->form_validation->set_rules('tfemale', 'Female Cost', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice Cost', 'required|numeric');
        $this->form_validation->set_rules('tscout', 'Scout Cost', 'required|numeric');
        $this->form_validation->set_rules('tadd', 'Additional Cost', 'required|numeric');
        
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->dept_id    = $this->input->post('cdept');
            $this->model->m          = $this->input->post('tmale');
            $this->model->f          = $this->input->post('tfemale');
            $this->model->practice   = $this->input->post('tpractice');
            $this->model->scout      = $this->input->post('tscout');
            $this->model->additional = $this->input->post('tadd');
//            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title data successfully saved!");
//            redirect($this->title.'/add');
            echo 'true';
        }
        else
        { 
            //$this->load->view('honorfee_form', $data); 
            echo validation_errors();
        }

    }
    
    function delete($uid)
    {
        $this->acl->otentikasi_admin($this->title);
        $this->model->where('id', $uid)->get();
        $this->model->delete(); 
        $this->session->set_flashdata('message', "1 $this->title successfully removed..!");       
        redirect($this->title);
    }
    
    function update($uid)
    {
//        $this->acl->otentikasi2($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'unicost_update';
	$data['form_action'] = site_url($this->title.'/update_process');
        $data['dept'] = $this->dept->combo(); 
        
        $this->model->where('id', $uid)->get();
        
        $data['default']['dept']     = $this->dept->get_name($this->model->dept_id);
        $data['default']['male']     = $this->model->m;
        $data['default']['female']   = $this->model->f;
        $data['default']['practice'] = $this->model->practice;
        $data['default']['scout']    = $this->model->scout;
        $data['default']['add']      = $this->model->additional;
        
	$this->session->set_userdata('curid', $this->model->id);
        $this->load->view('unicost_update', $data);
    }

    // Fungsi update untuk mengupdate db
    function update_process()
    {
        $this->acl->otentikasi3($this->title);

        $data['title'] = $this->properti['name'].' | Administrator  '.ucwords($this->modul['title']);
        $data['h2title'] = $this->modul['title'];
        $data['main_view'] = 'student_form';
	$data['form_action'] = site_url($this->title.'/update_process');

	// Form validation
        $this->form_validation->set_rules('tmale', 'Male Cost', 'required|numeric');
        $this->form_validation->set_rules('tfemale', 'Female Cost', 'required|numeric');
        $this->form_validation->set_rules('tpractice', 'Practice Cost', 'required|numeric');
        $this->form_validation->set_rules('tscout', 'Scout Cost', 'required|numeric');
        $this->form_validation->set_rules('tadd', 'Additional Cost', 'required|numeric');
        
        if ($this->form_validation->run($this) == TRUE)
        {
            $this->model->where('id', $this->session->userdata('curid'))->get();
            
            $this->model->m          = $this->input->post('tmale');
            $this->model->f          = $this->input->post('tfemale');
            $this->model->practice   = $this->input->post('tpractice');
            $this->model->scout      = $this->input->post('tscout');
            $this->model->additional = $this->input->post('tadd');
            
            $this->model->save();
            $this->session->set_flashdata('message', "One $this->title has successfully updated!");
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
            
            echo 'true'; 
        }
        else
        {
//            $this->load->view('honorfee_update', $data);
           echo validation_errors();
//            redirect($this->title.'/update/'.$this->session->userdata('curid'));
        }
        
        $this->session->unset_userdata('curid');
    }
    
    public function valid_cost($dept)
    {
        $val = $this->model->where('dept_id', $dept)->count();

        if ($val > 0)
        {
            $this->form_validation->set_message('valid_cost', "Cost type - already registered..!");
            return FALSE;
        }
        else {  return TRUE; }
    }
    
    public function valid_period($date=null)
    {
        $p = new Period();
        $p->get();

        $month = date('n', strtotime($date));
        $year  = date('Y', strtotime($date));

        if ( intval($p->month) != intval($month) || intval($p->year) != intval($year) )
        {
            $this->form_validation->set_message('valid_period', "Invalid Period.!");
            return FALSE;
        }
        else {  return TRUE; }
    }

    public function report()
    {
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
        $data['address'] = $this->properti['address'];
        $data['phone1']  = $this->properti['phone1'];
        $data['phone2']  = $this->properti['phone2'];
        $data['fax']     = $this->properti['fax'];
        $data['website'] = $this->properti['sitename'];
        $data['email']   = $this->properti['email'];
        
        $data['log']     = $this->session->userdata('log');
        $data['company'] = $this->properti['name'];
                
        $data['results'] = $this->model->get();
        
        $this->load->view('unicost_report', $data);
    }

}

?>