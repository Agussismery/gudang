<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-share"></i> Data Pembelian Barang</h4>
    </div>
    <div class="col-sm-12 col-md-2">
        <a href="<?= site_url('tambah_pembelian'); ?>" class="btn btn-success btn-sm btn-block">Tambah Data</a>
    </div>
</div>
<hr class="mt-0" />
<?php
//tampilkan pesan success
if ($this->session->flashdata('success')) {
    echo '<div class="alert alert-success" role="alert">
    ' . $this->session->flashdata('success') . '
  </div>';
}

//tampilkan pesan error
if ($this->session->flashdata('error')) {
    echo '<div class="alert alert-danger" role="alert">
    ' . $this->session->flashdata('error') . '
  </div>';
}
?>
<div class="table-responsive">
    <table class="table table-sm table-hover table-striped" id="tables">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Petani</th>
                <th scope="col">Jumlah / Ball</th>
                <th scope="col">Total Harga Beli</th>
                <th scope="col">Petugas</th>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>