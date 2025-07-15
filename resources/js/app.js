import './bootstrap';
import Alpine from 'alpinejs';
import * as bootstrap from 'bootstrap';

// Make Bootstrap available globally
window.bootstrap = bootstrap;

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start(); 