<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        <div class="d-flex flex-column">
            <a href="<?= base_url() ?>" class="logo">
                <span>Monitoring Backup Data</span>
            </a>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success rounded-circle text-success me-2">\</span>
                Online
            </div>
        </div>

        <div style="width: 130px;">
            <img src="assets/img/lora.png" class="img-fluid" alt="">
        </div>
    </div>
</header>

<!-- ======= Hero Section ======= -->
<section id="hero" class="hero d-flex align-items-center">

    <div class="container">
        <h1 class="text-center mb-4" data-aos="zoom-out">Backup Data</h1>
        <div class="row">
            <div class="col-lg-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card overflow-hidden" style="height: 60vh;">
                    <div id="map" style="height: 60vh;"></div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card overflow-scroll" style="height: 60vh;">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Pengaturan Backup</h4>
                        <div class="btn-group mb-3" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="setting" id="set1" autocomplete="off"
                                checked>
                            <label class="btn btn-outline-primary" for="set1">1 Menit</label>

                            <input type="radio" class="btn-check" name="setting" id="set2" autocomplete="off">
                            <label class="btn btn-outline-primary" for="set2">15 Menit</label>

                            <input type="radio" class="btn-check" name="setting" id="set3" autocomplete="off">
                            <label class="btn btn-outline-primary" for="set3">30 Menit</label>

                            <input type="radio" class="btn-check" name="setting" id="set4" autocomplete="off">
                            <label class="btn btn-outline-primary" for="set4">1 Jam</label>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">File</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>file 1.txt</td>
                                    <td>
                                        <a href="backup/file1.txt" class="btn"><i class="bi bi-cloud-download"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>file 2.txt</td>

                                    <td>
                                        <a href="backup/file2.txt" class="btn"><i class="bi bi-cloud-download"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>file 3.txt</td>
                                    <td>
                                        <a href="backup/file3.txt" class="btn"><i class="bi bi-cloud-download"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>file 4.txt</td>
                                    <td>
                                        <a href="backup/file4.txt" class="btn"><i class="bi bi-cloud-download"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section><!-- End Hero -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var map = L.map('map').setView([-6.989879783826559, 110.4239962116729], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        /** adding lora marker */
        var latlong_lora1 = [-6.9838859759810825, 110.41548622681835];
        var latlong_lora2 = [-6.9822774315480345, 110.43224338308413];

        var lora1 = L.marker(latlong_lora1).addTo(map).bindPopup('Lora 1');
        var lora1 = L.marker(latlong_lora2).addTo(map).bindPopup('Lora 2');

        /** adding compass */
        L.Control.Watermark = L.Control.extend({
            onAdd: function(map) {
                var img = L.DomUtil.create('img');

                img.src = '<?= base_url() ?>assets/img/compass.png';
                img.style.width = '80px';

                return img;
            },

            onRemove: function(map) {
                // Nothing to do here
            }
        });

        L.control.watermark = function(opts) {
            return new L.Control.Watermark(opts);
        }

        L.control.watermark({ position: 'topright' }).addTo(map);

        /** get distance from lora */
        var distance = parseFloat(getDistanceFromLatLonInKm(
            latlong_lora1[0],
            latlong_lora1[1],
            latlong_lora2[0],
            latlong_lora2[1]
        )).toFixed(2);

        /** create polyline as the distance */
        var latlongs = [
            latlong_lora1,
            latlong_lora2
        ];

        var polyline = L.polyline(latlongs, {color: 'blue'}).bindPopup('Jarak dari Lora 1 dan Lora 2 :  ' + distance + ' KM').addTo(map);

    });
    
</script>