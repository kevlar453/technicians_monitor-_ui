<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Daftar Wilayah
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Daftar Wilayah</li>
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
                        <?php if ($this->session->userdata('level') == 'administrator') { ?>
                            <a href="wilayah/tambah"><button class="btn btn-primary"><i class="fa fa-plus"> </i> Tambah Wilayah</button></a>
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
                                        <th>Nama Wilayah</th>
                                        <th>latitude</th>
                                        <th>longitude</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($wilayah as $isi) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td> <img src="<?php echo 'data:image/png;base64,'.$isi['foto']; ?>" class="img-responsive" style="height:auto;width:100px;" /></td>
                                                <td><?= $isi['nama_wilayah']; ?></td>
                                                <td><?= $isi['latitude']; ?></td>
                                                <td><?= $isi['longitude']; ?></td>
                                                <td style="width:17%;">
                                                    <a href="<?= base_url('wilayah/edit/' . $isi['id_wil']); ?>"><button class="btn btn-success" <?= $this->session->userdata('level') == 'administrator'?'':'style="display:none"'; ?>><i class="fa fa-edit"></i></button></a>
                                                    <a href="<?= base_url('wilayah/detail/' . $isi['id_wil']); ?>"><button class="btn btn-primary"><i class="fa fa-eye"></i></button></a>
                                                    <button data-toggle="modal" data-target="#del<?= $isi['id_wil'] ?>" class="btn btn-danger" <?= $this->session->userdata('level') == 'manajemen'?'':'style="display:none"'; ?>><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        } ?>
                                    </tbody>
                                <?php }
                                if ($this->session->userdata('level') == 'technician') { ?>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($wilayah_user as $isi) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td> <img src="<?php echo 'data:image/png;base64,'.$isi->foto; ?>" class="img-responsive" style="height:auto;width:100px;" /></td>
                                                <td><?= $isi->nama_wilayah ?></td>
                                                <td><?= $isi->latitude ?></td>
                                                <td><?= $isi->longitude ?></td>
                                                <td style="width:13%;">
                                                    <a href="<?= base_url('wilayah/edit/' . $isi->id_wil); ?>"><button class="btn btn-success"><i class="fa fa-edit"></i></button></a>
                                                    <a href="<?= base_url('wilayah/detail/' . $isi->id_wil); ?>">
                                                        <button class="btn btn-primary"><i class="fa fa-sign-in"></i> Detail</button></a>
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

<!-- Modal Hapus -->
<?php foreach ($wilayah as $isi) : ?>
    <div class="modal fade" id="del<?= $isi['id_wil'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"><b>Hapus Wilayah</b></h4>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus wilayah <strong><?= $isi['nama_wilayah'] ?></strong> ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a class="btn btn-danger" href="<?= base_url('wilayah/del/' . $isi['id_wil']); ?>">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
