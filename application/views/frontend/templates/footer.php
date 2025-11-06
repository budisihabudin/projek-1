<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
  };

  <?php if ($this->session->flashdata('success')): ?>
    toastr.success("<?= $this->session->flashdata('success'); ?>");
  <?php endif; ?>

  <?php if ($this->session->flashdata('error')): ?>
    toastr.error("<?= $this->session->flashdata('error'); ?>");
  <?php endif; ?>
</script>

</body>
</html>
