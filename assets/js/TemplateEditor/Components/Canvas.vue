<template>
    <v-stage
        :config="configKonva"
        @click="handleStageClick"
        @mousedown="handleStageMouseDown"
        @mousemove="handleStageMouseMove"
        @mouseup="handleStageMouseUp"
    >
        <v-layer ref="mainLayer">
            <template v-for="element in elements">
                <Element :config="element.config" :type="element.type"></Element>
            </template>
            <v-transformer ref="transformer"></v-transformer>
            <v-rect :config="{fill: 'rgba(0,0, 255, 0.5)', visible: false}" ref="selector"></v-rect>
        </v-layer>
    </v-stage>
</template>

<script>
    import Element from "./Element";
    export default {
      name: "Canvas",
      components: {Element},
      props: {
        'selectedElements': Array,
      },
      data() {
        return {
            configKonva: {
                width: 500,
                height: 500,
            },
            elements: [],
            selectorCoords: {
              x1: null,
              x2: null,
              y1: null,
              y2: null
            }
        }
      },
      created() {
        window.addEventListener('keydown', this.handleKeyboard);
      },
      destroyed() {
        window.removeEventListener('keydown', this.handleKeyboard);
      },
      methods: {
        addElement(type, config){
          this.elements.push({type, config})
        },
        updateTransformer(selectedElements) {
          const transformerNode = this.$refs.transformer.getNode();

          const selectedNodes = selectedElements.map(el => el.VueComponent.getNode());

          transformerNode.nodes(selectedNodes);

          transformerNode.getLayer().batchDraw();
        },
        handleStageClick(e) {
          // If we're doing a batch selection, do nothing
          const selectorNode = this.$refs.selector.getNode();
          if (selectorNode.visible()) {
            return;
          }
          // Click on stage - clear selection
          if (e.target === e.target.getStage()) {
            this.$emit('set-selected-element', []);
            this.updateTransformer([]);
            return;
          }

          const metaPressed = e.evt.ctrlKey;
          const isSelected = this.$refs.transformer.getNode().nodes().indexOf(e.target) !== -1;

          let newSelection;
          if (!metaPressed) {
            newSelection = [e.target];
          } else if (metaPressed && isSelected){
            newSelection = this.selectedElements.filter(item => item !== e.target);
          } else if(metaPressed && !isSelected) {
            newSelection = this.selectedElements.concat([e.target]);
          }

          this.$emit('set-selected-element', newSelection);
          this.updateTransformer(newSelection);
        },
        handleStageMouseDown(e) {
          const stage = e.target.getStage();
          // Only draw rectangle selection on stage click
          if (e.target !== stage){
            return;
          }

          // Get initial coords;
          this.selectorCoords.x1 = stage.getPointerPosition().x;
          this.selectorCoords.x2 = stage.getPointerPosition().x;
          this.selectorCoords.y1 = stage.getPointerPosition().y;
          this.selectorCoords.y1 = stage.getPointerPosition().y;

          // Start displaying the selector rectangle
          const selectorNode = this.$refs.selector.getNode();
          selectorNode.visible(true);
          selectorNode.width(0);
          selectorNode.height(0);
          selectorNode.getLayer().draw();
        },
        handleStageMouseMove(e) {
          const selectorNode = this.$refs.selector.getNode();
          // If user is not batch selecting, do nothing
          if (!selectorNode.visible()) {
            return;
          }
          const stage = e.target.getStage();
          this.selectorCoords.x2 = stage.getPointerPosition().x;
          this.selectorCoords.y2 = stage.getPointerPosition().y;

          // Display the rect selector according to mouse position
          selectorNode.setAttrs({
            x: Math.min(this.selectorCoords.x1, this.selectorCoords.x2),
            y: Math.min(this.selectorCoords.y1, this.selectorCoords.y2),
            width: Math.abs(this.selectorCoords.x2 - this.selectorCoords.x1),
            height: Math.abs(this.selectorCoords.y2 - this.selectorCoords.y1),
          });
          selectorNode.getLayer().batchDraw();
        },
        handleStageMouseUp(e) {
          const selectorNode = this.$refs.selector.getNode();
          // If user is not batch selecting, do nothing
          if(!selectorNode.visible()) {
            return;
          }

          setTimeout(() => {
            selectorNode.visible(false);
            selectorNode.getLayer().batchDraw();
          });

          const shapes = e.target.getStage().find('.konvaElement').toArray();
          const box = selectorNode.getClientRect();
          const selectedShapes = shapes.filter(shape => Konva.Util.haveIntersection(box, shape.getClientRect()));

          this.$emit('set-selected-element', selectedShapes);
          this.updateTransformer(selectedShapes);
        },
        handleKeyboard(e) {
          if(this.selectedElements.length) {
            let callback;
            const DELTA = e.ctrlKey ? 8 : 2;
            switch(e.key) {
              case "ArrowLeft":
                callback = el => el.x(el.x() - DELTA);
                break;
              case "ArrowUp":
                callback = el => el.y(el.y() - DELTA);
                break;
              case "ArrowRight":
                callback = el => el.x(el.x() + DELTA);
                break;
              case "ArrowDown":
                callback = el => el.y(el.y() + DELTA);
                break;
              default:
                return
            }
            e.preventDefault();
            this.selectedElements.map(callback);
            this.$refs.mainLayer.getNode().batchDraw();
          }
        }
      }
    }
</script>

<style scoped>

</style>