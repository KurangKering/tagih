<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>

<section class="section">
	<div class="section-header">
		<h1>Data Tagihan Mahasiswa</h1>
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
						<div class="table-responsive">
							<table class="table table-striped" id="mahasiswa_table">
								<thead>
									<tr>
										
										<th>ID</th>
										<th>NIM</th>
										<th>Nama</th>
										<th>Semester</th>
										<th>Periode</th>
										<th>Tahun</th>
										<th>Jenis</th>
										<th>Biaya</th>
										<th>Status</th>
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


<?= $this->endSection() ?>

<?= $this->section('inline-js') ?>
<script>
	let listOfFilter = {
		'periode_id' : "<?= isset($data_periode[0]) ? $data_periode[0]->id : ''?>",
		'jenis_tagihan_id' : "",
		'status' : "",
	};

	$(function() {


		$("#mahasiwa_form").submit(function(e) {
			e.preventDefault();

			let form_data = {
				id: $("#id").val(),
				kata: $("#kata").val(),
				arti_kata: $("#arti_kata").val(),
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

		$("#tambah_tagihan_button").click(function(e) {
			location.href="<?= base_url('tagihan/tambah') ?>";
		});

		let mahasiswa_table = $("#mahasiswa_table")
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
			"ajax": "<?= base_url('tagihan/data-pimpinan-json-dt') ?>",
			"columns": [
			{ "data": "tagihan_id" },
			{ "data": "nim" },
			{ "data": "nama" },
			{ "data": "semester" },
			{ "data": "periode" },
			{ "data": "tahun" },
			{ "data": "jenis" },
			{ "data": "biaya" },
			{ "data": "status" },
			],
			"order": [[ 0, "desc" ]],
			"columnDefs": [ 
			{
				"targets": -1,
				"render" : function(data, type, row) {
					let badge = '';
					if (row['status'] == 'belum') {
						badge = 'warning';
					} else if (row['status'] == 'lunas') {
						badge = 'success';
					}
					return '<div class="badge badge-'+badge+'">'+row['status']+'</div>';
				}
			} ],


		});

		$(".filters").change(function(event) {

			listOfFilter['periode_id'] = $("#filter_periode").val();
			listOfFilter['jenis_tagihan_id'] = $("#filter_jenis").val();
			listOfFilter['status'] = $("#filter_status").val();
			updateDataByFilter();
		});

		function updateDataByFilter() {
			$("#mahasiswa_table").DataTable().ajax.reload(null, false);
		}
	});


	function clearMahasiswaForm() {
		$("#kata").val('');
		$("#arti_kata").val('');
		$("#id").val('');
	}

	function show_tambah_mahasiswa_modal() {
		clearMahasiswaForm();
		$("#modal_title").text('Form tambah kamus');
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
					url: '<?= base_url("tagihan/delete") ?>',
					type: 'GET',
					data: {id: id},
				})
				.done(function(response) {
					if (!response.success) {
						swal({
							icon: 'warning', 
							title: 'Gagal',
							text: 'Tagihan telah lunas!',
							timer: 1000,
						})


					} else {
						$("#mahasiswa_table").DataTable().ajax.reload(null, false);
						swal({icon: 'success', showConfirmButton: false, timer: 1000})
					}
				});
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
				$("#kata").val(response.data.kata);
				$("#arti_kata").val(response.data.arti_kata);
				$("#modal_title").text('Form ubah data kamus');

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