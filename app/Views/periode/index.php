<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Daftar Mahasiswa</h1>
			</div><!-- /.col -->
			<div class="col-sm-6 text-right">
				<button type="button" class="btn btn-success" id="tambah_kata_dasar_button"><i class="fas fa-plus"></i> Tambah Mahasiswa</button>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-12">
				
				<div class="card">
					
					<!-- /.card-header -->
					<div class="card-body">
						<table id="kata_dasar_table" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>NIM</th>
									<th>Nama</th>
									<th>Semester Berjalan</th>
									<th>Angkatan</th>
									<th>Biaya UKT</th>
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
<div class="modal fade card card-primary" id="kata_dasar_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="kata_dasar_form" method="POST">
				<div class="modal-header card-header">
					<h4 class="modal-title card-title" id="modal_title">Form</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body card-body">
					<input type="hidden" name="id" id="id">
					<div class="form-group">
						<label for="kata">Kata</label>
						<input type="text" class="form-control" id="kata" required>
					</div>
					<div class="form-group">
						<label for="arti_kata">Arti kata</label>
						<input type="text" class="form-control" id="arti_kata" required>
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
<!-- /.modal -->

<template  id="render-action-button-template">
	<div class="btn-group">
		<button type="button" class="btn  btn-outline-info btn-edit" onclick="show_edit_kata_dasar_modal('place_here')"><i class="fas fa-edit"></i></button>
		<button type="button" class="btn  btn-outline-danger btn-delete" onclick="show_delete_kata_dasar_modal('place_here')" ><i class="fas fa-trash"></i></button>
	</div>
</template>
<?= $this->endSection() ?>

<?= $this->section('inline-js') ?>
<script>
	$(function() {


		$("#kata_dasar_form").submit(function(e) {
			e.preventDefault();

			let form_data = {
				id: $("#id").val(),
				kata: $("#kata").val(),
				arti_kata: $("#arti_kata").val(),
			};

			$.ajax({
				url: '<?= base_url("kata-dasar/create_or_update") ?>',
				type: 'POST',
				data: form_data,
			})
			.done(function(response) {
				if (!response.success) {

				} else {
					clearKataDasarForm();
					kata_dasar_table.ajax.reload(null, false);
					$("#kata_dasar_modal").modal('hide');
					Swal.fire({icon: 'success', showConfirmButton: false, timer: 1000})
					
				}
			});
			
		});

		$("#tambah_kata_dasar_button").click(function(e) {
			show_tambah_kata_dasar_modal();
		});

		let kata_dasar_table = $("#kata_dasar_table").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "<?= base_url('kata-dasar/show_all') ?>",
			"columns": [
			{ "data": "id" },
			{ "data": "kata" },
			{ "data": "arti_kata" },
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


	function clearKataDasarForm() {
		$("#kata").val('');
		$("#arti_kata").val('');
		$("#id").val('');
	}

	function show_tambah_kata_dasar_modal() {
		clearKataDasarForm();
		$("#modal_title").text('Form tambah kamus');
		$("#kata_dasar_modal").modal('show');
	}

	function show_delete_kata_dasar_modal(id) {
		Swal.fire({
			icon : 'warning',
			title : 'Hapus data',
			text : 'Yakin ingin menghapus data?',
			allowOutsideClick: false,
			showCancelButton: true,
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Jangan',

		})
		.then((res) => {
			if (res.value) {
				$.ajax({
					url: '<?= base_url("kata-dasar/delete") ?>',
					type: 'GET',
					data: {id: id},
				})
				.done(function(response) {
					if (!response.success) {

					} else {
						$("#kata_dasar_table").DataTable().ajax.reload(null, false);
						Swal.fire({icon: 'success', showConfirmButton: false, timer: 1000})
					}
				});
			}
		} );

		
		
	}
	function show_edit_kata_dasar_modal(id) {


		$.ajax({
			url: '<?= base_url("kata-dasar/show") ?>',
			type: 'GET',
			data: {id: id},
		})
		.done(function(response) {
			if (!response.success) {

			} else {
				$("#id").val(response.data.id);
				$("#kata").val(response.data.kata);
				$("#arti_kata").val(response.data.arti_kata);
				$("#modal_title").text('Form ubah data kamus');

				$("#kata_dasar_modal").modal('show');
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