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
<?= $this->endSection() ?>
<?= $this->section('inline-js') ?>
<script>
	let listOfFilter = {
		'periode_id' : "<?= isset($data_periode[0]) ? $data_periode[0]->id : ''?>",
		
		'semester' : '',
	};
	$(function() {
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
</script>
<?= $this->endSection() ?>