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
            <br/>
            <input v-model="textInput" placeholder="Enter text here"/>
            <button @click="textInput && addElem('text', {
                text: textInput,
                fontSize: 30,
                fontFamily: 'Comic sans MS',
                draggable: true,
            })">Text</button>
            <br/>
            <button @click="loadImage({
                height: 150,
                width: 150,
                draggable: true,
            })">Image</button>
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
                textInput: '',
            }
        },
        methods: {
            addElem: function(type, config){
                this.$refs.canvas.addElement(type, config)
            },
            loadImage: function (config) {
                let image = new Image();
                image.onload = function(){
                    this.addElem('image', {
                        ...config,
                        image
                    });
                }.bind(this);
                image.src = "/assets/images/logo_skorou.jpg";
            }
        }
    }
</script>

<style scoped>

</style>