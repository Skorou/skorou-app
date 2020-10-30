<template>
    <div>
        <Canvas ref="canvas"/>
        <div id="toolbar">
            <button @click="addElem('rect', {
                width: 100,
                height: 50,
                fill: 'red',
                stroke: 'black',
                strokeWidth: 5,
                draggable: true,
            })">Rectangle</button>
            <button @click="addElem('circle', {
                radius: 40,
                fill: 'blue',
                stroke: 'black',
                strokeWidth: 5,
                draggable: true
            })">Cercle</button>
        </div>
    </div>
</template>

<script>
    import Canvas from "./Canvas";

    export default {
        name: "Editor",
        components: { Canvas },
        data() {
            return {
                elements: [],
            }
        },
        methods: {
            addElem: function(type, config){
                this.$refs.canvas.addElement(type, config)
            }
        }
    }
</script>

<style scoped>

</style>