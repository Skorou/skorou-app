<template>
    <div id="editor">
      <div id="controls">
        <div id="controls-tab">
          <div :class="[{active: currentTab === 'text'}, 'controls-tab']" @click="currentTab = 'text'">
            <div class="controls-tab-title">Texte</div>
          </div>
          <div :class="[{active: currentTab === 'photos'}, 'controls-tab']" @click="currentTab = 'photos'">
            <div class="controls-tab-title">Photos</div>
          </div>
          <div :class="[{active: currentTab === 'shapes'}, 'controls-tab']" @click="currentTab = 'shapes'">
            <div class="controls-tab-title">Éléments</div>
          </div>
        </div>
        <div id="controls-panel">
          <Controls :panel="currentTab"/>
        </div>
      </div>
      <div id="canvas-container">
        <div id="canvas-tools">
          <Toolbar/>
        </div>
        <div id="canvas">
          <Canvas/>
        </div>
      </div>
      <div id="save-btn">
        <button class="btn btn-lg btn-success" @click="save">
          Sauvegarder
        </button>
      </div>
    </div>
</template>

<script>
import Canvas from "./Canvas";
import Controls from "./Controls";
import Toolbar from "./Toolbar";

export default {
  name: "Editor",
  components: {Toolbar, Controls, Canvas },
  data() {
    return {
      currentTab: 'text',
    }
  },
  methods: {
    save(){
      const url = window['SAVE_URL'];
      const data = JSON.stringify(this.$store.state.elements);
        fetch(url, {
            headers: new Headers({
                "X-Requested-With": "XMLHttpRequest"  // Follow common headers
            }),
            method: 'POST',
            body: JSON.stringify({
                'data': data,
            })
        }).then(function(response){
            if(response.ok) {
              alert("Template sauvegardé");
            } else {
                console.error("error")
            }
        }).catch(function(error){
            console.error(error)
        });
    }
  }
}
</script>

<style scoped>

</style>