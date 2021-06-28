<template>
  <div class="preview">
    <v-stage
        :config="configKonva"
    >
      <v-layer>
        <template v-for="element in elements">
          <Element :config="element.config" :type="element.type"></Element>
        </template>
      </v-layer>
    </v-stage>
  </div>
</template>

<script>
import Element from "../../TemplateEditor/Components/Element";


export default {
  name: "Canvas",
  components: {Element},
  data() {
    return {
      configKonva: {
        width: 800,
        height: 800,
      },
      elements: JSON.parse(this.$refs.previewContainer.dataset.konvaElements)
    }
  },
}
</script>

<style scoped>

</style>