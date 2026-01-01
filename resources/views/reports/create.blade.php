<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - Laporanku Sidoarjo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .gps-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .map-container {
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #dee2e6;
        }
        .location-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .btn-gps {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-gps:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .auto-filled {
            background-color: #d1ecf1;
            border-color: #28a745;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data" id="reportForm">
                            
                            <!-- Data Pelapor -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-person-fill"></i> Data Pelapor
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_pelapor" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor HP *</label>
                                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="email" class="form-label">Email (Opsional)</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>

                            <!-- Lokasi dengan GPS -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-geo-alt-fill"></i> Lokasi Kejadian
                                    </h5>
                                </div>
                                
                                <!-- GPS Container -->
                                <div class="col-12 mb-3">
                                    <div class="gps-container">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                <i class="bi bi-crosshair"></i> Lokasi GPS
                                            </h6>
                                            <button type="button" class="btn btn-success btn-sm btn-gps" id="getLocationBtn">
                                                <i class="bi bi-geo-alt"></i> Dapatkan Lokasi Saya
                                            </button>
                                        </div>
                                        <small class="opacity-75">
                                            Klik tombol di atas untuk mendapatkan koordinat GPS dan alamat secara otomatis
                                        </small>
                                        
                                        <!-- GPS Status -->
                                        <div id="gpsStatus" class="mt-2" style="display: none;">
                                            <div class="d-flex align-items-center">
                                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span>Mencari lokasi...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Info -->
                                <div class="col-12 mb-3" id="locationInfo" style="display: none;">
                                    <div class="location-info">
                                        <h6 class="text-success mb-2">
                                            <i class="bi bi-check-circle"></i> Lokasi Berhasil Didapatkan
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><strong>Latitude:</strong> <span id="displayLat">-</span></small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Longitude:</strong> <span id="displayLng">-</span></small>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small><strong>Alamat:</strong> <span id="displayAddress">Memuat alamat...</span></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Container -->
                                <div class="col-12 mb-3" id="mapContainer" style="display: none;">
                                    <div class="map-container" id="map"></div>
                                </div>

                                <!-- Manual Location Input -->
                                <div class="col-12 mb-3">
                                    <label for="lokasi" class="form-label">
                                        Nama Lokasi / Alamat *
                                        <span id="autoFilledBadge" class="badge bg-success ms-2" style="display: none;">
                                            <i class="bi bi-check-circle"></i> Terisi Otomatis
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" 
                                           placeholder="Contoh: Jl. Raya Sidoarjo No. 123 atau Dekat Alun-alun Sidoarjo"
                                           required>
                                    <small class="text-muted">
                                        <span id="manualHint">Gunakan tombol GPS atau ketik alamat secara manual</span>
                                        <span id="autoHint" style="display: none;" class="text-success">
                                            <i class="bi bi-info-circle"></i> Alamat telah terisi otomatis dari GPS. Anda dapat mengubahnya jika perlu.
                                        </span>
                                    </small>
                                </div>

                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <input type="hidden" id="alamat_lengkap" name="alamat_lengkap">
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-chat-text-fill"></i> Detail Masalah
                                    </h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Masalah *</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                                              required placeholder="Jelaskan masalah yang Anda laporkan secara detail..."></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="foto" class="form-label">Foto Pendukung</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-send"></i> Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let map;
        let marker;
        let geocoder;

        // Initialize Google Maps
        function initMap() {
            const defaultLocation = { lat: -7.4479, lng: 112.7178 };
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: defaultLocation,
            });
            
            geocoder = new google.maps.Geocoder();
        }

        // Get user location using GPS
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            const gpsStatus = document.getElementById('gpsStatus');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mencari...';
            gpsStatus.style.display = 'block';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Update form fields
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        document.getElementById('displayLat').textContent = lat.toFixed(6);
                        document.getElementById('displayLng').textContent = lng.toFixed(6);
                        
                        // Show location info
                        document.getElementById('locationInfo').style.display = 'block';
                        document.getElementById('mapContainer').style.display = 'block';
                        
                        // Update map
                        const userLocation = { lat: lat, lng: lng };
                        map.setCenter(userLocation);
                        
                        // Add marker
                        if (marker) {
                            marker.setMap(null);
                        }
                        marker = new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            title: "Lokasi Anda",
                            animation: google.maps.Animation.BOUNCE
                        });
                        
                        // Get address from coordinates and AUTO-FILL
                        // Method 1: Try Google Maps Geocoding API
                        geocoder.geocode({ location: userLocation }, (results, status) => {
                            const lokasiField = document.getElementById('lokasi');
                            
                            if (status === "OK" && results[0]) {
                                const address = results[0].formatted_address;
                                document.getElementById('displayAddress').textContent = address;
                                document.getElementById('alamat_lengkap').value = address;
                                
                                // AUTO-FILL: Isi field lokasi dengan alamat dari GPS
                                lokasiField.value = address;
                                
                                // Add visual feedback
                                lokasiField.classList.add('auto-filled');
                                document.getElementById('autoFilledBadge').style.display = 'inline';
                                document.getElementById('manualHint').style.display = 'none';
                                document.getElementById('autoHint').style.display = 'inline';
                                
                                // Remove highlight after 3 seconds
                                setTimeout(() => {
                                    lokasiField.classList.remove('auto-filled');
                                }, 3000);
                                
                            } else {
                                // Fallback: Use OpenStreetMap Nominatim (Free alternative)
                                console.log('Google Geocoding failed, trying OpenStreetMap...');
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.display_name) {
                                            const address = data.display_name;
                                            document.getElementById('displayAddress').textContent = address;
                                            document.getElementById('alamat_lengkap').value = address;
                                            
                                            // AUTO-FILL
                                            lokasiField.value = address;
                                            lokasiField.classList.add('auto-filled');
                                            document.getElementById('autoFilledBadge').style.display = 'inline';
                                            document.getElementById('manualHint').style.display = 'none';
                                            document.getElementById('autoHint').style.display = 'inline';
                                            
                                            setTimeout(() => {
                                                lokasiField.classList.remove('auto-filled');
                                            }, 3000);
                                        } else {
                                            throw new Error('No address found');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Geocoding error:', error);
                                        document.getElementById('displayAddress').textContent = 
                                            `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                                        
                                        // Fill with coordinates as fallback
                                        const coordText = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                                        lokasiField.placeholder = 'Alamat tidak dapat dimuat otomatis, silakan isi manual';
                                        
                                        alert('Alamat tidak dapat dimuat otomatis. Koordinat GPS sudah tersimpan. Silakan isi alamat secara manual.');
                                    });
                            }
                        });
                        
                        // Reset button
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Lokasi Didapat';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-success');
                        gpsStatus.style.display = 'none';
                        
                        // Add success animation
                        document.getElementById('locationInfo').classList.add('pulse');
                        setTimeout(() => {
                            document.getElementById('locationInfo').classList.remove('pulse');
                        }, 1000);
                        
                    },
                    function(error) {
                        let errorMessage = 'Tidak dapat mendapatkan lokasi. ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Izin lokasi ditolak.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Waktu tunggu habis.';
                                break;
                            default:
                                errorMessage += 'Error tidak diketahui.';
                                break;
                        }
                        
                        alert(errorMessage + ' Silakan isi alamat secara manual.');
                        
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        gpsStatus.style.display = 'none';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    }
                );
            } else {
                alert('GPS tidak didukung oleh browser Anda. Silakan isi alamat secara manual.');
                btn.disabled = false;
                btn.innerHTML = originalText;
                gpsStatus.style.display = 'none';
            }
        });

        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            alert('Form berhasil disubmit! (Demo mode)');
        });
    </script>
    
    <!-- Google Maps API -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&libraries=places&callback=initMap">
    </script>
</body>
</html>