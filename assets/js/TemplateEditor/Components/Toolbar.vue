<template>
  <TextToolbar v-if="selectedElementType === 'text'"/>
  <MultiSelectionToolbar v-else-if="selectedElementType === 'multi'"/>
  <DefaultToolbar v-else/>
</template>

<script>
import TextToolbar from "./ToolBars/TextToolbar";
import MultiSelectionToolbar from "./ToolBars/MultiSelectionToolbar";
import DefaultToolbar from "./ToolBars/DefaultToolbar";

export default {
  name: "Toolbar",
  components: {TextToolbar, MultiSelectionToolbar, DefaultToolbar},
  computed: {
    selectedElementType(){
      if(this.$store.state.selectedElements.length > 1) {
        return "multi";
      } else if(this.$store.state.selectedElements.length === 1) {
        switch(this.$store.state.selectedElements[0].type){
          case 'text':
            return "text";
        }
      }
    },
    selectedElements(){
      return this.$store.state.selectedElements;
    }
  }
}
</script>

<style scoped>

</style>