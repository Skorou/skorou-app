<template>
  <KonvaRectangle v-if="type === 'rect'" :config="config"></KonvaRectangle>
  <KonvaCircle v-else-if="type === 'circle'" :config="config"></KonvaCircle>
  <KonvaText v-else-if="type === 'text'" :config="config"></KonvaText>
  <KonvaImage v-else-if="type === 'image'" :config="config"></KonvaImage>
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