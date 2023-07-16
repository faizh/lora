        <!-- Vendor JS Files -->
        <script src="<?= base_url() ?>assets/vendor/aos/aos.js"></script>
        <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Template Main JS File -->
        <script src="<?= base_url() ?>assets/js/index.js"></script>

        <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap"></script> -->


        <script>
            function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                var R = 6371; // Radius of the earth in km
                var dLat = deg2rad(lat2-lat1);  // deg2rad below
                var dLon = deg2rad(lon2-lon1); 
                var a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2)
                    ; 
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                var d = R * c; // Distance in km
                
                return d;
            }

            function deg2rad(deg) {
                return deg * (Math.PI/180)
            }
        </script>
    </body>

</html>