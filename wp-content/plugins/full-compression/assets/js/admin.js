document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('fc_enable_compression');
    const formatSelect = document.getElementById('fc_image_format');

    function toggleFormatSelect() {
        if(!checkbox || !formatSelect) return;
        formatSelect.disabled = !checkbox.checked;
    }

    if(checkbox && formatSelect) {
        checkbox.addEventListener('change', toggleFormatSelect);
        toggleFormatSelect();
    }
});