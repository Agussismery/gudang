<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-cubes"></i> Data Pengiriman</h4>
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

<?= form_open(); ?>
<div class="row">
    <div class="col-md-12">

        <div class="form-group row">
            <label for="noball" class="col-sm-2 col-form-label">No. Ball</label>
            <div class="col-sm-3">
                <select class="custom-select custom-select-sm barang-select" onchange="get_item_detail(this.value)" id="nobal" name="nobal">
                    <option value="" disabled selected>Pilih Noball</option>
                    <?php foreach ($data->result() as $d) : ?>
                        <option value="<?= $d->id; ?>">
                            <?= $d->noball ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control form-control-sm" id="id" name="id" placeholder="" style="display: none;">
            </div>
        </div>


        <div class="form-group row">
            <label for="barcode" class="col-sm-2 col-form-label">Barcode Kirim</label>
            <div class="col-sm-3">
                <input type="text" name="barcode" class="form-control form-control-sm" id="barcode" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="tgl_kirim" class="col-sm-2 col-form-label">Tanggal Pengiriman</label>
            <div class="col-sm-3">
                <input type="date" name="tgl_kirim" class="form-control form-control-sm" id="tgl_kirim" placeholder="">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6 offset-sm-2">
                <div id="rowid-field"></div>
                <div id="btn-act">
                    <div class="row">

                        <div class="col-md-6">
                            <button type="submit" name="submit" class="btn btn-primary btn-sm" value="Submit">
                                <i class="fa fa-save"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= form_close(); ?>

<div class="table-responsive">
    <table class="table table-sm table-hover table-striped" id="tables">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">No Ball</th>
                <th scope="col">Mutu Barang</th>
                <th scope="col">Barcode Pengiriman</th>
                <th scope="col">Bruto</th>
                <th scope="col">Netto</th>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>