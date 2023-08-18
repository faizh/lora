<header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <img src="<?= base_url() ?>assets/img/lora.png" style="width: 100px" />
            <div class="col">
                <a href="<?= base_url() ?>" class="logo">
                    <span>Monitoring Backup Data</span>
                </a>
                <div class="mt-3 d-flex align-items-center">
                    <span class="badge bg-success rounded-circle text-success me-2">\</span>
                    Online
                </div>
            </div>
    </div>
</header>

<!-- ======= Hero Section ======= -->
<section id="hero" class="hero d-flex align-items-center">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card overflow-hidden" style="height: 60vh;">
                    <div id="map" style="height: 60vh;"></div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card overflow-scroll" style="height: 60vh;">
                    <div class="card-body">
                        <div class="row text-center m-2">
                            <div class="col-md-3">
                                Tanggal
                            </div>
                            <div class="col-md-9">
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="text-center" role="group" aria-label="Basic radio toggle button group">
                            <input onchange="setBackup(5)" type="radio" class="btn-check" name="setting" id="set1" autocomplete="off"
                                <?= ($interval == 5) ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="set1">5 Menit</label>

                            <input onchange="setBackup(300)" type="radio" class="btn-check" name="setting" id="set2" autocomplete="off"
                                <?= ($interval == 300) ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="set2">5 Jam</label>

                            <input onchange="setBackup(600)" type="radio" class="btn-check" name="setting" id="set3" autocomplete="off"
                                <?= ($interval == 600) ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="set3">10 Jam</label>

                            <input onchange="setBackup(1440)" type="radio" class="btn-check" name="setting" id="set4" autocomplete="off"
                                <?= ($interval == 1440) ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="set4">24 Jam</label>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">File</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Aksi</th>
                                    <th scope="col">Clear</th>
                                    <th scope="col">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backup_files as $key => $file) { ?>
                                    <tr>
                                        <th scope="row"><?= $key + 1 ?></th>
                                        <td><?= $file->filename ?></td>
                                        <td><?= $file->created_dtm ?></td>
                                        <td>
                                            <a href="<?= base_url() . 'index.php/backup/download/' . $file->id ?>" class="btn" target="__BLANK"><i class="bi bi-cloud-download"></i></a>
                                        </td>
                                        <td>
                                            <a href="<?= base_url() . 'index.php/backup/delete/' . $file->id ?>" class="btn" target="__BLANK"><i class="bi bi-trash"></i></a>
                                        </td>
                                        <td>
                                            
                                            <a href="<?= base_url() . $file->path ?>" target="_BLANK" class="btn"button class="button button1">View</button></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
        var latlong_lora1 = [<?= $lora_1->latitude ?>, <?= $lora_1->longitude ?>];
        var latlong_lora2 = [<?= $lora_2->latitude ?>, <?= $lora_2->longitude ?>];

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

        var content_lora1 = '<b><h5>Lora 1</h5></b>' + 
                            '(' + latlong_lora1[0] + ',' + latlong_lora1[1] + ') <br />' +
                            'Jarak dari Lora 1 dan Lora 2 :  <b>' + distance + ' KM</b>';

        var content_lora2 = '<b><h5>Lora 2</h5></b>' + 
                            '(' + latlong_lora2[0] + ',' + latlong_lora2[1] + ') <br />' +
                            'Jarak dari Lora 2 dan Lora 1 :  <b>' + distance + ' KM</b>';

        var lora1 = L.marker(latlong_lora1).addTo(map).bindPopup(content_lora1);
        var lora1 = L.marker(latlong_lora2).addTo(map).bindPopup(content_lora2);

    });

    function setBackup(interval) {
        disableRadioBtn();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("index.php/backup/updateBackupInterval")?>',
            data:{interval: interval},
            dataType: 'json',
            success: function(data) {
                enableRadioBtn();

            },
        });
    }

    function disableRadioBtn() {
        document.getElementById("set1").disabled = true;
        document.getElementById("set2").disabled = true;
        document.getElementById("set3").disabled = true;
        document.getElementById("set4").disabled = true;
    }

    function enableRadioBtn() {
        document.getElementById("set1").disabled = false;
        document.getElementById("set2").disabled = false;
        document.getElementById("set3").disabled = false;
        document.getElementById("set4").disabled = false;
    }
    
</script>