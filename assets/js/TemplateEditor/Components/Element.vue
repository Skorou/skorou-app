<template>
  <KonvaRectangle v-if="type === 'rect'" :initial-config="config"></KonvaRectangle>
  <KonvaCircle v-else-if="type === 'circle'" :initial-config="config"></KonvaCircle>
  <KonvaText v-else-if="type === 'text'" :initial-config="config"></KonvaText>
  <KonvaImage v-else-if="type === 'image'" :initial-config="config"></KonvaImage>
</template>

<script>
import KonvaRectangle from "./KonvaElements/KonvaRectangle";
import KonvaCircle from "./KonvaElements/KonvaCircle";
import KonvaText from "./KonvaElements/KonvaText";
import KonvaImage from "./KonvaElements/KonvaImage";
export default {
  name: "Element",
  components: {KonvaImage, KonvaText, KonvaCircle, KonvaRectangle},
  props: ['type', 'config']
}
</script>

<style scoped>

</style>