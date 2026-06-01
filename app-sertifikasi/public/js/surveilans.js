document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('pengawasan_selector');
    const sections = document.querySelectorAll('.pengawasan-section');

    selector.addEventListener('change', function() {
        const selectedValue = this.value;
        
        sections.forEach(section => {
            if (section.id === 'pengawasan_' + selectedValue) {
                section.classList.remove('hidden');
                section.style.opacity = '0';
                setTimeout(() => {
                    section.style.opacity = '1';
                }, 50);
            } else {
                section.classList.add('hidden');
            }
        });
    });

    // Dropdown Logic
    const dropdownContainers = document.querySelectorAll('.dropdown-container');
    dropdownContainers.forEach(container => {
        const trigger = container.querySelector('.dropdown-trigger');
        const menu = container.querySelector('.dropdown-menu');
        
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
    });

    // Autofill logic based on No Referensi
    const refInput = document.getElementById('no_referensi_input');
    
    function fetchSertifikasiData(noRef) {
        if (!noRef) {
            document.getElementById('nama_perusahaan').value = '';
            document.getElementById('komoditi').value = '';
            document.getElementById('no_sertifikat_sni').value = '';
            document.getElementById('tanggal_sertifikat_sni').value = '';
            document.getElementById('auditor_sertifikasi').value = '';
            document.getElementById('ppc_sertifikasi').value = '';
            document.getElementById('laboratorium_pengujian').value = '';
            document.getElementById('status_sertifikasi').value = '';

            for(let i=1; i<=4; i++) {
                const fields = ['tgl_surat_pengawasan_', 'tgl_pelaksanaan_pengawasan_', 'tgl_surat_teguran_1_', 'tgl_surat_teguran_2_', 'tgl_pembekuan_'];
                fields.forEach(f => {
                    let el = document.getElementsByName(f + i)[0];
                    if (el) el.value = '';
                });

                window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'auditor_pelaksanaan_' + i, value: '' } }));
                window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'ppc_pelaksanaan_' + i, value: '' } }));
                window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'laboratorium_pengujian_' + i, value: '' } }));
            }
            
            // Remove sertifikasi_id from links
            document.querySelectorAll('.dropdown-menu a').forEach(a => {
                try {
                    let url = new URL(a.href);
                    url.searchParams.delete('sertifikasi_id');
                    a.href = url.toString();
                } catch(e) {}
            });
            return;
        }

        fetch('/api/sertifikasi/' + encodeURIComponent(noRef))
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    const data = result.data;
                    document.getElementById('nama_perusahaan').value = data.nama_perusahaan || '-';
                    document.getElementById('komoditi').value = data.komoditi || '-';
                    document.getElementById('no_sertifikat_sni').value = data.no_sni || '-';
                    document.getElementById('tanggal_sertifikat_sni').value = data.tgl_sertifikasi || '-';
                    document.getElementById('auditor_sertifikasi').value = data.auditor_sertifikasi || '-';
                    document.getElementById('ppc_sertifikasi').value = data.ppc_sertifikasi || '-';
                    document.getElementById('laboratorium_pengujian').value = data.laboratorium_pengujian || '-';
                    document.getElementById('status_sertifikasi').value = data.status_sertifikasi || '-';

                    for(let i=1; i<=4; i++) {
                        const fields = ['tgl_surat_pengawasan_', 'tgl_pelaksanaan_pengawasan_', 'tgl_surat_teguran_1_', 'tgl_surat_teguran_2_', 'tgl_pembekuan_1_', 'tgl_pembekuan_2_'];
                        fields.forEach(f => {
                            let el = document.getElementsByName(f + i)[0];
                            if (el) el.value = data[f + i] || '';
                        });

                        window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'auditor_pelaksanaan_' + i, value: data['auditor_pelaksanaan_' + i] || '' } }));
                        window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'ppc_pelaksanaan_' + i, value: data['ppc_pelaksanaan_' + i] || '' } }));
                        window.dispatchEvent(new CustomEvent('update-react-select', { detail: { name: 'laboratorium_pengujian_' + i, value: data['laboratorium_pengujian_' + i] || '' } }));
                    }
                    
                    const maxSurv = data.max_surveilans || 3;
                    const opt4 = document.getElementById('opt_pengawasan_4');
                    if (opt4) {
                        if (maxSurv >= 4) {
                            opt4.classList.remove('hidden');
                            opt4.hidden = false;
                            opt4.disabled = false;
                        } else {
                            opt4.classList.add('hidden');
                            opt4.hidden = true;
                            opt4.disabled = true;
                            if (document.getElementById('pengawasan_selector').value === '4') {
                                document.getElementById('pengawasan_selector').value = '1';
                                document.getElementById('pengawasan_selector').dispatchEvent(new Event('change'));
                            }
                        }
                    }
                    
                    updateSelectorState();

                    // Update cetak surat links
                    document.querySelectorAll('.dropdown-menu a').forEach(a => {
                        try {
                            let url = new URL(a.href);
                            url.searchParams.set('sertifikasi_id', data.id_sertifikasi);
                            a.href = url.toString();
                        } catch(e) {}
                    });
                }
            })
            .catch(err => console.error('Error fetching data:', err));
    }

    function updateSelectorState() {
        const tgl1 = document.getElementsByName('tgl_pelaksanaan_pengawasan_1')[0]?.value;
        const tgl2 = document.getElementsByName('tgl_pelaksanaan_pengawasan_2')[0]?.value;
        const tgl3 = document.getElementsByName('tgl_pelaksanaan_pengawasan_3')[0]?.value;

        const sel = document.getElementById('pengawasan_selector');
        if (!sel) return;

        const opt2 = sel.querySelector('option[value="2"]');
        if (opt2) {
            if (!tgl1) {
                opt2.disabled = true;
                opt2.textContent = 'Pengawasan Berkala 2';
                if (sel.value === '2') {
                    sel.value = '1';
                    sel.dispatchEvent(new Event('change'));
                }
            } else {
                opt2.disabled = false;
                opt2.textContent = 'Pengawasan Berkala 2';
            }
        }

        const opt3 = sel.querySelector('option[value="3"]');
        if (opt3) {
            if (!tgl2) {
                opt3.disabled = true;
                opt3.textContent = 'Pengawasan Berkala 3';
                if (sel.value === '3') {
                    sel.value = tgl1 ? '2' : '1';
                    sel.dispatchEvent(new Event('change'));
                }
            } else {
                opt3.disabled = false;
                opt3.textContent = 'Pengawasan Berkala 3';
            }
        }

        const opt4 = sel.querySelector('option[value="4"]');
        if (opt4) {
            if (!tgl3) {
                opt4.disabled = true;
                opt4.textContent = 'Pengawasan Berkala 4';
                if (sel.value === '4') {
                    sel.value = tgl2 ? '3' : (tgl1 ? '2' : '1');
                    sel.dispatchEvent(new Event('change'));
                }
            } else {
                opt4.disabled = false;
                opt4.textContent = 'Pengawasan Berkala 4';
            }
        }
    }

    // Initialize listeners for date changes
    const inputTgl1 = document.getElementsByName('tgl_pelaksanaan_pengawasan_1')[0];
    if (inputTgl1) {
        inputTgl1.addEventListener('change', updateSelectorState);
        inputTgl1.addEventListener('input', updateSelectorState);
    }

    const inputTgl2 = document.getElementsByName('tgl_pelaksanaan_pengawasan_2')[0];
    if (inputTgl2) {
        inputTgl2.addEventListener('change', updateSelectorState);
        inputTgl2.addEventListener('input', updateSelectorState);
    }

    const inputTgl3 = document.getElementsByName('tgl_pelaksanaan_pengawasan_3')[0];
    if (inputTgl3) {
        inputTgl3.addEventListener('change', updateSelectorState);
        inputTgl3.addEventListener('input', updateSelectorState);
    }

    // Call synchronously on load to lock it before fetch completes
    updateSelectorState();

    if (refInput) {
        refInput.addEventListener('change', function() {
            fetchSertifikasiData(this.value);
        });
        // Automatically fetch if value is pre-filled from URL
        if (refInput.value) {
            fetchSertifikasiData(refInput.value);
        }
    }
});
