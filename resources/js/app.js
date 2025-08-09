import './bootstrap';
import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';
import Chart from 'chart.js/auto';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Make Chart.js available globally
window.Chart = Chart;

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Import specific modules
import './encuestas';
import './dashboard';
import './date-validation';
import './auth';
import './preguntas'; 