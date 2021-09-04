<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>
<section class="section">
	<div class="section-header">
		<h1>Tambah Data Tagihan</h1>
		<div class="section-header-breadcrumb">
		</div>
	</div>
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card card-statistic-1">
					<form id="form_cek_data_tagihan" >
						<div class="card-body">
							<div class="form-row">
								<div class="form-group col-md-4">
									<label>Periode</label>
									<select class="form-control filters" name="periode_id"  id="periode_id">
										<?php foreach ($data_periode as $index => $periode): ?>
											<option value="<?= $periode->id ?>"><?="{$periode->periode} {$periode->tahun}"?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label>Jenis Tagihan</label>
									<select required class="form-control filters" name="jenis_tagihan_id"  id="jenis_tagihan_id">
										<option value="" selected disabled >Pilih Jenis Tagihan</option>
										<?php foreach ($data_jenis_tagihan as $index => $jenis_tagihan): ?>
											<option data-biaya="<?= $jenis_tagihan->biaya ?>" value="<?= $jenis_tagihan->id ?>"><?=
											"{$jenis_tagihan->nama}"
											?></option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="form-row text-center">
									<button type="button" id="button_tambah_tagihan" class="btn btn-primary">Tambah</button>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped" id="tagihan_kosong_table">
							<thead>
								<tr>
									<th>MP ID</th>
									<th>NIM</th>
									<th>Nama</th>
									<th>Semester</th>
									<!-- <th width="1%">Action</th> -->
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
<div class="modal fade " id="tagihan_masal_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="tagihan_form" method="POST">
				<div class="modal-header card-header">
					<h4 class="modal-title card-title" id="modal_title">Form</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body card-body">
					<input type="hidden" name="id" id="id">
					<input type="hidden" id="query_periode_id" name="query_periode_id">
					<input type="hidden" id="query_jenis_tagihan_id" name="query_jenis_tagihan_id">
					<div class="form-group">
						<label for="periode">Periode</label>
						<input type="text" readonly class="form-control" id="tampil_periode" >
					</div>
					<div class="form-group">
						<label for="jenis_tagihan">Jenis Tagihan</label>
						<input type="text" readonly class="form-control" id="tampil_jenis_tagihan" >
					</div>
					<div class="form-group">
						<label for="biaya">Biaya</label>
						<input type="text"  class="form-control" id="query_biaya" >
					</div>
					<div class="form-group">
						<label for="jumlah_mahasiswa">Jumlah Mahasiswa</label>
						<input type="text" readonly class="form-control" id="tampil_jumlah_mahasiswa" >
					</div>
				</div>
				<div class="modal-footer  card-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?= $this->endSection() ?>
<!-- /.modal -->
<template  id="render-action-button-template">
	<div class="btn-group" style="white-space: nowrap">
		<button type="button" class="btn  btn-outline-info btn-edit" onclick="show_edit_tagihan_masal_modal('place_here')"><i class="fas fa-plus"></i> Tambah</button>
	</div>
