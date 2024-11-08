<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<span><img src="<?php echo 'data:image/png;base64,'.$client->foto; ?>" style="height:auto;width:24px;" /></span> <?= $title_web; ?> [<strong><?= $client->nama_client; ?></strong>]
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-list"></i>&nbsp; <?= $title_web; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-sm-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h4 class="card-title">Lokasi</h4>
					</div>
					<div class="box-body">
						<div id="map" style="width: 100%; height: 400px;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h4>Detail Klien</h4>
						<input type="hidden" value="<?= $client->kode_client; ?>" id="base_client">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table">
							<tr>
								<td style="width:16%;">Nama Klien</td>
								<td>:</td>
								<td><?= $client->nama_client; ?></td>
							</tr>
							<tr>
								<td>Nama PIC</td>
								<td>:</td>
								<td><?= $client->nama_pic; ?></td>
							</tr>
							<!--id_unit 	nama_client 	nama_pic 	alamat 	serial 	model 	numlabel 	periode 	foto 	unit_geojson 	warna 	latitude 	longitude
							-->
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td><?= $client->alamat; ?></td>
							</tr>
							<tr>
								<td>Unit</td>
								<td>:</td>
								<td>
									<?php
									$q = [];
									foreach ($dunit as $iunit) {
										$umur = round((time()-strtotime($iunit->unit_install))/(3600*24*365.25));
										if($umur < 3){
											$wumur = 'black';
											$tumur = '< 3 th';
										} elseif ($umur < 6 && $umur >= 3) {
											$wumur = 'blue';
											$tumur = '3 - 6 th';
										} elseif ($umur < 9 && $umur >=6) {
											$wumur = 'purple';
											$tumur = '7 - 9 th';
										} else {
											$wumur = 'red';
											$tumur = '> 9 th';
										}
										$q[] .= '<button class="btn" style="background-color:#f6f6f6;color:'.$wumur.'" data-toggle="modal" data-target="#spec-'.$iunit->unit_kode.'"><span  data-toggle="tooltip" data-placement="top" title="'.$tumur.'">'.$iunit->unit_merk.'-'.$iunit->unit_model.'-'.$iunit->unit_serial.'</span></button>';
									}
									echo implode(', ',$q);
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="box-footer with-border">
						<button class="btn btn-info pull-right" data-toggle="modal" data-target="#tbhunit">Tambah Unit</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="box box-body">
				<div class="box-header with-border">
					<h4>Visit Logs</h4>
				</div>
				<div class="table-responsive">

						<br />
						<table id="tblvisit" class="table table-bordered table-striped table" width="100%">
								<thead>
										<tr>
												<th>No</th>
												<th>Tanggal</th>
												<th>Kode</th>
												<th>Model</th>
												<th>Job Desc</th>
												<th>Status</th>
												<th>Teknisi</th>
												<th>Doc</th>
										</tr>
								</thead>
								<?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
										<tbody>
												<?php $no = 1;
												$setisi = json_decode(json_encode($rep_main),true);

												//  	id_unit 	nama_client 	nama_pic 	alamat 	serial 	model 	numlabel 	periode 	foto 	unit_geojson 	warna 	latitude 	longitude
												foreach ($setisi as $isi) {
													$sjob = explode(',',$isi['rep_job']);
													$arraytext = ["Maintenance", "Survey", "Repair", "Test & Commissioning"];
													$sjob_index = array_search("&#10003;", $sjob);

													if ($sjob_index !== false && isset($arraytext[$sjob_index])) {
														$sjob_text = $arraytext[$sjob_index];
													} else {
														$sjob_text = "-";
													}

													$ssts = explode(',',$isi['rep_status']);
													$statustext = ["Normal", "Failure", "Repair"];
													$ssts_index = array_search("&#10003;", $ssts);

													if ($ssts_index !== false && isset($statustext[$ssts_index])) {
														$ssts_text = $statustext[$ssts_index];
													} else {
														$ssts_text = "-";
													}


													?>
														<tr>
																<td><?= $no; ?></td>
																<td><?= $isi['rep_tgl']; ?></td>
																<td><?= $isi['rep_kode']; ?></td>
																<td><?= $isi['rep_model']; ?></td>
																<td><?= $sjob_text; ?></td>
																<td><?= $ssts_text; ?></td>
																<td><?= $isi['rep_nmtek']; ?></td>
																<td>
																		<a href="<?= base_url('monitor/reportv1/' . $isi['rep_kode']); ?>"  target="_blank"><button class="btn btn-success"><i class="fa fa-print"></i></button></a>
																</td>
														</tr>
												<?php $no++;
												} ?>
										</tbody>
								<?php }
								if ($this->session->userdata('level') == 'operator') { ?>
										<tbody>
												<?php $no = 1;
												$setisi = json_decode(json_encode($rep_main),true);
												foreach ($setisi as $isi) {
													$sjob = explode(',',$isi['rep_job']);
													$arraytext = ["Maintenance", "Survey", "Repair", "Test & Commissioning"];
													$sjob_index = array_search("&#10003;", $sjob);

													if ($sjob_index !== false && isset($arraytext[$sjob_index])) {
														$sjob_text = $arraytext[$sjob_index];
													} else {
														$sjob_text = "-";
													}

													$ssts = explode(',',$isi['rep_status']);
													$statustext = ["Normal", "Failure", "Repair"];
													$ssts_index = array_search("&#10003;", $ssts);

													if ($ssts_index !== false && isset($statustext[$ssts_index])) {
														$ssts_text = $statustext[$ssts_index];
													} else {
														$ssts_text = "-";
													}
													?>
														<tr>
																<td><?= $no; ?></td>
																<td><?= $isi['rep_tgl'] ?></td>
																<td><?= $isi['rep_kode'] ?></td>
																<td><?= $isi['rep_model'] ?></td>
																<td><?= $sjob_text ?></td>
																<td><?= $ssts_text ?></td>
																<td><?= $isi['rep_nmtek'] ?></td>
																<td>
																		<a href="<?= base_url('monitor/reportv1/' . $isi['rep_kode']); ?>"  target="_blank"><button class="btn btn-success"><i class="fa fa-print"></i></button></a>
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
	</section>
</div>
<div class="modal fade" id="tbhunit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
				<div class="modal-content">
						<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel"><b>Unit Baru</b></h4>
						</div>
						<div class="modal-body">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Tgl Pasang</label>
									<input type="text" class="form-control" name="ui_install" required="required" id="ui_install">
								</div>
								<div class="form-group">
									<label>Merk</label>
									<input type="text" class="form-control" name="ui_merk" required="required" id="ui_merk">
								</div>
								<div class="form-group">
									<label>Serial Number</label>
									<input type="text" class="form-control" name="ui_serial" required="required" id="ui_serial">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Model/Version</label>
									<input type="text" class="form-control" name="ui_model" required="required" id="ui_model">
								</div>
								<div class="form-group">
									<label>Label Number</label>
									<input type="text" class="form-control" name="ui_numlabel" required="required" id="ui_numlabel">
								</div>
								<div class="form-group">
									<label>Periode</label>
									<input type="text" class="form-control" name="ui_periode" required="required" id="ui_periode">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
							<button type="button" class="btn btn-success" data-dismiss="modal" onclick="simunit();">Simpan</button>
						</div>
				</div>
		</div>
</div>
