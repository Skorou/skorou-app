<template>
  <div>
    <div>
    <input v-model="textInput" placeholder="Entrez du texte ici"/>
    <button @click="textInput && addElement('text', {
      text: textInput,
      fontSize: 30,
      fontFamily: 'Comic sans MS',
      draggable: true,
      x: 100,
      y: 100,
    })">Ajouter</button>
    </div>

    <hr/>

    <div>
      Intégrer un élément personnalisé
    </div>
    <button @click="addElement('text', {
      text: '[# nom_entreprise #]',
      fontSize: 30,
      fontFamily: 'Comic sans MS',
      draggable: true,
      x: 100,
      y: 100,
    })">Nom entreprise</button>
  </div>


</template>

<script>
import ControlsBase from "./base";

export default {
  name: "TextControls",
  mixins: [ControlsBase],
  data() {
    return {
      textInput: '',
    }
  }
}
</script>

<style scoped>

</style>