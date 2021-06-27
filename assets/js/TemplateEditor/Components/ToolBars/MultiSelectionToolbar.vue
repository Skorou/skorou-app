<template>
  <div>
      <div class="canvas-tools-group align-left">
       
      </div>
      <div class="canvas-tools-group align-right">
        <button class="btn btn-sm btn-secondary" @click="remove()">Effacer</button>
      </div>
  </div>
</template>

<script>
import BaseToolBar from "./base";

export default {
  name: "MultiSelectionToolbar",
  mixins: [BaseToolBar]
}
</script>

<style scoped>

</style>