<template>
  <div>
  <div>Ajouter des formes</div>
    <button @click="addElement('rect', {
        width: 100,
        height: 50,
        fill: 'red',
        stroke: 'black',
        strokeWidth: 5,
        draggable: true,
    })">Rectangle</button>
    <button @click="addElement('circle', {
        radius: 40,
        fill: 'blue',
        stroke: 'black',
        strokeWidth: 5,
        draggable: true
    })">Cercle</button>
  </div>
</template>

<script>
import ControlsBase from "./base";

export default {
  name: "ShapesControls",
  mixins: [ControlsBase],
}
</script>

<style scoped>

</style>