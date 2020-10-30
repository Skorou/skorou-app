<template>
    <v-stage :config="configKonva">
        <v-layer>
            <template v-for="element in elements">
                <v-rect v-if="element.type === 'rect'" :config="element.config"></v-rect>
                <v-circle v-else-if="element.type === 'circle'" :config="element.config"></v-circle>
            </template>
        </v-layer>
    </v-stage>
</template>

<script>
    export default {
        name: "Canvas",
        data() {
            return {
                configKonva: {
                    width: 500,
                    height: 500,
                },
                elements: []
            }
        },
        methods: {
            addElement(type, config){
                this.elements.push({type, config})
            },
        }
    }
</script>

<style scoped>

</style>