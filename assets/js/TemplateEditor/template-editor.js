import Vue from 'vue';
import VueKonva from 'vue-konva';

import Editor from "./Components/Editor";

Vue.use(VueKonva);

new Vue({
    el: '#template-editor',
    components: { Editor }
});