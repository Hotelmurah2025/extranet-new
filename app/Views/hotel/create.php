<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><?= $title ?></h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('hotel/store') ?>" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Hotel</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokasi Map</label>
                                    <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?>" placeholder="Latitude, Longitude">
                                    <small class="text-muted">Format: -6.123456, 106.123456</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"><?= old('description') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="facilities" class="form-label">Fasilitas Umum</label>
                                    <textarea class="form-control" id="facilities" name="facilities" rows="3"><?= old('facilities') ?></textarea>
                                    <small class="text-muted">Pisahkan dengan koma (contoh: WiFi, Kolam Renang, Restoran)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Foto Hotel</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <small class="text-muted">Anda dapat memilih beberapa file sekaligus. Format yang didukung: JPG, PNG, GIF.</small>
                        </div>
                        
                        <div class="mb-3 text-end">
                            <a href="<?= base_url('hotel') ?>" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
