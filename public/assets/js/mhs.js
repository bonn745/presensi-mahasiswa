function formatPhoneNumber(input) {
  let phoneNumber = input.value;

  if (phoneNumber.startsWith("0")) {
    phoneNumber = "62" + phoneNumber.slice(1);
  } else if (!phoneNumber.startsWith("62") && phoneNumber.length > 0) {
    phoneNumber = "62" + phoneNumber;
  }

  input.value = phoneNumber;
}

var tanggal_mulai = document.getElementById("tanggal_mulai");
var tanggal_selesai = document.getElementById("tanggal_selesai");

tanggal_mulai.addEventListener("change", function () {
  var tgl_mulai = this.value;
  var tgl_selesai = document.getElementById("tanggal_selesai").value;

  var data = {
    tgl_mulai: tgl_mulai,
    tgl_selesai: tgl_selesai,
  };

  getMatkul(data);
});

tanggal_selesai.addEventListener("change", function () {
  var tgl_selesai = this.value;
  var tgl_mulai = document.getElementById("tanggal_mulai").value;

  var data = {
    tgl_mulai: tgl_mulai,
    tgl_selesai: tgl_selesai,
  };

  getMatkul(data);
});

function getMatkul(data) {
  var url = window.location.origin + "/mahasiswa/ketidakhadiran/matkul";
  $.ajax({
    url: url,
    type: "POST",
    data: data,
    success: function (data, textStatus, jqXHR) {
      var form = document.getElementById("data-mata-kuliah");
      form.setAttribute("class", "block");
      form.innerHTML = '<Strong>Mata Kuliah</strong><br><br>';
      data["data"].forEach((element) => {
        form.innerHTML +=
          newSelect(element["id"], element["mata_kuliah"]);
      });
      form.innerHTML += '<br><br>';

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
      var form = document.getElementById("data-mata-kuliah");
      form.setAttribute("class", "d-none");
      form.innerHTML = "";
    },
  });
}

function newSelect(id, label) {
  return (
    '<div class="form-check">' +
    '<input name="matkul[]" checked class="form-check-input" type="checkbox" value="' +
    id +
    '" id="matkul' +
    id +
    '">' +
    '<label class="form-check-label" for="matkul' +
    id +
    '">' +
    label +
    "</label>" +
    "</div>"
  );
}
