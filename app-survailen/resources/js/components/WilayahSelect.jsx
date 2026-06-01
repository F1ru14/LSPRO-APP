import React, { useState, useEffect } from 'react';
import Select from 'react-select';

export default function WilayahSelect({ 
    provinsiOptionsData, 
    initialProvinsiId, 
    initialProvinsiName, 
    initialKotaId, 
    initialKotaName 
}) {
    // Parse initial provinces
    const parsedProvinces = JSON.parse(provinsiOptionsData || '[]');
    const defaultProvinsiOptions = parsedProvinces.map(opt => ({
        value: opt.id_provinsi,
        label: opt.provinsi
    }));

    const [provinsiOptions, setProvinsiOptions] = useState(defaultProvinsiOptions);
    const [kotaOptions, setKotaOptions] = useState([]);

    // Initialize selected
    let initialProvLabel = initialProvinsiName;
    if (initialProvinsiId && !initialProvLabel) {
        const found = defaultProvinsiOptions.find(o => o.value == initialProvinsiId);
        initialProvLabel = found ? found.label : initialProvinsiId;
    }
    const initialProv = initialProvinsiId ? { value: initialProvinsiId, label: initialProvLabel } : null;

    let initialKotaLabel = initialKotaName;
    if (initialKotaId && !initialKotaLabel) {
        initialKotaLabel = initialKotaId; // Will be updated if numeric and found later, but good for string
    }
    const initialKota = initialKotaId ? { value: initialKotaId, label: initialKotaLabel } : null;

    const [selectedProvinsi, setSelectedProvinsi] = useState(initialProv);
    const [selectedKota, setSelectedKota] = useState(initialKota);
    const [isKotaDisabled, setIsKotaDisabled] = useState(!initialProv);

    // If there is an initial province, fetch its cities
    useEffect(() => {
        if (selectedProvinsi && !isNaN(selectedProvinsi.value)) {
            fetchCities(selectedProvinsi.value, true);
        } else if (selectedProvinsi) {
            setIsKotaDisabled(false);
            if (initialKota) {
                setKotaOptions([initialKota]);
            }
        }
    }, []);

    const fetchCities = (provId, isInitialLoad = false) => {
        setIsKotaDisabled(true);
        fetch(`/api/kota/${provId}`)
            .then(res => res.json())
            .then(data => {
                const cities = data.map(city => ({
                    value: city.id_kota,
                    label: city.kota
                }));
                
                if (isInitialLoad && initialKota) {
                     const exists = cities.some(c => c.value == initialKota.value);
                     if (!exists) {
                         cities.push({ value: initialKota.value, label: initialKota.label });
                     }
                }
                
                setKotaOptions(cities);
                setIsKotaDisabled(false);
            })
            .catch(err => console.error(err));
    };

    const handleProvinsiChange = (newValue) => {
        setSelectedProvinsi(newValue);
        setSelectedKota(null);
        setKotaOptions([]);
        
        if (newValue && !isNaN(newValue.value)) {
            fetchCities(newValue.value);
        } else if (newValue) {
            setIsKotaDisabled(false);
        } else {
            setIsKotaDisabled(true);
        }
    };

    const handleKotaChange = (newValue) => {
        setSelectedKota(newValue);
    };

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label className="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Provinsi <span className="text-red-500">*</span></label>
                <input type="hidden" name="id_provinsi" value={selectedProvinsi ? selectedProvinsi.value : ''} required />
                <Select
                    isClearable
                    onChange={handleProvinsiChange}
                    options={provinsiOptions}
                    value={selectedProvinsi}
                    placeholder="-- Pilih Provinsi --"
                    classNamePrefix="react-select"
                />
            </div>
            <div>
                <label className="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Kota / Kabupaten <span className="text-red-500">*</span></label>
                <input type="hidden" name="id_kota" value={selectedKota ? selectedKota.value : ''} required />
                <Select
                    isDisabled={isKotaDisabled}
                    isClearable
                    onChange={handleKotaChange}
                    options={kotaOptions}
                    value={selectedKota}
                    placeholder="-- Pilih Kota --"
                    classNamePrefix="react-select"
                />
            </div>
        </div>
    );
}
