<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_mutu_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library(['template', 'form_validation', 'cart']);
        //load model
        $this->load->model('m_barang');
        $this->load->model('m_pembelian');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    public function index()
    {
        $this->is_admin();

        $data = [
            'title' => 'Data Barang'
        ];

        $this->template->kasir('data_mutu_barang/index', $data);
    }

    public function tambah_data()
    {
        // $this->is_admin();

        //ketika button simpan di klik maka lakukan proses validasi dan penyimpanan data
        if ($this->input->post('submit', TRUE) == 'Submit') {
            //cek apakah user sudah memilih barang atau belum, jika belum maka munculkan pesan kesalahan
            if (!$this->cart->contents()) {
                $this->session->set_flashdata('alert', 'Anda belum memilih barang...');

                redirect('tambah_mutu_barang', 'refresh');
            }
            //validasi input data tanggal
            $this->form_validation->set_rules(
                'tanggal',
                'Tanggal Pembelian',
                'required',
                array(
                    'required' => '{field} wajib diisi',
                )
            );

            $this->form_validation->set_rules(
                'supplier',
                'Supplier',
                'required',
                array(
                    'required' => '{field} wajib dipilih',
                    'min_length' => '{field} tidak valid'
                )
            );

            if ($this->form_validation->run() == TRUE) {

                $id = 'ID' . time();
                $tgl = date('Y-m-d', strtotime(str_replace('/', '-', $this->security->xss_clean($this->input->post('tanggal', TRUE)))));
                $sup = $this->security->xss_clean($this->input->post('supplier', TRUE));
                $user = $this->session->userdata('UserID');

                $data_pembelian = [
                    'id_pembelian' => $id,
                    'tgl_pembelian' => $tgl,
                    'id_supplier' => $sup,
                    'id_user' => $user
                ];
                //baca cart dan memasukkannya dalam array untuk disimpan
                $cart = array();

                foreach ($this->cart->contents() as $c) {
                    $item = [
                        'id_pembelian' => $id,
                        'id_barang' => $c['id'],
                        'qty' => $c['qty'],
                        'harga' => $c['price'],
                        'noball'=>$$c['options']['nobal']
                    ];

                    //push ke array cart
                    array_push($cart, $item);
                }
                //simpan data pembelian
                $simpan = $this->m_pembelian->save('tbl_pembelian', $data_pembelian);

                if ($simpan) {
                    //simpan data detail pembelian
                    $this->m_pembelian->multiSave('tbl_detail_pembelian', $cart);
                    //kosongkan cart
                    $this->cart->destroy();
                    //buat notifikasi penyimpanan berhasil
                    $this->session->set_flashdata('success', 'Data pembelian berhasil ditambahkan...');

                    redirect('mutu_barang');
                }
            }
        }

        $data = [
            'title' => 'Tambah Data Mutu Barang',
            'data' => $this->m_pembelian->getData('tbl_barang', ['active' => 'Y']),
            'supplier' => $this->m_pembelian->getAllData('tbl_supplier'),
            'id_pembelian'=>'ID' . time(),
            'table' => $this->read_cart()
        ];
        // die();
        $this->template->kasir('data_mutu_barang/form_input', $data);
    }
 

    public function ajax_mutu_barang()
    {
        $this->is_admin();
        //cek apakah request berupa ajax atau bukan, jika bukan maka redirect ke home
        if ($this->input->is_ajax_request()) {


            //ambil list data
            $list = $this->m_pembelian->get_datatables();
            //siapkan variabel array
            $data = array();
            $no = $_POST['start'];

            foreach ($list as $i) {
                $button = '';
                if ($this->session->userdata('level') == 'admin' || $this->session->userdata('UserID') == $i->id_user) :

                    $button .= '
                        <button type="button" class="btn btn-danger btn-sm"onclick="hapus_pembelian(\'' . $i->id_pembelian . '\')">Hapus</button>
                        <a href="' . site_url('edit_pembelian/' . $i->id_pembelian) . '" class="btn btn-warning btn-sm text-white">Edit</a>
                        ';

                endif;
          
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $i->id_pembelian;
                $row[] = $this->tanggal_indo($i->tgl_pembelian);
                $row[] = $i->nama_supplier;
                $row[] = $i->jumlah;
                $row[] = $i->fullname;
                $row[] = '<a href="' . site_url('data_pembelian/' . $i->id_pembelian) . '" class="btn btn-sm btn-success">Detail</a>
                ' . $button;

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->m_barang->count_all(),
                "recordsFiltered" => $this->m_barang->count_filtered(),
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            redirect('dashboard');
        }

    }
    private function tanggal_indo($tgl)
    {
        $bulan  = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $exp    = explode('-', date('d-m-Y', strtotime($tgl)));

        return $exp[0] . ' ' . $bulan[(int) $exp[1]] . ' ' . $exp[2];
    }
 
    private function is_admin()
    {
        if (!$this->session->userdata('level') || $this->session->userdata('level') != 'admin') {
            redirect('dashboard');
        }
    }

    private function read_cart()
    {
        // var_dump($this->cart->contents());
        // die();
        if ($this->cart->contents()) {

            $table = '';
            $i = 1;
            foreach ($this->cart->contents() as $c) {
                $table .= '<tr><td>' . $i++ . '</td>';
                $table .= '<td class="text-left">' . $c['options']['nobal'] . '</td>';
                $table .= '<td>' . $c['name'] . '</td>';
                //$table .= '<td class="text-left">' . $c['qty'] . '</td>';
                $table .= '<td class="text-center">' . $c['price'] . '</td>';
                $table .= '<td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm text-white" onclick="get_item(\'' . $c['rowid'] . '\')">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm text-white" onclick="remove_item(\'' . $c['rowid'] . '\')">Hapus</button>
                            </td></tr>';
            }
        } else {
            $table = '<tr>
                        <td scope="col" colspan="5" class="text-center"><i>Belum ada data</i></td>
                    </tr>';
        }

        return $table;
    }
}
