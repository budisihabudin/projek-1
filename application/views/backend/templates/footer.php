   <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div><script>document.write(new Date().getFullYear())</script> Hak Cipta © </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                                <p class="mb-0">Develop by <a href="https://wa.me/6285772772080" target="_blank">B. S</a> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

       <!-- App js -->
    <script src="<?= base_url('assets/js/vendor.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/app.js'); ?>"></script>

    <!-- Knob charts js -->
    <script src="<?= base_url('assets/libs/jquery-knob/jquery.knob.min.js'); ?>"></script>

    <!-- Sparkline Js -->
    <script src="<?= base_url('assets/libs/jquery-sparkline/jquery.sparkline.min.js'); ?>"></script>

    <script src="<?= base_url('assets/libs/morris.js/morris.min.js'); ?>"></script>

    <script src="<?= base_url('assets/libs/raphael/raphael.min.js'); ?>"></script>

    <!-- Dashboard init -->
    <script src="<?= base_url('assets/js/pages/dashboard.js'); ?>"></script>

    <!-- jQuery (sudah ada di vendor.min.js, jadi cukup pastikan sudah ada) -->
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    

<script>
$(document).ready(function() {
    $('.paket-select').select2({
        placeholder: "-- Pilih Paket --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$(document).ready(function() {
    $('.jabatan-select').select2({
        placeholder: "-- Pilih Jabatan --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$(document).ready(function() {
    $('.role-akun-select').select2({
        placeholder: "-- Pilih Role --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$(document).ready(function() {
    $('.surveyor-select').select2({
        placeholder: "-- Pilih Surveyor --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$(document).ready(function() {
    $('.customer-select').select2({
        placeholder: "-- Pilih Customer --",
        allowClear: true,
        width: '100%'
    });
});
</script>

<script>
$(document).ready(function() {
    $('.sales-select').select2({
        placeholder: "-- Pilih Sales --",
        allowClear: true,
        width: '100%'
    });
});
</script>



</body>
</html>
