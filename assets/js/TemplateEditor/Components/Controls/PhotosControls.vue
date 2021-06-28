<template>
  <div>
    <div>Ajouter une photo</div>
    <button @click="loadImage({
        height: 150,
        width: 150,
        draggable: true,
    })">Image</button>
  </div>
</template>

<script>
import ControlsBase from "./base";

export default {
  name: "PhotosControls",
  mixins: [ControlsBase],
  methods: {
    loadImage: function(config) {
      let image = new Image();
      image.onload = function() {
        this.addElement('image', {
          ...config,
          image
        });
      }.bind(this);
      image.src = "/assets/images/logo_skorou.jpg";
    }
  }
}
</script>

<style scoped>

</style>