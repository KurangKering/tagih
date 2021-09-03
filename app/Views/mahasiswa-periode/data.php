<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>

<section class="section">
	<div class="section-header">
		<h1>Data Mahasiswa Periode</h1>
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
								<select class="form-control filters" id="filter_periode">
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
								
								<label>Filter By Semester</label>
								<select class="form-control filters" id="filter_semester">
									<option value="">Seluruh Semester</option>
									<?php foreach ($data_semester as $index => $semester): ?>

										<option 
										value="<?= $semester->semester ?>"><?= $semester->semester ?></option>
									<?php endforeach ?>
								</select>

							</div>

						</div>
					</div>
				</div>
				
				<div class="card">
					
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped" id="mahasiswa_periode_table">
								<thead>
									<tr>
										
										<th>ID</th>
										<th>Periode</th>
										<th>Tahun</th>
										<th>NIM</th>
										<th>Nama</th>
										<th>Semester</th>
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
						<label for="nim">periode</label>
						<select name="periode" id="periode" class="form-control">
							<option value="ganjil" selected>Ganjil</option>
							<option value="genap">Genap</option>
						</select>
					</div>
					<div class="form-group">
						<label for="tahun">Tahun</label>
						<input type="text" class="form-control" id="tahun" value="2020">
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
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?= $this->endSection() ?>
<!-- /.modal -->

<template  id="render-action-button-template">
	<div class="btn-group">
		<button type="button" class="btn  btn-outline-info btn-edit" onclick="show_detail_modal('place_here')"><i class="fas fa-list"></i></button>
	</div>
</template>
<?= $this->endSection() ?>

<?= $this->section('inline-js') ?>
<script>
	let listOfFilter = {
		'periode_id' : "<?= isset($data_periode[0]) ? $data_periode[0]->id : ''?>",
		'semester' : '',
		
	};

	$(function() {

		



		$("#mahasiswa_form").submit(function(e) {
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
					mahasiswa_periode_table.ajax.reload(null, false);
					$("#periode_modal").modal('hide');
					swal({icon: 'success', showConfirmButton: false, timer: 1000})
					
				}
			});
			
		});

		$("#tambah_periode_button").click(function(e) {
			show_tambah_periode_modal();
		});

		let mahasiswa_periode_table = $("#mahasiswa_periode_table")
		.on('preXhr.dt', function ( e, settings, data ) {
			data['filters'] = {
				'periode_id': listOfFilter['periode_id'],
				'semester': listOfFilter['semester'],
			}

		} )
		.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "<?= base_url('mahasiswa-periode/data-json-dt') ?>",
			"columns": [
			{ "data": "id" },
			{ "data": "periode" },
			{ "data": "tahun" },
			{ "data": "nim" },
			{ "data": "nama" },
			{ "data": "semester_berjalan" },
			
			],

		});


		$(".filters").change(function(event) {

			listOfFilter['periode_id'] = $("#filter_periode").val();
			listOfFilter['semester'] = $("#filter_semester").val();
			updateDataByFilter();
		});

		function updateDataByFilter() {
			$("#mahasiswa_periode_table").DataTable().ajax.reload(null, false);
		}

	});


	function clearPeriodeForm() {
		$("#id").val('');
		$("#periode").val('');
		$("#tahun").val('');
	}

	function show_tambah_periode_modal() {
		// clearPeriodeForm();
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

					} else {
						$("#mahasiswa_periode_table").DataTable().ajax.reload(null, false);
						swal({icon: 'success', showConfirmButton: false, timer: 1000})
					}
				});
			}
			
		} );

		
		
	}
	function show_detail_modal(id) {

		location.href="<?= base_url('mahasiswa-periode') ?>";
		
		
	}
	function render_edit_delete_button(id) {
		let tmpl = $("#render-action-button-template").html();
		tmpl = tmpl.replace('place_here', id);
		tmpl = tmpl.replace('place_here', id);

		return tmpl;

	}
</script>
<?= $this->endSection() ?>