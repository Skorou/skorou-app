<template>
  <v-circle :config="config" @transformend="handleTransform" @dragend="handleDrag"></v-circle>
</template>

<script>
import KonvaElementBase from "./base";
export default {
  name: "KonvaCircle",
  mixins: [KonvaElementBase]
}
</script>

<style scoped>

</style>