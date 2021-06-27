import Vue from 'vue';
import VueKonva from 'vue-konva';
import Vuex from 'vuex';

import Editor from "./Components/Editor";
Vue.use(VueKonva);
Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        elements: window['INITIAL_CONFIG'],
        selectedElements: [],
    },
    mutations: {
        setElements (state, elements) {
            state.elements = elements;
        },
        addElement (state, element) {
            const id = state.elements.length + 1;
            element = {...element, id: state.elements.length + 1}
            element.config.id = id.toString();
            state.elements = state.elements.concat([element]);
        },
        removeSelected (state, elements) {
            const elements_id = state.selectedElements.map(elem => elem.id);
            state.elements = state.elements.filter(elem => elements_id.indexOf(elem.id.toString()) !== -1)
            state.selectedElements = []
        },
        setSelectedElements (state, ids) {
            state.selectedElements = state.elements.filter(element => ids.indexOf(element.id.toString()) !== -1);
        },
        updateConfig (state, new_config) {
            state.selectedElements = state.selectedElements.map((el) => {
                const elementToUpdate = state.elements.find(elem => elem.id === el.id);
                Object.assign(elementToUpdate.config, new_config);
                Object.assign(el.config, new_config);
                return el;
            });
        }
    },
    getters: {
        getElementById: (state) => (id) =>  {
            return state.elements.find(elem => elem.id === id);
        },
        getElementsByIds: (state) => (ids) => {
            return state.elements.filter(elem => ids.indexOf(elem.id) !== -1);
        },
        getSelectedElementsIds: (state) => {
            return state.selectedElements.map(elem => elem.id);
        }
    }
})

new Vue({
    el: '#template-editor',
    store,
    components: { Editor }
});