import Vue from 'vue';
import VueKonva from 'vue-konva';


import Element from "../TemplateEditor/Components/Element";

Vue.use(VueKonva);

const creationPreviewContainers = document.querySelectorAll(".creation-preview");

creationPreviewContainers.forEach(function(c) {
    new Vue({
        el: c,
        data: {
            configKonva: {
                width: 800,
                height: 800,
            },
            elements: JSON.parse(c.dataset.konvaElements).map((el) => {
                el.config.draggable = false;
                return el;
            })
        },
        components: { KonvaElement: Element }
    });
});