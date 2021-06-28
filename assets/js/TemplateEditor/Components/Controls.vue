<template>
  <TextControls v-if="panel === 'text'"/>
  <PhotosControls v-else-if="panel === 'photos'"/>
  <ShapesControls v-else-if="panel === 'shapes'"/>
</template>

<script>
import TextControls from "./Controls/TextControls";
import PhotosControls from "./Controls/PhotosControls";
import ShapesControls from "./Controls/ShapesControls";
export default {
  name: "Controls",
  components: {ShapesControls, PhotosControls, TextControls},
  props: ['panel', 'selectedElements']
}
</script>

<style scoped>

</style>