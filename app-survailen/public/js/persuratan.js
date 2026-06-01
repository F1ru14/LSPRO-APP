document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const rawJenis = urlParams.get('jenis');
    const jenis = rawJenis ? rawJenis.toLowerCase().trim() : null;
    
    let suratType = 'Surat Pemberitahuan';
    
    if (jenis) {
        const types = {
            'pemberitahuan': 'Surat Pemberitahuan',
            'pelaksanaan': 'Surat Pelaksanaan',
            'teguran': 'Surat Teguran',
            'teguran1': 'Surat Teguran 1',
            'teguran2': 'Surat Teguran 2',
            'pembekuan': 'Surat Pembekuan',
            'pembekuan1': 'Surat Pembekuan 1',
            'pembekuan2': 'Surat Pembekuan 2'
        };
        
        if (types[jenis]) {
            suratType = types[jenis];
            let labelEl = document.getElementById('jenis-surat-label');
            if (labelEl) labelEl.textContent = suratType;
            let titleEl = document.getElementById('form-title');
            if (titleEl) titleEl.textContent = 'Form ' + suratType;
        }
    }

    // Live Preview Logic
    const inputs = {
        tanggal: document.getElementById('tanggal_surat'),
        nama_perusahaan: document.getElementById('nama_perusahaan'),
        alamat_perusahaan: document.getElementById('alamat_perusahaan'),
        kota_provinsi: document.getElementById('kota_provinsi'),
        nomor_surat_rujukan: document.getElementById('nomor_surat_rujukan'),
        nomor_sppt_sni: document.getElementById('nomor_sppt_sni'),
        merek: document.getElementById('merek'),
        tanggal_sertifikat: document.getElementById('tanggal_sertifikat'),
        tanggal_pelaksanaan: document.getElementById('tanggal_pelaksanaan'),
        waktu_pelaksanaan: document.getElementById('waktu_pelaksanaan'),
        biaya: document.getElementById('biaya'),
        komoditi: document.getElementById('komoditi'),
        nama_perusahaan_induk: document.getElementById('nama_perusahaan_induk'),
        tanggal_terbit_sertifikat_induk: document.getElementById('tanggal_terbit_sertifikat_induk'),
        laboratorium: document.getElementById('laboratorium'),
        ketua_tim: document.getElementById('ketua_tim')
    };

    const previews = {
        nomor: document.getElementById('preview-nomor'),
        tanggal: document.getElementById('preview-tanggal'),
        perihal: document.getElementById('preview-perihal'),
        nama_perusahaan: document.getElementById('preview-nama-perusahaan'),
        alamat_perusahaan: document.getElementById('preview-alamat-perusahaan'),
        kota_provinsi: document.getElementById('preview-kota-provinsi'),
        tanggal_sertifikat: document.getElementById('preview-tanggal-sertifikat'),
        tanggal_pelaksanaan: document.getElementById('preview-tanggal-pelaksanaan'),
        waktu_pelaksanaan: document.getElementById('preview-waktu'),
        biaya: document.getElementById('preview-biaya'),
        komoditi: document.getElementById('preview-komoditi'),
        laboratorium: document.getElementById('preview-laboratorium'),
        ketua_tim: document.getElementById('preview-ketua-tim')
    };

    const survailenKe = urlParams.get('survailen') || '1';

    // Set dynamic perihal for preview only
    let defaultPerihal = 'Pemberitahuan Survailen ke-' + survailenKe;
    if (jenis === 'pelaksanaan') {
        defaultPerihal = 'Pelaksanaan Survailen ke-' + survailenKe;
    } else if (jenis === 'teguran' || jenis === 'teguran1') {
        defaultPerihal = 'Teguran 1';
    } else if (jenis === 'teguran2') {
        defaultPerihal = 'Teguran 2';
    } else if (jenis === 'pembekuan' || jenis === 'pembekuan1') {
        defaultPerihal = 'Pembekuan ke-1 SPPT SNI';
    } else if (jenis === 'pembekuan2') {
        defaultPerihal = 'Pembekuan ke-2 SPPT SNI';
    }

    if (previews.perihal) {
        previews.perihal.textContent = defaultPerihal;
    }

    // Calendar formatting for Hari/Tanggal Pelaksanaan (Start/End Date range)
    const mulaiInput = document.getElementById('mulai_pelaksanaan');
    const selesaiInput = document.getElementById('selesai_pelaksanaan');

    function formatIndonesianDateRange(startDateVal, endDateVal) {
        if (!startDateVal) return '';

        const start = new Date(startDateVal);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const monthsIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const startDay = days[start.getDay()];
        const startDateNum = start.getDate();
        const startMonth = monthsIndo[start.getMonth()];
        const startYear = start.getFullYear();

        if (!endDateVal || startDateVal === endDateVal) {
            return `${startDay}, ${startDateNum} ${startMonth} ${startYear}`;
        }

        const end = new Date(endDateVal);
        const endDay = days[end.getDay()];
        const endDateNum = end.getDate();
        const endMonth = monthsIndo[end.getMonth()];
        const endYear = end.getFullYear();

        if (startYear !== endYear) {
            return `${startDay} - ${endDay}, ${startDateNum} ${startMonth} ${startYear} - ${endDateNum} ${endMonth} ${endYear}`;
        }

        if (startMonth !== endMonth) {
            return `${startDay} - ${endDay}, ${startDateNum} ${startMonth} - ${endDateNum} ${endMonth} ${startYear}`;
        }

        return `${startDay} - ${endDay}, ${startDateNum} - ${endDateNum} ${startMonth} ${startYear}`;
    }

    function updateTanggalPelaksanaanString() {
        if (mulaiInput.value) {
            const formatted = formatIndonesianDateRange(mulaiInput.value, selesaiInput.value);
            inputs.tanggal_pelaksanaan.value = formatted;
            updatePreview();
        }
    }

    if (mulaiInput && selesaiInput) {
        mulaiInput.addEventListener('change', updateTanggalPelaksanaanString);
        selesaiInput.addEventListener('change', updateTanggalPelaksanaanString);
    }

    // Time formatting for Waktu Pelaksanaan (Jam Mulai picker)
    const jamMulaiInput = document.getElementById('jam_mulai');
    function updateWaktuPelaksanaanString() {
        if (jamMulaiInput.value) {
            const parts = jamMulaiInput.value.split(':');
            const formattedTime = `${parts[0]}.${parts[1]} s/d selesai`;
            inputs.waktu_pelaksanaan.value = formattedTime;
            updatePreview();
        }
    }
    if (jamMulaiInput) {
        jamMulaiInput.addEventListener('input', updateWaktuPelaksanaanString);
        jamMulaiInput.addEventListener('change', updateWaktuPelaksanaanString);
        // Ensure it's initialized correctly on load (overrides browser cache/faker)
        updateWaktuPelaksanaanString();
    }

    // Select auto-fill
    const selectSertifikasi = document.getElementById('select_sertifikasi');
    if (selectSertifikasi) {
        selectSertifikasi.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const isAutoFilled = this.value !== '';
            
            const autoFillInputs = [
                inputs.nama_perusahaan,
                inputs.alamat_perusahaan,
                inputs.komoditi,
                inputs.tanggal_sertifikat
            ];

            autoFillInputs.forEach(input => {
                if (input) {
                    input.readOnly = isAutoFilled;
                    if (isAutoFilled) {
                        input.classList.add('bg-gray-100', 'cursor-not-allowed');
                        input.classList.remove('bg-gray-50', 'bg-white');
                        if (input.type === 'date') {
                            input.style.pointerEvents = 'none';
                        }
                    } else {
                        input.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        if (input.id === 'tanggal_sertifikat') {
                            input.classList.add('bg-white');
                        } else {
                            input.classList.add('bg-gray-50');
                        }
                        if (input.type === 'date') {
                            input.style.pointerEvents = 'auto';
                        }
                    }
                }
            });

            if (this.value) {
                const nama = option.dataset.nama || '';
                const alamat = option.dataset.alamat || '';
                const kota = option.dataset.kota || '';
                const komoditi = option.dataset.komoditi || '';
                const tanggal = option.dataset.tanggal || '';
                const merek = option.dataset.merek || '';
                const kategori = option.dataset.kategori || '';

                const isPembekuan = jenis && jenis.includes('pembekuan');
                
                if (kategori.toLowerCase() === 'luar negeri' && !isPembekuan) {
                    document.querySelectorAll('.ln-only').forEach(el => el.classList.remove('hidden'));
                    document.querySelectorAll('.dn-only').forEach(el => el.classList.add('hidden'));
                } else {
                    document.querySelectorAll('.ln-only').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.dn-only').forEach(el => el.classList.remove('hidden'));
                }

                inputs.nama_perusahaan.value = nama;
                inputs.alamat_perusahaan.value = alamat;
                inputs.kota_provinsi.value = kota;
                inputs.komoditi.value = komoditi;
                inputs.tanggal_sertifikat.value = tanggal;
                if (inputs.merek) inputs.merek.value = merek;

                if (document.getElementById('sertifikasi_id')) {
                    document.getElementById('sertifikasi_id').value = this.value;
                }

                updatePreview();
            } else {
                inputs.nama_perusahaan.value = '';
                inputs.alamat_perusahaan.value = '';
                inputs.kota_provinsi.value = '';
                inputs.komoditi.value = '';
                inputs.tanggal_sertifikat.value = '';
                if (inputs.merek) inputs.merek.value = '';
                
                document.querySelectorAll('.ln-only').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.dn-only').forEach(el => el.classList.remove('hidden'));

                if (document.getElementById('sertifikasi_id')) {
                    document.getElementById('sertifikasi_id').value = '';
                }

                updatePreview();
            }
        });
    }

    const sertifikasiId = urlParams.get('sertifikasi_id');
    if (sertifikasiId && selectSertifikasi) {
        selectSertifikasi.value = sertifikasiId;
        // Trigger change event to auto-fill the rest of the form
        selectSertifikasi.dispatchEvent(new Event('change'));
    }

    const urlTanggalSurat = urlParams.get('tanggal_surat');
    if (urlTanggalSurat && inputs.tanggal) {
        inputs.tanggal.value = urlTanggalSurat;
        updatePreview();
    }

    function updatePreview() {
        if (inputs.tanggal.value) {
            const dateVal = new Date(inputs.tanggal.value);
            const romanMonths = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
            const romanMonth = romanMonths[dateVal.getMonth()];
            const year = dateVal.getFullYear();
            previews.nomor.textContent = "         /BSPJI-Surabaya/MS/" + romanMonth + "/" + year + "                ";
        } else {
            previews.nomor.textContent = "         /BSPJI-Surabaya/MS/[Bulan]/[Tahun]                ";
        }
        
        if (inputs.tanggal.value) {
            const date = new Date(inputs.tanggal.value);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            previews.tanggal.textContent = date.toLocaleDateString('id-ID', options);
        } else {
            previews.tanggal.textContent = '[Tanggal Bulan Tahun]';
        }
        
        previews.nama_perusahaan.textContent = inputs.nama_perusahaan.value || '[Nama Perusahaan]';
        let nama2 = document.getElementById('preview-nama-perusahaan-2');
        if (nama2) nama2.textContent = inputs.nama_perusahaan.value || '[Nama Perusahaan]';

        previews.alamat_perusahaan.textContent = inputs.alamat_perusahaan.value || '[Alamat Perusahaan]';
        previews.kota_provinsi.textContent = inputs.kota_provinsi.value || '[Kota / Provinsi]';
        
        let namaLn1 = document.getElementById('preview-nama-perusahaan-ln-1');
        if (namaLn1) namaLn1.textContent = (inputs.nama_perusahaan_induk && inputs.nama_perusahaan_induk.value) ? inputs.nama_perusahaan_induk.value : '[Nama Perusahaan Induk]';
        let namaLn2 = document.getElementById('preview-nama-perusahaan-ln-2');
        if (namaLn2) namaLn2.textContent = (inputs.nama_perusahaan_induk && inputs.nama_perusahaan_induk.value) ? inputs.nama_perusahaan_induk.value : '[Nama Perusahaan Induk]';
        
        if (previews.tanggal_sertifikat) {
            if (inputs.tanggal_sertifikat.value) {
                const dSert = new Date(inputs.tanggal_sertifikat.value);
                const optSert = { year: 'numeric', month: 'long', day: 'numeric' };
                previews.tanggal_sertifikat.textContent = dSert.toLocaleDateString('id-ID', optSert);
            } else {
                previews.tanggal_sertifikat.textContent = '[Tanggal Sertifikat]';
            }
        }

        let tglSertLn2 = document.getElementById('preview-tanggal-sertifikat-ln-2');
        if (tglSertLn2) {
            if (inputs.tanggal_terbit_sertifikat_induk && inputs.tanggal_terbit_sertifikat_induk.value) {
                const dSertInduk = new Date(inputs.tanggal_terbit_sertifikat_induk.value);
                const optSert = { year: 'numeric', month: 'long', day: 'numeric' };
                tglSertLn2.textContent = dSertInduk.toLocaleDateString('id-ID', optSert);
            } else {
                tglSertLn2.textContent = '[Tanggal Sertifikat Induk]';
            }
        }

        let nomorRujukan = document.getElementById('preview-nomor-surat-rujukan');
        if (nomorRujukan) nomorRujukan.textContent = (inputs.nomor_surat_rujukan && inputs.nomor_surat_rujukan.value) ? inputs.nomor_surat_rujukan.value : '[Nomor Surat Survailen]';
        let nomorRujukan2 = document.getElementById('preview-nomor-surat-rujukan-2');
        if (nomorRujukan2) nomorRujukan2.textContent = (inputs.nomor_surat_rujukan && inputs.nomor_surat_rujukan.value) ? inputs.nomor_surat_rujukan.value : '[Nomor Surat Sebelumnya]';
        let nomorRujukan3 = document.getElementById('preview-nomor-surat-rujukan-3');
        if (nomorRujukan3) nomorRujukan3.textContent = (inputs.nomor_surat_rujukan && inputs.nomor_surat_rujukan.value) ? inputs.nomor_surat_rujukan.value : '[Nomor Surat Sebelumnya]';

        let nomorSpptSni2 = document.getElementById('preview-nomor-sppt-sni-2');
        if (nomorSpptSni2) nomorSpptSni2.textContent = (inputs.nomor_sppt_sni && inputs.nomor_sppt_sni.value) ? inputs.nomor_sppt_sni.value : '[Nomor SPPT SNI]';
        let nomorSpptSni3 = document.getElementById('preview-nomor-sppt-sni-3');
        if (nomorSpptSni3) nomorSpptSni3.textContent = (inputs.nomor_sppt_sni && inputs.nomor_sppt_sni.value) ? inputs.nomor_sppt_sni.value : '[Nomor SPPT SNI]';

        let merek2 = document.getElementById('preview-merek-2');
        if (merek2) merek2.textContent = (inputs.merek && inputs.merek.value) ? inputs.merek.value : '[Merek]';
        let merek3 = document.getElementById('preview-merek-3');
        if (merek3) merek3.textContent = (inputs.merek && inputs.merek.value) ? inputs.merek.value : '[Merek]';

        if (previews.tanggal_pelaksanaan) previews.tanggal_pelaksanaan.textContent = inputs.tanggal_pelaksanaan.value || '[Tanggal Pelaksanaan]';
        if (previews.waktu_pelaksanaan) previews.waktu_pelaksanaan.textContent = inputs.waktu_pelaksanaan.value || '09.00 s/d selesai';
        if (previews.biaya) previews.biaya.textContent = inputs.biaya.value || '[Biaya]';
        
        let numBiaya = inputs.biaya.value.replace(/[^0-9]/g, '');
        let previewTerbilang = document.getElementById('preview-terbilang-biaya');
        if (previewTerbilang) {
            if (numBiaya) {
                let terbilangText = terbilang(numBiaya).trim() + " Rupiah";
                previewTerbilang.textContent = toTitleCase(terbilangText);
            } else {
                previewTerbilang.textContent = "Nol Rupiah";
            }
        }

        if (previews.komoditi) previews.komoditi.textContent = inputs.komoditi.value || '[Komoditi]';
        let komoditi2 = document.getElementById('preview-komoditi-2');
        if (komoditi2) komoditi2.textContent = inputs.komoditi.value || '[Komoditi]';
        let komoditi3 = document.getElementById('preview-komoditi-3');
        if (komoditi3) komoditi3.textContent = inputs.komoditi.value || '[Komoditi]';

        if (previews.laboratorium) previews.laboratorium.textContent = inputs.laboratorium.value || '[Laboratorium]';
        let lab3 = document.getElementById('preview-laboratorium-3');
        if (lab3) lab3.textContent = inputs.laboratorium.value || '[Laboratorium]';
        
        if (previews.ketua_tim) previews.ketua_tim.textContent = inputs.ketua_tim.value || '[Ketua Tim]';
    }

    function terbilang(angka) {
        angka = Math.abs(parseInt(angka, 10));
        if (isNaN(angka)) return "";
        
        var baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        var hasil = "";
        
        if (angka < 12) {
            hasil = " " + baca[angka];
        } else if (angka < 20) {
            hasil = terbilang(angka - 10) + " Belas";
        } else if (angka < 100) {
            hasil = terbilang(Math.floor(angka / 10)) + " Puluh" + terbilang(angka % 10);
        } else if (angka < 200) {
            hasil = " Seratus" + terbilang(angka - 100);
        } else if (angka < 1000) {
            hasil = terbilang(Math.floor(angka / 100)) + " Ratus" + terbilang(angka % 100);
        } else if (angka < 2000) {
            hasil = " Seribu" + terbilang(angka - 1000);
        } else if (angka < 1000000) {
            hasil = terbilang(Math.floor(angka / 1000)) + " Ribu" + terbilang(angka % 1000);
        } else if (angka < 1000000000) {
            hasil = terbilang(Math.floor(angka / 1000000)) + " Juta" + terbilang(angka % 1000000);
        } else if (angka < 1000000000000) {
            hasil = terbilang(Math.floor(angka / 1000000000)) + " Miliar" + terbilang(angka % 1000000000);
        }
        return hasil;
    }
    
    function toTitleCase(str) {
        return str.replace(
            /\w\S*/g,
            function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }

    // Add event listeners for inputs
    Object.values(inputs).forEach(input => {
        if (input) {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        }
    });

    // Initialize preview on page load
    updatePreview();

    // Zoom & Pan Logic
    let currentZoom = 1;
    const paper = document.getElementById('a4-paper');
    const zoomLevelText = document.getElementById('zoom-level');
    const zoomSlider = document.getElementById('zoom-slider');
    const previewContainer = document.getElementById('preview-container');

    function applyZoom() {
        zoomLevelText.textContent = Math.round(currentZoom * 100) + '%';
        if (zoomSlider) zoomSlider.value = currentZoom;
        paper.style.width = `calc(210mm * ${currentZoom})`;
        paper.style.height = `auto`; // Managed by aspect-ratio
    }

    document.getElementById('zoom-in').addEventListener('click', () => {
        if (currentZoom < 2.5) {
            currentZoom = Math.min(2.5, currentZoom + 0.1);
            applyZoom();
        }
    });

    document.getElementById('zoom-out').addEventListener('click', () => {
        if (currentZoom > 0.5) {
            currentZoom = Math.max(0.5, currentZoom - 0.1);
            applyZoom();
        }
    });

    if (zoomSlider) {
        zoomSlider.addEventListener('input', (e) => {
            currentZoom = parseFloat(e.target.value);
            applyZoom();
        });
    }

    // Panning (Drag to scroll) Logic
    let isDown = false;
    let startX, startY, scrollLeft, scrollTop;

    if (previewContainer) {
        previewContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            previewContainer.classList.add('cursor-grabbing');
            previewContainer.classList.remove('cursor-grab');
            startX = e.pageX - previewContainer.offsetLeft;
            startY = e.pageY - previewContainer.offsetTop;
            scrollLeft = previewContainer.scrollLeft;
            scrollTop = previewContainer.scrollTop;
        });

        previewContainer.addEventListener('mouseleave', () => {
            isDown = false;
            previewContainer.classList.remove('cursor-grabbing');
            previewContainer.classList.add('cursor-grab');
        });

        previewContainer.addEventListener('mouseup', () => {
            isDown = false;
            previewContainer.classList.remove('cursor-grabbing');
            previewContainer.classList.add('cursor-grab');
        });

        previewContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - previewContainer.offsetLeft;
            const y = e.pageY - previewContainer.offsetTop;
            const walkX = (x - startX) * 1.5; // Drag speed multiplier
            const walkY = (y - startY) * 1.5;
            previewContainer.scrollLeft = scrollLeft - walkX;
            previewContainer.scrollTop = scrollTop - walkY;
        });
    }

    // Handle form reset
    document.getElementById('persuratan-form').addEventListener('reset', function() {
        setTimeout(updatePreview, 10);
        currentZoom = 1;
        applyZoom();
    });
});
