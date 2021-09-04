<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>
<section class="section">
	<div class="section-header">
		<h1>Data Periode</h1>
		<div class="section-header-breadcrumb">
			<button class="btn btn-primary" id="tambah_periode_button">Mulai Periode Baru</button>
		</div>
	</div>
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped" id="periode_table">
								<thead>
									<tr>
										<th>ID</th>
										<th>Tahun</th>
										<th>Periode</th>
										<th>Total Mahasiswa</th>
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
<div class="modal fade " id="periode_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="periode_form" method="POST">
				<div class="modal-header card-header">
					<h4 class="modal-title card-title" id="modal_title">Form</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body card-body">
					<input type="hidden" name="id" id="id">
					<div class="form-group">
						<label for="nim">periode</label>
						<select name="periode" id="periode" class="form-control">
							<option value="ganjil" selected>Ganjil</option>
							<option value="genap">Genap</option>
						</select>
					</div>
					<div class="form-group">
						<label for="tahun">Tahun</label>
						<input type="text" class="form-control" id="tahun" value="2021">
					</div>
					<div class="form-group mb-0">
					</div>
					<div class="form-group mb-0">
						<div class="form-check">
							<input class="form-check-input" id="tagihan_spp" type="checkbox" value="1" checked>
							<label class="form-check-label" for="tagihan_spp">
								Mulai Tagihan SPP ?
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer  card-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?= $this->endSection() ?>
<template  id="render-action-button-template">
	<div class="btn-group" style="white-space: nowrap">
		<button  type="button" class="btn  btn-outline-primary btn-list" onclick="show_detail_modal('place_here')" ><i class="fas fa-list"></i> Detail</button>
		<button type="button" class="btn  btn-outline-danger btn-delete" onclick="show_delete_periode_modal('place_here')" ><i class="fas fa-trash"></i> Hapus</button>
	</div>
</template>
<?= $this->endSection() ?>
<?= $this->section('inline-js') ?>
<script>
	$(function() {
		$("#periode_form").submit(function(e) {
			e.preventDefault();
			let form_data = {
				id: $("#id").val(),
				periode: $("#periode :selected").val(),
				tahun: $("#tahun").val(),
				tagihan_spp: $("#tagihan_spp:checked").val(),
			};
			$.ajax({
				url: '<?= base_url("periode/create-update") ?>',
				type: 'POST',
				data: form_data,
			})
			.done(function(response) {
				if (!response.success) {
				} else {
					clearPeriodeForm();
					periode_table.ajax.reload(null, false);
					$("#periode_modal").modal('hide');
					swal({icon: 'success', showConfirmButton: false, timer: 1000})
				}
			});
		});
		$("#tambah_periode_button").click(function(e) {
			show_tambah_periode_modal();
		});
		let periode_table = $("#periode_table").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "<?= base_url('periode/data-json-dt') ?>",
			"columns": [
			{ "data": "id" },
			{ "data": "tahun" },
			{ "data": "periode" },
			{ "data": "total_mahasiswa" },
			{ "data": null },
			],
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"render" : function(data, type, row) {
					return render_edit_delete_button(row['id']);
				}
			} ],
			"order": [[ 0, "desc" ]]
		});
	});
	function clearPeriodeForm() {
		$("#id").val('');
		$("#periode").val('');
		$("#tahun").val('');
	}
	function show_tambah_periode_modal() {
		$("#modal_title").text('Form Mulai Periode Semester');
		$("#periode_modal").modal('show');
	}
	function show_delete_periode_modal(id) {
		swal({
			icon : 'warning',
			title : 'Hapus data',
			text : 'Yakin ingin menghapus data?',
			allowOutsideClick: false,
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: '<?= base_url("periode/delete") ?>',
					type: 'GET',
					data: {id: id},
				})
				.done(function(response) {
					if (!response.success) {
						swal({
							icon: 'warning', 
							title: 'Gagal',
							timer: 1000,
						})
					} else {
						$("#periode_table").DataTable().ajax.reload(null, false);
						swal({icon: 'success', showConfirmButton: false, timer: 1000})
					}
				});
			} 
		} );
	}
	function show_detail_modal(id) {
		location.href="<?= base_url('mahasiswa-periode') ?>"+"/data?periode_id="+id;
	}
	function render_edit_delete_button(id) {
		let tmpl = $("#render-action-button-template").html();
		tmpl = tmpl.replace('place_here', id);
		tmpl = tmpl.replace('place_here', id);
		return tmpl;
	}
</script>
<?= $this->endSection() ?>