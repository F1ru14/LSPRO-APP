import './bootstrap';

import Alpine from 'alpinejs';

import React from 'react';
import { createRoot } from 'react-dom/client';
import AuditorSelect from './components/AuditorSelect';
import WilayahSelect from './components/WilayahSelect';

window.Alpine = Alpine;
Alpine.start();

// Mount React components
document.addEventListener('DOMContentLoaded', () => {
    // Find all container divs for the react select
    const auditorSelectContainers = document.querySelectorAll('.react-auditor-select-container');
    
    auditorSelectContainers.forEach(container => {
        const root = createRoot(container);
        const isMulti = container.dataset.isMulti === 'true';
        // By default, assume it's creatable unless explicitly set to 'false'
        const isCreatable = container.dataset.isCreatable !== 'false';
        
        root.render(
            <AuditorSelect 
                name={container.dataset.name}
                initialValue={container.dataset.value}
                optionsData={container.dataset.options}
                storeUrl={container.dataset.storeUrl}
                placeholder={container.dataset.placeholder}
                isMulti={isMulti}
                isCreatable={isCreatable}
            />
        );
    });

    const wilayahSelectContainers = document.querySelectorAll('.react-wilayah-select-container');
    wilayahSelectContainers.forEach(container => {
        const root = createRoot(container);
        root.render(
            <WilayahSelect 
                provinsiOptionsData={container.dataset.provinces}
                initialProvinsiId={container.dataset.provId}
                initialProvinsiName={container.dataset.provName}
                initialKotaId={container.dataset.kotaId}
                initialKotaName={container.dataset.kotaName}
            />
        );
    });
});
