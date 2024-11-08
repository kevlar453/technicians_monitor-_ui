<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Daftar Perusahaan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-file-text"></i>&nbsp; Daftar Data User</li>
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
                        <a href="master/tambah"><button class="btn btn-primary"><i class="fa fa-plus"> </i> Tambah Company</button></a>

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
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($usaha as $isi) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td>
                                                    <center>
                                                        <?php if (!empty($isi['com_logo'] !== "-")) { ?>
                                                            <img src="<?php echo 'data:image/png;base64,' . $isi['com_logo']; ?>" alt="#" class="img-responsive" style="height:auto;width:100px;" />
                                                        <?php } else { ?>
                                                            <!--<img src="" alt="#" class="user-image" style="border:2px solid #fff;"/>-->
                                                            <i class="fa fa-user fa-3x" style="color:#333;"></i>
                                                        <?php } ?>
                                                    </center>
                                                </td>
                                                <td><?= $isi['com_kode']; ?></td>
                                                <td><?= $isi['com_nama']; ?></td>
                                                    <td>
                                                          <button data-toggle="modal" data-target="#del<?= $isi['com_kode'] ?>" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                      </td>
                                                <?php
                                                $no++;
                                            ?>
                                        </tr>

                                    <?php
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php foreach ($usaha as $isi) : ?>
    <!-- Modal Hapus -->
    <div class="modal fade" id="del<?= $isi['com_kode'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"><b>Hapus User</b></h4>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus user <strong><?= $isi['com_nama'] ?></strong> ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a class="btn btn-danger" href="<?= base_url('master/del/' . $isi['com_kode']); ?>">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
