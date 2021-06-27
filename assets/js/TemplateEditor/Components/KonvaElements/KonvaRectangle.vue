<template>
  <v-rect :config="config" @transformend="handleTransform" @dragend="handleDrag"></v-rect>
</template>

<script>
import KonvaElementBase from "./base";
export default {
  name: "KonvaRectangle",
  mixins: [KonvaElementBase]
}
</script>

<style scoped>

</style>