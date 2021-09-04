<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>
<section class="section">
	<div class="section-header">
		<h1>Data Grafik Tagihan Mahasiswa</h1>
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
								<label>Filter By Semester</label>
								<select class="form-control filters" id="filter_semester">
									<option value="">Seluruh Semester</option>
									<?php foreach ($data_semester as $index => $semester): ?>
										<option 
										value="<?= $semester->semester ?>"><?= 
										$semester->semester ?></option>
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
							
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<canvas id="myChart4"></canvas>

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
		'semester' : "",
		'jenis_tagihan_id' : "",
	};


	var ctx = document.getElementById("myChart4").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'pie',
		data: {},
		options: {
			responsive: true,
			legend: {
				position: 'bottom',
			},
		}
	});
	$(function() {
		$.ajax({
			url: '<?= base_url('tagihan/grafik-pimpinan-json') ?>',
			type: 'GET',
			data: {filters: listOfFilter},
		})
		.done(function(result) {
			console.log("success");
			myChart.data = result;
			myChart.update();
		});
		$(".filters").change(function(event) {
			listOfFilter['periode_id'] = $("#filter_periode").val();
			listOfFilter['semester'] = $("#filter_semester").val();
			listOfFilter['jenis_tagihan_id'] = $("#filter_jenis").val();
			$.ajax({
				url: '<?= base_url('tagihan/grafik-pimpinan-json') ?>',
				type: 'GET',
				data: {filters: listOfFilter},
			})
			.done(function(result) {
				console.log("success");
				myChart.data = result;
				myChart.update();
			});
		});
	});
</script>
<?= $this->endSection() ?>