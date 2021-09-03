<?= $this->extend('top_layout') ?>
<?= $this->section('inline-css') ?>
<style>
	#table_detail tbody > tr > th {
		padding: 0 !important;
	}
	#table_detail tbody > tr > th {
		padding: 0 !important;
		height: 20px !impor
	}
	.daftar_ {
		font-weight: bold;
	}
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
	<div class="section-header">
		<h1>Data Tagihan Saya</h1>
		<div class="section-header-breadcrumb">
		</div>
	</div>
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card card-statistic-1">
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label>Filter By Periode</label>
								<select class="form-control filters"  id="filter_periode">
									<option value="">Seluruh periode</option>
									<?php foreach ($data_periode as $index => $periode): ?>
										<option 
										<?php if ($periode->id == $data_periode[0]->id): ?>
											selected
										<?php endif ?>
										value="<?= $periode->id ?>"><?= 
										"{$periode->periode} {$periode->tahun}" ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label>Filter By Jenis Tagihan</label>
								<select class="form-control filters" id="filter_jenis">
									<option value="">Seluruh jenis</option>
									<?php foreach ($data_jenis_tagihan as $index => $jenis): ?>
										<option 
										value="<?= $jenis->id ?>"><?= 
										"{$jenis->nama} " ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label>Filter By Status</label>
								<select class="form-control filters" id="filter_status">
									<option value="">Seluruh status</option>
									<option value="belum">Belum</option>
									<option value="lunas">Lunas</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<form action="">
							<div class="row">
								<div class="card-md-2">
									<div class="form-group"></div>
								</div>
								<div class="card-md-2">
									<div class="form-group"></div>
								</div>
								<div class="card-md-2">
									<div class="form-group"></div>
								</div>
							</div>
						</form>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped" id="tagihan_table">
								<thead>
									<tr>
										<th>ID</th>
										<th>Semester</th>
										<th>Periode</th>
										<th>Tahun</th>
										<th>Jenis</th>
										<th>Biaya</th>
										<th>Status</th>
										<th width="1%">Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?= $this->section('modal') ?>
<div class="modal fade " id="bayar_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="bayar_form" method="POST">
				<div class="modal-header">
					<h4 class="modal-title card-title" id="modal_title">Form</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="tagihan_id" id="tagihan_id">
					<div class="row">
						<div class="col-3" style="margin-bottom:10px;">
							<span class="daftar_">Periode</span>
						</div>
						<div class="col-1">
							:
						</div>
						<div class="col-8" style="padding-left: 0;">
							<span id="periode"></span>
						</div>
						<div class="col-3" style="margin-bottom:10px;">
							<span class="daftar_">Jenis Tagihan</span>
						</div>
						<div class="col-1">
							:
						</div>
						<div class="col-8" style="padding-left: 0;">
							<span id="jenis_tagihan"></span>
						</div>
						<div class="col-3" style="margin-bottom:10px;">
							<span class="daftar_">Biaya</span>
						</div>
						<div class="col-1">
							:
						</div>
						<div class="col-8" style="padding-left: 0;">
							<span id="biaya"></span>
						</div>
						<div class="col-3" style="margin-bottom:10px;">
							<span class="daftar_">Pembayaran Via</span>
						</div>
						<div class="col-1">
							:
						</div>
						<div class="col-8" style="padding-left: 0;">
							<?php foreach ($daftar_pembayaran as $index => $pembayaran): ?>
								<div class="custom-control custom-radio">
									<input type="radio" <?php if ($index == 0): ?>
									checked
									<?php endif ?> id="via<?= $index ?>" name="via" class="custom-control-input" value="<?= $pembayaran->id ?>">
									<label class="custom-control-label" for="via<?= $index ?>"><?= $pembayaran->nama ?></label>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
				<div class="modal-footer  card-footer">
					<div class="float-lg-left mb-lg-0 mb-3">
						<button class="btn btn-primary btn-icon icon-left" type="submit"><i class="fas fa-credit-card"></i> Proses Pembayaran</button>
						<button class="btn btn-danger btn-icon icon-left" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<template  id="render-action-button-template">
	<div class="btn-group" style="white-space: nowrap">
		<button type="button" class="btn  btn-outline-info btn-dollar" onclick="show_tambah_bayar_modal('place_here')">Bayar</button>
	</div>
