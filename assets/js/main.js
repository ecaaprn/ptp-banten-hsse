// assets/js/main.js

document.addEventListener('DOMContentLoaded', function () {
  // Setup Chart.js global defaults
  if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748B';
  }

  // 1. Dashboard Chart: Tren Kecelakaan Bulanan
  const canvasTren = document.getElementById('chartTrenKecelakaan');
  if (canvasTren) {
    const ctx = canvasTren.getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
          label: 'Jumlah Kejadian',
          data: [2, 3, 4, 3, 5, 4, 6, 5, 0, 0, 0, 0],
          backgroundColor: '#1890FF',
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0B1E36',
            titleFont: { size: 13, weight: 'bold' },
            padding: 12,
            cornerRadius: 8
          }
        },
        scales: {
          x: { grid: { display: false } },
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    });
  }

  // 2. Dashboard Chart: Jenis Kecelakaan (Donut)
  const canvasJenis = document.getElementById('chartJenisKecelakaan');
  if (canvasJenis) {
    const ctx = canvasJenis.getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Terjatuh/Tergelincir', 'Tertabrak', 'Terguling', 'Terjepit', 'Lainnya'],
        datasets: [{
          data: [8, 6, 4, 7, 7],
          backgroundColor: ['#1890FF', '#FA8C16', '#FF4D4F', '#13C2C2', '#94A3B8'],
          borderWidth: 2,
          borderColor: '#FFFFFF'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: {
            position: 'right',
            labels: {
              usePointStyle: true,
              boxWidth: 8,
              font: { size: 11, weight: '600' }
            }
          }
        }
      }
    });
  }

  // 3. Statistik Chart: Jenis (Horizontal Bar)
  const canvasJenisHoriz = document.getElementById('chartJenisHoriz');
  if (canvasJenisHoriz) {
    const ctx = canvasJenisHoriz.getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      indexAxis: 'y',
      data: {
        labels: ['Terguling', 'Terjatuh/Tergelincir', 'Tertabrak', 'Terjepit', 'Lainnya'],
        datasets: [{
          label: 'Kejadian',
          data: [6, 8, 6, 5, 7],
          backgroundColor: '#1890FF',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { beginAtZero: true },
          y: { grid: { display: false } }
        }
      }
    });
  }

  // Live filter table on data-kecelakaan.php
  const searchInput = document.getElementById('tableSearchInput');
  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      const term = this.value.toLowerCase();
      const rows = document.querySelectorAll('#kecelakaanTableBody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  }
});

// Modal Detail Trigger
function showDetailModal(id, idKejadian, tanggal, lokasi, jenis, klasifikasi, perusahaan, korban, status, kronologi, penyebab, tindakan) {
  const modal = document.getElementById('detailModal');
  if (!modal) return;

  document.getElementById('modalIdKejadian').innerText = idKejadian;
  document.getElementById('modalTanggal').innerText = tanggal;
  document.getElementById('modalLokasi').innerText = lokasi;
  document.getElementById('modalJenis').innerText = jenis;
  document.getElementById('modalKlasifikasi').innerText = klasifikasi;
  document.getElementById('modalPerusahaan').innerText = perusahaan;
  document.getElementById('modalKorban').innerText = korban;
  document.getElementById('modalStatus').innerText = status;
  document.getElementById('modalKronologi').innerText = kronologi || '-';
  document.getElementById('modalPenyebab').innerText = penyebab || '-';
  document.getElementById('modalTindakan').innerText = tindakan || '-';

  modal.classList.add('active');
}

function closeModal() {
  const modal = document.getElementById('detailModal');
  if (modal) modal.classList.remove('active');
}
