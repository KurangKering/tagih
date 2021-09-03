<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>
<?= $this->section('inline_css') ?>

<?= $this->endSection() ?>
<section class="section">
	<div class="section-header">
		<h1>Data Mahasiswa</h1>
		<div class="section-header-breadcrumb">
			<button class="btn btn-primary" id="tambah_mahasiswa_button">Tambah Mahasiswa</button>
		</div>
	</div>
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped" id="mahasiswa_table">
								<thead>
									<tr>
										<th>ID</th>
										<th>NIM</th>
										<th>Nama</th>
										<th>Prodi</th>
										<th>Semester</th>
										<th>NO HP</th>
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
<template  id="render-action-button-template">
	<div class="btn-group" style="white-space: nowrap;">
		<button type="button" class="btn  btn-outline-info btn-edit" onclick="show_edit_mahasiswa_modal('place_here')"><i class="fas fa-edit"></i> Ubah</button>
		<button type="button" class="btn  btn-outline-danger btn-delete" onclick="show_delete_mahasiswa_modal('place_here')" ><i class="fas fa-trash"></i> Hapus</button>
	</div>
</template>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<div class="modal fade " id="mahasiswa_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="mahasiswa_form" method="POST">
				<div class="modal-header card-header">
					<h4 class="modal-title card-title" id="modal_title">Form</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body card-body">
					<input type="hidden" name="id" id="id">
					<div class="form-group">
						<label for="nim">NIM</label>
						<input type="text" class="form-control" id="nim" >
					</div>
					<div class="form-group">
						<label for="nama">Nama Mahasiswa</label>
						<input type="text" class="form-control" id="nama" >
					</div>
					<div class="form-group">
						<label for="angkatan">Angkatan</label>
						<input type="date" class="form-control" id="angkatan" >
					</div>
					<div class="form-group">
						<label for="semester">Semester</label>
						<input type="text" class="form-control" id="semester" >
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

<?= $this->section('inline-js') ?>
<script>
	$(function() {
		$("#mahasiswa_form").submit(function(e) {
			e.preventDefault();

			let form_data = {
				id: $("#id").val(),
				nim: $("#nim").val(),
				nama: $("#nama").val(),
				semester_berjalan: $("#semester").val(),
				angkatan: $("#angkatan").val(),
			};
			$.ajax({
				url: '<?= base_url("mahasiswa/create-update") ?>',
				type: 'POST',
				data: form_data,
			})
			.done(function(response) {
				if (!response.success) {
				} else {
					clearMahasiswaForm();
					mahasiswa_table.ajax.reload(null, false);
					$("#mahasiswa_modal").modal('hide');
					swal({icon: 'success', showConfirmButton: false, timer: 1000})
				}
			});
		});
		$("#tambah_mahasiswa_button").click(function(e) {
			show_tambah_mahasiswa_modal();
		});
		let mahasiswa_table = $("#mahasiswa_table").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "<?= base_url('mahasiswa/data-json-dt') ?>",
			"columns": [
			{ "data": "id" },
			{ "data": "nim" },
			{ "data": "nama" },
			{ "data": "program_studi" },
			{ "data": "semester_berjalan" },
			{ "data": "no_hp" },
			{ "data": "status" },
			{ "data": null },
			],
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"render" : function(data, type, row) {
					return render_edit_delete_button(row['id']);
				}
			},
			{
				"targets": -2,
				"render" : function(data, type, row) {
					let badge = '';
					if (row['status'] == 'inactive') {
						badge = 'warning';
					} else if (row['status'] == 'active') {
						badge = 'success';
					}
					return '<div class="badge badge-'+badge+'">'+row['status']+'</div>';
				}
			} ,
			],
			"order": [[ 0, "desc" ]]
		});
	});

	function clearMahasiswaForm() {
		$("#id").val('');
		$("#nim").val('');
		$("#nama").val('');
		$("#semester").val('');
		$("#angkatan").val('');
	}
	function show_tambah_mahasiswa_modal() {
		clearMahasiswaForm();
		$("#modal_title").text('Form tambah Mahasiswa');
		$("#mahasiswa_modal").modal('show');
	}
	function show_delete_mahasiswa_modal(id) {
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
					url: '<?= base_url("mahasiswa/delete") ?>',
					type: 'GET',
					data: {id: id},
				})
				.done(function(response) {
					if (!response.success) {

					} else {
						$("#mahasiswa_table").DataTable().ajax.reload(null, false);
						swal({icon: 'success', showConfirmButton: false, timer: 1000})
					}
				});
			} 
		});
	}
	function show_edit_mahasiswa_modal(id) {
		$.ajax({
			url: '<?= base_url("mahasiswa/show") ?>',
			type: 'GET',
			data: {id: id},
		})
		.done(function(response) {
			if (!response.success) {

			} else {
				$("#id").val(response.data.id);
				$("#nim").val(response.data.nim);
				$("#nama").val(response.data.nama);
				$("#semester").val(response.data.semester_berjalan);
				$("#angkatan").val(response.data.angkatan);

				$("#modal_title").text('Form ubah data Mahasiswa');

				$("#mahasiswa_modal").modal('show');
			}
		});
	}
	function render_edit_delete_button(id) {
		let tmpl = $("#render-action-button-template").html();
		tmpl = tmpl.replace('place_here', id);
		tmpl = tmpl.replace('place_here', id);
		return tmpl;
	}

</script>
<?= $this->endSection() ?>