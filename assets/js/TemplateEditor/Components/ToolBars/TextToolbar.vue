<template>
  <div>
      <div class="canvas-tools-group align-left">
        <input type="number" :value="fontSize" @input="updateConfig($event, 'fontSize')"/>
      </div>
      <div class="canvas-tools-group align-right">
        <button class="btn btn-sm btn-secondary" @click="remove()">Effacer</button>
      </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import BaseToolBar from "./base";

export default {
  name: "TextToolbar",
  mixins: [BaseToolBar],
  computed: {
    ...mapState({
      fontSize: state => state.selectedElements[0].config.fontSize
    })
  },
  methods: {
    updateConfig (event, property) {
      this.$store.commit('updateConfig', {[property]: event.target.value})
    }
  }
}
</script>

<style scoped>

</style>