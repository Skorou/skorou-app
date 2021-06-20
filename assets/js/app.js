/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import '../css/app.scss';
import './bootstrap.js';

// import bsCustomFileInput from 'bs-custom-file-input';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
import Vue from 'vue';
import VueKonva from 'vue-konva';

import Editor from "./Components/Editor";

Vue.use(VueKonva);

new Vue({
    el: '#vue-playground',
    components: { Editor }
});
