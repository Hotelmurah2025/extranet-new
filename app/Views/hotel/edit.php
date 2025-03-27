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
                    
                    <form action="<?= base_url('hotel/update/' . $hotel['id']) ?>" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Hotel</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $hotel['name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address', $hotel['address']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokasi Map</label>
                                    <input type="text" class="form-control" id="location" name="location" value="<?= old('location', $hotel['location']) ?>" placeholder="Latitude, Longitude">
                                    <small class="text-muted">Format: -6.123456, 106.123456</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="5"><?= old('description', $hotel['description']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="facilities" class="form-label">Fasilitas Umum</label>
                                    <textarea class="form-control" id="facilities" name="facilities" rows="3"><?= old('facilities', $hotel['facilities']) ?></textarea>
                                    <small class="text-muted">Pisahkan dengan koma (contoh: WiFi, Kolam Renang, Restoran)</small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($images)): ?>
                            <div class="mb-4">
                                <label class="form-label">Foto Hotel Saat Ini</label>
                                <div class="row">
                                    <?php foreach ($images as $image): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="<?= base_url($image['image_path']) ?>" class="card-img-top" alt="Hotel Image" style="height: 150px; object-fit: cover;">
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" name="primary_image" id="primary_<?= $image['id'] ?>" value="<?= $image['id'] ?>" <?= $image['is_primary'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="primary_<?= $image['id'] ?>">
                                                            Jadikan Foto Utama
                                                        </label>
                                                    </div>
                                                    <div class="d-grid">
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteImageModal<?= $image['id'] ?>">
                                                            Hapus Foto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Image Modal -->
                                            <div class="modal fade" id="deleteImageModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="deleteImageModalLabel<?= $image['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteImageModalLabel<?= $image['id'] ?>">Konfirmasi Hapus Foto</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus foto ini?</p>
                                                            <img src="<?= base_url($image['image_path']) ?>" class="img-fluid" alt="Hotel Image">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?= base_url('hotel/deleteImage/' . $image['id'] . '/' . $hotel['id']) ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Tambah Foto Baru</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <small class="text-muted">Anda dapat memilih beberapa file sekaligus. Format yang didukung: JPG, PNG, GIF.</small>
                        </div>
                        
                        <div class="mb-3 text-end">
                            <a href="<?= base_url('hotel') ?>" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
