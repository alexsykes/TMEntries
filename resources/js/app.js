import './bootstrap';
// import 'tinymce/tinymce';
// import 'tinymce/skins/ui/oxide/skin.min.css';
// import 'tinymce/skins/content/default/content.min.css';
// import 'tinymce/skins/content/default/content.css';
// import 'tinymce/icons/default/icons';
// import 'tinymce/themes/silver/theme';
// import 'tinymce/models/dom/model';
// import 'tinymce/plugins/lists';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// window.addEventListener('DOMContentLoaded', () => {
//     tinymce.init({
//         selector: 'textarea',
//         placeholder: 'Type here...',
//         license_key: 'gpl',
//         plugins: ' table lists',
//         toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
//     });
// });