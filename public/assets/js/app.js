var form = document.getElementById("import-form");
var btn = document.getElementById("btn-simpan");
var footer = document.getElementById("modal-footer");

btn.addEventListener("click", function () {
  footer.innerHTML = '<i class="fa fa-spinner fa-spin"></i> mengupload';
  form.submit();
});