<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Daftar Data Klien
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Daftar Data Klien</li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
                            <a href="client/tambah"><button class="btn btn-primary"><i class="fa fa-plus"> </i> Tambah Klien</button></a>
                        <?php } ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">

                            <br />
                            <table id="example1" class="table table-bordered table-striped table" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Logo</th>
                                        <th>Nama Klien</th>
                                        <th>Alamat</th>
                                        <th>Nama PIC</th>
                                        <th>Unit</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
                                    <tbody>
                                        <?php $no = 1;

                                        //  	id_unit 	nama_client 	nama_pic 	alamat 	serial 	model 	numlabel 	periode 	foto 	unit_geojson 	warna 	latitude 	longitude
                                        foreach ($unit as $isi) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td> <img src="<?php echo 'data:image/png;base64,'.$isi['foto']; ?>" class="img-responsive" style="height:auto;width:100px;" /></td>
                                                <td><?= $isi['nama_client']; ?></td>
                                                <td><?= $isi['alamat']; ?></td>
                                                <td><?= $isi['nama_pic']; ?></td>
                                                <td>Jum Unit</td>
                                                <td style="width:17%;">
                                                    <a href="<?= base_url('client/edit/' . $isi['id_unit']); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
                                                    <a href="<?= base_url('client/detail/' . $isi['id_unit']); ?>"><button class="btn btn-primary"><i class="fa fa-eye"></i></button></a>
                                                    <button data-toggle="modal" data-target="#del<?= $isi['id_unit'] ?>" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        } ?>
                                    </tbody>
                                <?php }
                                if ($this->session->userdata('level') == 'technician') { ?>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($unit_user as $isi) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td> <img src="<?= base_url(); ?>assets_style/file/<?php echo $isi->foto; ?>" class="img-responsive" style="height:auto;width:100px;" /></td>
                                                <td><?= $isi->nama_client ?></td>
                                                <td><?= $isi->alamat ?></td>
                                                <td>Jum Unit</td>
                                                <td><?= $isi->periode ?></td>
                                                <td style="width:13%;">
                                                    <a href="<?= base_url('client/edit/' . $isi->id_unit); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
                                                    <a href="<?= base_url('client/detail/' . $isi->id_unit); ?>">
                                                        <button class="btn btn-primary"><i class="fa fa-eye"></i></button></a>
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        } ?>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
