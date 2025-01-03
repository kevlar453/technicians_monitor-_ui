<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Peta Klien
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Peta</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="container">
                            <?php if (empty($unit)) { ?>
                                <?php echo '<script>alert("UNIT TIDAK DITEMUKAN");window.location="' . base_url('monitor') . '"</script>'; ?>
                            <?php } else { ?>
                                <h3 class="my-2 text-gray-800">Anda Mencari Klien: <?= $keyword ?></h3>
                            <?php } ?>
                            <div class="form-inline row">
                                <h5 class="col-sm-3"><b>Cari Lokasi Klien:</b></h5>
                                <div class="col-sm-6">
                                    <?php echo form_open('monitor/pencarian') ?>
                                    <select name="keyword" class="form-control">
                                        <?php foreach ($daftar_unit as $isi) { ?>
                                            <option value="<?= $isi['nama_client']; ?>" <?php if ($isi['nama_client'] == $keyword) {
                                                                                            echo 'selected';
                                                                                        } ?>><?= $isi['nama_client']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" class="btn btn-success">Cari</button>
                                    <?php echo form_close() ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="container-fluid">
                            <div id="map" style="width: 100%; height: 550px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
