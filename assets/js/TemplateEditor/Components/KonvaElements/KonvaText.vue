<template>
  <v-text :config="config" @transformend="handleTransform" @dragend="handleDrag"></v-text>
</template>

<script>
import KonvaElementBase from "./base";
export default {
  name: "KonvaText",
  mixins: [KonvaElementBase]
}
</script>

<style scoped>

</style>