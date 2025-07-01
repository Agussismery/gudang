<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-10">
        <h4 class="mb-0"><i class="fa fa-share"></i> Tambah Data Pembelian Barang</h4>
    </div>
</div>
<hr class="mt-0" />
<div id="message">
    <?php if ($this->session->flashdata('alert')) : ?>
        <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('alert'); ?></div>
    <?php endif; ?>
</div>
<?= form_open(); ?>
<div class="col-md-12">
    <div class="form-group row">
        <label for="tanggal" class="col-sm-2 col-form-label">ID Pembelian</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" name="nama_teks" value="<?= set_value('nama_teks'); ?>">
            <div class="invalid-feedback">
                <?= form_error('tanggal', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal Pembelian</label>
        <div class="col-sm-3">
            <input type="text" class="form-control form-control-sm <?= (form_error('tanggal')) ? 'is-invalid' : ''; ?>" name="tanggal" id="date-picker" value="<?= (set_value('tanggal')) ? set_value('tanggal') : date('d/m/Y'); ?>">
            <div class="invalid-feedback">
                <?= form_error('tanggal', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="supplier" class="col-sm-2 col-form-label">Petani</label>
        <div class="col-sm-6">
            <select class="custom-select custom-select-sm supplier <?= (form_error('supplier')) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier">
                <option value="" disabled selected>Pilih Petani</option>
                <?php foreach ($supplier->result() as $s) : ?>
                    <option value="<?= $s->id_supplier; ?>">
                        <?= $s->nama_supplier; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
                <?= form_error('supplier', '<p class="error-message">', '</p>'); ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="jumlahx" class="col-sm-2 col-form-label">No. Ball</label>
        <div class="col-sm-2">
            <input type="number" class="form-control form-control-sm" id="jumlahx" placeholder="">
        </div>
    </div>
    <div class="form-group row">
        <label for="barangx" class="col-sm-2 col-form-label">Mutu Barang</label>
        <div class="col-sm-6">
            <select class="custom-select custom-select-sm barang-select" id="barangx">
                <option value="" disabled selected>Pilih Barang</option>
                <?php foreach ($data->result() as $d) : ?>
                    <option value="<?= $d->kode_barang; ?>">
                        <?= $d->nama_barang . ' ( ' . $d->brand . ' )'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm uang" id="harga" placeholder="Harga Satuan" readonly>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#barangx').on('change', function() {
        var kodeBarang = $(this).val();
        if (kodeBarang) {
            $.ajax({
                url: 'get_harga_barang.php',
                type: 'POST',
                data: { kode_barang: kodeBarang },
                success: function(response) {
                    $('#harga').val('Rp ' + response);
                },
                error: function() {
                    $('#harga').val('Gagal memuat harga');
                }
            });
        }
    });
});
</script>

    </div>
    <div class="form-group row">
        <label for="bruto" class="col-sm-2 col-form-label">Berat / Bruto</label>
        <div class="col-sm-2">
            <input type="number" class="form-control form-control-sm" id="bruto" placeholder="Berat / Bruto">
        </div>
    </div>
    <div class="form-group row">
        <label for="netto" class="col-sm-2 col-form-label">Berat / Netto</label>
        <div class="col-sm-2">
            <input type="number" class="form-control form-control-sm" id="netto" placeholder="Berat / Netto" readonly>
        </div>
    </div>

    <script>
        document.getElementById('bruto').addEventListener('input', function () {
            const bruto = parseFloat(this.value);
            const netto = isNaN(bruto) ? '' : bruto - 2;
            document.getElementById('netto').value = netto;
        });
    </script>
    
    <div class="form-group row">
        <div class="col-sm-3 offset-sm-2">
            <div id="rowid-field"></div>
            <div id="btn-act">
                <button type="button" class="btn btn-success btn-sm" onclick="tambah_cart()">Tambah Barang</button>
                <button type="submit" name="submit" class="btn btn-primary btn-sm" value="Submit">
                    <i class="fa fa-save"></i> Simpan Data Pembelian
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">No Bal</th>
                    <th scope="col">Mutu Barang</th>
                    <th scope="col" class="text-center">Bruto</th>
                    <th scope="col" class="text-right">Netto</th>
                    <th scope="col" class="text-right">Harga</th>
                    <th scope="col" class="text-right">Total Harga</th>
                    <th scope="col" class="text-center">Opsi</th>
                </tr>
            </thead>
            <tbody id="daftar-beli">
                <?= $table; ?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-4 offset-sm-8">

        <button type="button" onclick="window.history.back()" class="btn btn-light btn-sm">
            Kembali
        </button>
    </div>
</div>
<?= form_close(); ?>