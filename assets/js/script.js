document.addEventListener("DOMContentLoaded", function() {
    const filterInput = document.getElementById("filterTanggal");
    if(filterInput) {
        filterInput.addEventListener("change", function() {
            const tanggal = this.value;
            const rows = document.querySelectorAll("#tabelPeminjaman tbody tr");
            rows.forEach(row => {
                const cellTanggal = row.cells[1].innerText;
                row.style.display = cellTanggal.includes(tanggal) ? "" : "none";
            });
        });
    }
});