</template>
<?= $this->endSection() ?>
<?= $this->section('inline-js') ?>
<script>
	var query = {
		"periode": '',
		"periode_id": '',
		"jenis_tagihan": '',
		"jenis_tagihan_id": '',
		"biaya": '',
	};
	var mahasiswaPeriodeIds = '';
	$(function() {
		$(document).on('change', '.filters', function(e) {
			e.preventDefault();
			let formData = $("#form_cek_data_tagihan").serialize();
			$.ajax({
				url: '<?= base_url('tagihan/cek') ?>',
				type: 'POST',
				data: formData,
			})
			.done(function(result) {
				$("#tagihan_kosong_table").DataTable().clear();
				$("#tagihan_kosong_table").DataTable().rows.add(result);
				$("#tagihan_kosong_table").DataTable().draw();
				if (result.length > 0) {
					query = {
						"periode": $("#periode_id option:selected").text(),
						"periode_id": $("#periode_id").val(),
						"jenis_tagihan": $("#jenis_tagihan_id option:selected").text(),
						"jenis_tagihan_id": $("#jenis_tagihan_id").val(),
						"biaya": $("#jenis_tagihan_id option:selected").data('biaya'),
					};
				} else {
					query = {
						"periode": '',
						"periode_id": '',
						"jenis_tagihan": '',
						"jenis_tagihan_id": '',
						"biaya": '',
					};
				}
			});
		})
		$("#tagihan_form").submit(function(e) {
			e.preventDefault();
			let form_data = {
				periode_id: $("#query_periode_id").val(),
				jenis_tagihan_id: $("#query_jenis_tagihan_id").val(),
				biaya: $("#query_biaya").val(),
				mahasiswa_periode_ids: mahasiswaPeriodeIds,
			};
			$.ajax({
				url: '<?= base_url("tagihan/create") ?>',
				type: 'POST',
				data: form_data,
			})
			.done(function(response) {
				if (!response.success) {
				} else {
					$("#tagihan_masal_modal").modal('hide');
					swal({icon: 'success', showConfirmButton: false, timer: 1000})
					let formCek = {
						periode_id: query['periode_id'],
						jenis_tagihan_id: query['jenis_tagihan_id'],
					}
					$.ajax({
						url: '<?= base_url('tagihan/cek') ?>',
						type: 'POST',
						data: formCek,
					})
					.done(function(result) {
						$("#tagihan_kosong_table").DataTable().clear();
						$("#tagihan_kosong_table").DataTable().rows.add(result);
						$("#tagihan_kosong_table").DataTable().draw();
						if (result.length > 0) {
							query = {
								"periode": $("#periode_id option:selected").text(),
								"periode_id": $("#periode_id").val(),
								"jenis_tagihan": $("#jenis_tagihan_id option:selected").text(),
								"jenis_tagihan_id": $("#jenis_tagihan_id").val(),
								"biaya": $("#jenis_tagihan_id option:selected").data('biaya'),
							};
						} else {
							query = {
								"periode": '',
								"periode_id": '',
								"jenis_tagihan": '',
								"jenis_tagihan_id": '',
								"biaya": '',
							};
						}
					});
				}
			});
		});
		$("#button_tambah_tagihan").click(function(e) {
			if ($("#tagihan_kosong_table").DataTable().columns(0).data()[0].length > 0) {
				showTambahTagihanMasal();
			} else {
				swal({
					icon: 'warning', 
					title: 'Tidak ada data',
					showConfirmButton: false, 
					timer: 1000
				});
			}
		});
		let tagihan_kosong_table = $("#tagihan_kosong_table").DataTable({
			"columns": [
			{ "data": "mp_id" },
			{ "data": "nim" },
			{ "data": "nama" },
			{ "data": "semester_berjalan" },
			// { "data": null },
			],
			// "columnDefs": [ {
			// 	"targets": -1,
			// 	"data": null,
			// 	"render" : function(data, type, row) {
			// 		return render_edit_delete_button(row['id']);
			// 	}
			// } ],
		});
	});
	function showTambahTagihanMasal() {
		$("#modal_title").text('Form tambah Tagihan Masal');
		$("#tampil_periode").val(query['periode']);
		$("#query_periode").val(query['periode']);
		$("#tampil_jenis_tagihan").val(query['jenis_tagihan']);
		$("#query_periode_id").val(query['periode_id']);
		$("#query_jenis_tagihan_id").val(query['jenis_tagihan_id']);
		$("#query_biaya").val(query['biaya']);
		mahasiswaPeriodeIds = $("#tagihan_kosong_table").DataTable().columns(0).data()[0];
		$("#tampil_jumlah_mahasiswa").val(mahasiswaPeriodeIds.length + " Mahasiswa");
		$("#tagihan_masal_modal").modal('show');
	}
	function render_edit_delete_button(id) {
		let tmpl = $("#render-action-button-template").html();
		tmpl = tmpl.replace('place_here', id);
		tmpl = tmpl.replace('place_here', id);
		return tmpl;
	}
</script>
<?= $this->endSection() ?>