<?= $this->extend('top_layout') ?>
<?= $this->section('content') ?>
<section class="section">
	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					
					<div class="card-body">
						<div class="jumbotron" style="background: white">
							<h1 class="display-6">Selamat Datang, <?= session('role') ?>!</h1>
							<hr class="my-4">
							<p class="lead">
							</p>
						</div>		
					</div>
				</div>
			</div>
		</div>
		
	</div>
</section>
<?= $this->endSection() ?>

