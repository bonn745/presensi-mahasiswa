var table;
function refreshJadwal(id, url) {
  var nama = $("#nama-" + id).html();
  $("#jadwal-dosen").html("Jadwal " + nama);

  if ($.fn.DataTable.isDataTable("#jadwal-datatable")) {
    $("#jadwal-datatable").DataTable().destroy();
  }

  var displayJadwal = $("#display-jadwal");
  displayJadwal.removeClass("d-none");
  table = $("#jadwal-datatable").DataTable({
    ajax: url,
    processing: true, // Show a processing indicator
    dataSrc: "data",
    columnDefs: [
      {
        searchable: false,
        orderable: false,
        targets: 0,
      },
    ],
    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      $("td:eq(0)", nRow).html(iDisplayIndexFull + 1);
    },
    columns: [
      { data: null },
      { data: "hari" },
      { data: "nama_matkul" },
      { data: "ruangan" },
      { data: "jam_masuk" },
      { data: "jam_pulang" },
    ],
  });
}
