<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-list" style="color:green"> </i> <?= $title_web; ?>
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
						<h4 class="card-title">Lokasi Unit <?= $wilayah->nama_wilayah; ?></h4>
					</div>
					<div class="box-body">
						<div id="map" style="width: 100%; height: 500px;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h4>Detail Unit <?= $wilayah->nama_wilayah; ?></h4>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-striped table-bordered">
							<tr>
								<td>Nama Unit</td>
								<td>:</td>
								<td><?= $wilayah->nama_wilayah; ?></td>
							</tr>
							<tr>
								<td>Latitude</td>
								<td>:</td>
								<td><?= $wilayah->latitude; ?></td>
							</tr>
							<tr>
								<td>Longitude</td>
								<td>:</td>
								<td><?= $wilayah->longitude; ?></td>
							</tr>
							<tr>
								<td>Lambang Wilayah</td>
								<td>:</td>
								<td> <img src="<?php echo 'data:image/png;base64,' . $wilayah->foto; ?>" class="img-responsive" style="height:auto;width:250px;" /></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
