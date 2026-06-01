import React, { useState, useEffect } from 'react';
import Select from 'react-select';
import CreatableSelect from 'react-select/creatable';

export default function AuditorSelect({ name, initialValue, optionsData, storeUrl, placeholder, isMulti, isCreatable = true }) {
    const parsedOptions = JSON.parse(optionsData || '[]');
    
    // React Select expects options in the format { value: '...', label: '...' }
    const defaultOptions = parsedOptions.map(opt => ({
        value: opt,
        label: opt
    }));

    let initialSelected = null;
    if (initialValue) {
        if (isMulti) {
            initialSelected = initialValue.split(',').map(item => ({
                value: item.trim(),
                label: item.trim()
            })).filter(item => item.value !== '');
        } else {
            initialSelected = { value: initialValue, label: initialValue };
        }
    }

    const [options, setOptions] = useState(defaultOptions);
    const [value, setValue] = useState(initialSelected);

    const handleChange = (newValue) => {
        setValue(newValue);
    };

    useEffect(() => {
        const handleUpdate = (e) => {
            if (e.detail && e.detail.name === name) {
                const newVal = e.detail.value;
                if (!newVal) {
                    setValue(null);
                } else if (isMulti) {
                    setValue(newVal.split(',').map(item => ({
                        value: item.trim(),
                        label: item.trim()
                    })).filter(item => item.value !== ''));
                } else {
                    setValue({ value: newVal, label: newVal });
                }
            }
        };
        window.addEventListener('update-react-select', handleUpdate);
        return () => window.removeEventListener('update-react-select', handleUpdate);
    }, [name, isMulti]);

    const handleCreate = (inputValue) => {
        const newOption = { value: inputValue, label: inputValue };
        setOptions([...options, newOption]);
        
        if (isMulti) {
            setValue(prev => prev ? [...prev, newOption] : [newOption]);
        } else {
            setValue(newOption);
        }

        // Optionally send the new value to the server
        if (storeUrl) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ [name]: inputValue })
                }).catch(err => console.error('Error saving new auditor:', err));
            }
        }
    };

    let hiddenInputValue = '';
    if (value) {
        if (isMulti) {
            hiddenInputValue = value.map(v => v.value).join(', ');
        } else {
            hiddenInputValue = value.value;
        }
    }

    return (
        <>
            {/* Hidden input to hold the value for Laravel form submission */}
            <input type="hidden" name={name} value={hiddenInputValue} />
            
            {isCreatable ? (
                <CreatableSelect
                    isMulti={isMulti}
                    isClearable
                    onChange={handleChange}
                    onCreateOption={handleCreate}
                    options={options}
                    value={value}
                    placeholder={placeholder || 'Pilih atau ketik nama...'}
                    formatCreateLabel={(inputValue) => `+ Tambah "${inputValue}"`}
                    classNamePrefix="react-select"
                />
            ) : (
                <Select
                    isMulti={isMulti}
                    isClearable
                    onChange={handleChange}
                    options={options}
                    value={value}
                    placeholder={placeholder || 'Pilih atau ketik nama...'}
                    classNamePrefix="react-select"
                />
            )}
        </>
    );
}
