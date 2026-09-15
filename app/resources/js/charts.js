// Separate Vite entry so Chart.js only ships on pages that actually render
// a chart (dashboards with :charts="true"), not the whole app bundle.
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
window.Chart = Chart;
