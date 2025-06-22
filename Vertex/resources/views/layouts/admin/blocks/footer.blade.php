
</div>
<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.js') }}"></script>

<!-- Plugins js-->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

<!--select2 Plugins js-->
<script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script> --}}
<script src="{{ asset('assets/libs/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>

<!-- Summernote js -->
<script src="{{ asset('assets/libs/summernote/summernote-bs4.min.js') }}"></script>

<!-- Init js -->
<script src="{{ asset('assets/js/pages/form-summernote.init.js') }}"></script>

<!-- datepicker js -->
<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<!-- clockpicker js -->
<script src="{{ asset('assets/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('assets/js/pages/form-pickers.init.js') }}"></script>

<!-- third party js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}" async defer></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}" async defer></script>

<!-- Datatables init -->
<script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

<!-- App js-->
<script src="{{ asset('assets/js/app.min.js') }}"></script>

<script>
    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 3000);
</script>
{{-- <style>
.imag {
  padding-top: 10px;
  padding-right: 100px;
  padding-bottom: 50px;
  padding-left: 100px;
}
</style>
<div class="container-fluid">
    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-md-6" style="padding-top: 10px;padding-right:100px;padding-bottom:50px; padding-left: 100px">
            <h4 style="color: #22232b">&#169;2023 Unity.All Rights Reserved. {{ $version->version }}</h4>
        </div>
        <div class="col-md-6 imag" style="right: 0%">
            <img src="{{ asset('assets/images/theme/' . $setting[16]['value']) }}" alt=""
            height="50%" width="50%">
        </div>
    </div>
</div>
</div> --}}
</body>

</html>