</template>
<?= $this->endSection() ?>
<?= $this->section('inline-js') ?>
<script>
	let listOfFilter = {
		'periode_id' : "<?= isset($data_periode[0]) ? $data_periode[0]->id : ''?>",
		'jenis_tagihan_id' : "",
		'status' : "",
	};
	$(function() {
		$("#bayar_form").submit(function(e) {
			e.preventDefault();
			let form_data = {
				tagihan_id: $("#tagihan_id").val(),
				via: $("input[name='via']:checked").val(),
			};
			$.ajax({
				url: '<?= base_url("tagihan/bayar") ?>',
				type: 'POST',
				data: form_data,
			})
			.done(function(response) {
				if (!response.success) {
				} else {
					clearMahasiswaForm();
					tagihan_table.ajax.reload(null, false);
					$("#bayar_modal").modal('hide');
					swal({icon: 'success', showConfirmButton: false, timer: 1000})
				}
			});
		});
		let tagihan_table = $("#tagihan_table")
		.on('preXhr.dt', function ( e, settings, data ) {
			data['filters'] = {
				'periode_id': listOfFilter['periode_id'],
				'jenis_tagihan_id': listOfFilter['jenis_tagihan_id'],
				'status': listOfFilter['status'],
			}
		} )
		.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "<?= base_url('tagihan/data-mahasiswa-json-dt') ?>",
			"columns": [
			{ "data": "tagihan_id" },
			{ "data": "semester" },
			{ "data": "periode" },
			{ "data": "tahun" },
			{ "data": "jenis" },
			{ "data": "biaya" },
			{ "data": "status" },
			{ "data": null },
			],
			"order": [[0, 'desc']],
			"columnDefs": [ 
			{
				"targets": -2,
				"render" : function(data, type, row) {
					let badge = '';
					if (row['status'] == 'belum') {
						badge = 'warning';
					} else if (row['status'] == 'lunas') {
						badge = 'success';
					}
					return '<div class="badge badge-'+badge+'">'+row['status']+'</div>';
				}
			},
			{
				"targets": -1,
				"data": null,
				"render" : function(data, type, row) {
					if (row['status'] != 'lunas') {
						return render_bayar_button(row['tagihan_id']);
					} else {
						return '-';
					}
				}
			}, ],
		});
		$(".filters").change(function(event) {
			listOfFilter['periode_id'] = $("#filter_periode").val();
			listOfFilter['jenis_tagihan_id'] = $("#filter_jenis").val();
			listOfFilter['status'] = $("#filter_status").val();
			updateDataByFilter();
		});
		function updateDataByFilter() {
			$("#tagihan_table").DataTable().ajax.reload(null, false);
		}
	});
	function show_tambah_bayar_modal(id) {
		$.ajax({
			url: '<?= base_url("tagihan/info-mahasiswa") ?>',
			type: 'GET',
			data: {id: id},
		})
		.done(function(response) {
			if (!response.success) {
			} else {
				$("#periode").text(response.data.periode);
				$("#jenis_tagihan").text(response.data.jenis_tagihan);
				$("#biaya").text(response.data.biaya);
				$("#tagihan_id").val(id);
				$("#modal_title").text('Form Pembayaran Tagihan');
				$("#bayar_modal").modal('show');
			}
		});
	}
	function render_bayar_button(id) {
		let tmpl = $("#render-action-button-template").html();
		tmpl = tmpl.replace('place_here', id);
		tmpl = tmpl.replace('place_here', id);
		return tmpl;
	}
</script>
<?= $this->endSection() ?>