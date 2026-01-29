import './bootstrap';

import { initQuillEditors } from './components/quill';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

document.addEventListener('DOMContentLoaded', initQuillEditors);
Alpine.plugin(collapse)
window.Alpine = Alpine;

Alpine.start();

