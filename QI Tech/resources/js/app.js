import './bootstrap';

 // Added: Actual Bootstrap JavaScript dependency
 import $ from "jquery";
import 'bootstrap';
import '@popperjs/core';
import 'tinymce/tinymce';
import 'tinymce/plugins/quickbars'; // Quickbars plugin
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';
import 'tinymce/icons/default/icons';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/skins/ui/oxide/content.inline.min.css';
import 'tinymce/plugins/link'



// Import the TinyMCE skin (UI appearance)

import Alpine from 'alpinejs';
import DataTable from 'datatables.net-dt';
import 'datatables.net-plugins/sorting/absolute.mjs';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import moment from 'moment';

// window.$ = $;
window.Alpine = Alpine;
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;
window.interactionPlugin = interactionPlugin;
window.DataTable = DataTable;
window.moment = moment;
Alpine.start();
