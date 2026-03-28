console.log('Script loaded, testing basic functionality...');

// Simple test function
function testBasicFunctionality() {
    console.log('Testing basic functionality...');
    alert('JavaScript is working!');

    // Test DOM access
    const testElement = document.getElementById('fecha_inicio');
    if (testElement) {
        console.log('DOM access works');
        alert('DOM access works');
    } else {
        console.log('DOM access failed');
        alert('DOM access failed');
    }
}

// Call test immediately
testBasicFunctionality();

// Use vanilla JavaScript instead of jQuery
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing reports...');
    alert('DOM Content Loaded event fired');
    initializeReports();
});

function initializeReports() {
    console.log('Initializing reports system...');
    alert('Initializing reports system...');

    try {
        // Set default dates first
        setPeriodo('mesAnterior');

        // Initialize UI components
        initUI();

        // Setup event handlers
        setupEvents();

        // Initialize charts
        initCharts();

        console.log('Reports system initialized successfully');
        alert('Reports system initialized successfully');

    } catch (error) {
        console.error('Error initializing reports:', error);
        alert('Error initializing reports: ' + error.message);
    }
}

function initUI() {
    console.log('Initializing UI components...');

    // Collapse functionality
    const collapse = document.getElementById('collapseReportes');
    const icon = document.querySelector('[data-target="#collapseReportes"] .fas');

    if (collapse && icon) {
        collapse.addEventListener('show.bs.collapse', () => {
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        });
        collapse.addEventListener('hide.bs.collapse', () => {
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        });
    }

    // Initialize Select2 for main selects
    $('#tipo_reporte, #formato').select2({
        theme: 'bootstrap-5',
        width: '100%',
        minimumResultsForSearch: 0
    });

    console.log('UI components initialized');
}

function setupEvents() {
    console.log('Setting up event handlers...');

    // Report type change handler - use direct event listener
    const tipoReporteSelect = document.getElementById('tipo_reporte');
    if (tipoReporteSelect) {
        tipoReporteSelect.addEventListener('change', function() {
            console.log('Tipo de reporte changed:', this.value);
            handleReportTypeChange();
        });
    }

    // Dynamic filters update on date change - use direct event listeners
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    if (fechaInicioInput) {
        fechaInicioInput.addEventListener('change', function() {
            console.log('Fecha inicio changed');
            loadDynamicFilters();
        });
    }

    if (fechaFinInput) {
        fechaFinInput.addEventListener('change', function() {
            console.log('Fecha fin changed');
            loadDynamicFilters();
        });
    }

    // Quick period buttons - use direct event listeners
    const periodoButtons = document.querySelectorAll('.periodo-btn');
    periodoButtons.forEach(button => {
        const periodo = button.getAttribute('data-periodo');
