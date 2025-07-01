<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_berat extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation', 'cart']);
        //load model
        $this->load->model('m_barang');
        $this->load->model('m_pembelian');
        $this->load->model('m_detail_pembelian');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    public function index()
    {
        $this->is_admin();
        if ($this->input->post('submit', TRUE) == 'Submit') {
            
            $this->form_validation->set_rules(
                'bruto',
                'Bruto',
                'required',
                array(
                    'required' => '{field} wajib diisi',
                )
            );
            $this->form_validation->set_rules(
                'netto',
                'Netto',
                'required',
                array(
                    'required' => '{field} wajib diisi',
                )
            );

            if ($this->form_validation->run() == TRUE) {
                $id = $this->security->xss_clean($this->input->post('id', TRUE));
                $data = [
                    'bruto'=>$this->security->xss_clean($this->input->post('bruto', TRUE)),
                    'netto'=>$this->security->xss_clean($this->input->post('netto', TRUE))
                ];
                $this->m_detail_pembelian->update('tbl_detail_pembelian',$data,['id'=>$id]);

                $this->session->set_flashdata('success', 'Data berat berhasil ditambahkan...');

                redirect('input_berat');
            }else{
                $errors = validation_errors('<li>', '</li>');
                $html_message = '<ul>' . $errors . '</ul>';

                $this->session->set_flashdata('error', $html_message);
                redirect('input_berat');
            }

        } else {
            $data = [
                'title' => 'Data Berat',
                'data' => $this->m_detail_pembelian->getData('tbl_detail_pembelian'),
            ];

            $this->template->kasir('data_berat/index', $data);
        }
    }




    public function ajax_berat()
    {
        $this->is_admin();
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {
            //ambil list data
            $list = $this->m_detail_pembelian->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];

            foreach ($list as $i) {
                $button = '';
                if ($this->session->userdata('level') == 'admin' || $this->session->userdata('UserID') == $i->id_user) :

                    $button .= '
                        <button type="button" class="btn btn-success btn-sm"onclick="get_item_detail(' . $i->id . ')">Edit</button>
                        ';

                endif;

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->noball;
                $row[] = $i->id_barang;
                $row[] = $i->bruto;
                $row[] = $i->netto;
                $row[] = 'Taufik';
                $row[] = $button;

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_detail_pembelian->count_all(),
                "recordsFiltered" => $this->m_detail_pembelian->count_filtered(),
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            redirect('dashboard');
        }
    }


    public function get_item_detail()
    {
        //cek login
        //validasi request ajax
        if ($this->input->is_ajax_request()) {
            //tangkap rowid
            $id = $this->security->xss_clean($this->input->post('id', TRUE));

            $arr = $this->m_detail_pembelian->get_row($id);


            echo json_encode($arr);
        } else {
            redirect('dashboard');
        }
    }

    private function is_admin()
    {
        if (!$this->session->userdata('level') || $this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
    }
}
