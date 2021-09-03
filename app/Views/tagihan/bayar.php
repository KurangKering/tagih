<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>

<section class="section">
	<div class="section-header">
		<h1>Menu Payment</h1>
		<div class="section-header-breadcrumb">
		</div>
	</div>

	<div class="section-body">
		

		<div class="row">
			<div class="col-12">
				<div class="card">
					
					<div class="card-body">
						<p>Asumsi ini adalah menu payment yang menampilkan pembayaran</p>
						<p>NIM : </p>
						<p>Nama Mahasiswa : </p>
						<p>Semester : </p>
						<p>Biaya : </p>
						<button class="btn btn-primary">Bayar</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</section>
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
					<div class="form-group">
						<label for="biaya_ukt">Biaya UKT</label>
						<input type="text" class="form-control" id="biaya_ukt" >
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
	<div class="btn-group">
		<button type="button" class="btn  btn-outline-info btn-edit" onclick="show_edit_mahasiswa_modal('place_here')"><i class="fas fa-edit"></i></button>
		<button type="button" class="btn  btn-outline-danger btn-delete" onclick="show_delete_mahasiswa_modal('place_here')" ><i class="fas fa-trash"></i></button>
	</div>
</template>
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
				biaya_ukt: $("#biaya_ukt").val(),
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
			{ "data": "semester_berjalan" },
			{ "data": "biaya_ukt" },
			{ "data": null },
			],
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"render" : function(data, type, row) {
					return render_edit_delete_button(row['id']);
				}
			} ],

		});
	});


	function clearMahasiswaForm() {
		$("#id").val('');
		$("#nim").val('');
		$("#nama").val('');
		$("#semester").val('');
		$("#angkatan").val('');
		$("#biaya_ukt").val('');
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
			} else {
				swal('Your imaginary file is safe!');
			}
			
		} );

		
		
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
				$("#biaya_ukt").val(response.data.biaya_ukt);

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