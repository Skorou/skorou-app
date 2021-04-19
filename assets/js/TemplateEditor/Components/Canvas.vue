<template>
    <v-stage
        :config="configKonva"
        @click="handleStageClick"
        @mousedown="handleStageMouseDown"
        @mousemove="handleStageMouseMove"
        @mouseup="handleStageMouseUp"
        @dragmove="handleDragMove"
        @dragend="handleDragEnd"
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

    const SNAPPING_GUIDELINE_OFFSET = 3;

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

          const selectedNodes = selectedElements.filter(el => el.VueComponent).map(el => el.VueComponent.getNode());

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
        },
        /* Snap position of object (anchor) */
        getLineGuidesStops(stage, skipShape) {
          // Snap stage border and center of stage
          let vertical = [0, stage.width() / 2, stage.width()];
          let horizontal = [0, stage.height() / 2, stage.height()];

          stage.find('.konvaElement').forEach(guideItem => {
            if (guideItem === skipShape) {
              return;
            }

            if(this.selectedElements.indexOf(guideItem) !== -1) {
              return;
            }

            let box = guideItem.getClientRect();
            vertical.push([box.x, box.x + box.width, box.x + box.width / 2]);
            horizontal.push([box.y, box.y + box.height, box.y + box.height / 2]);
          });

          return {
            vertical: vertical.flat(),
            horizontal: horizontal.flat(),
          };
        },
        getObjectSnappingEdges(node) {
          let box = node.getClientRect();
          let absPos = node.absolutePosition();
          return {
            vertical: [
              {
                guide: Math.round(box.x),
                offset: Math.round(absPos.x - box.x),
                snap: 'start'
              },
              {
                guide: Math.round(box.x + box.width / 2),
                offset: Math.round(absPos.x - box.x - box.width / 2),
                snap: 'center'
              },
              {
                guide: Math.round(box.x + box.width),
                offset: Math.round(absPos.x - box.x - box.width),
                snap: 'end'
              },
            ],
            horizontal: [
              {
                guide: Math.round(box.y),
                offset: Math.round(absPos.y - box.y),
                snap: 'start'
              },
              {
                guide: Math.round(box.y + box.height / 2),
                offset: Math.round(absPos.y - box.y - box.height / 2),
                snap: 'center'
              },
              {
                guide: Math.round(box.y + box.height),
                offset: Math.round(absPos.y - box.y - box.height),
                snap: 'end'
              },
            ],
          };
        },
        getGuides(lineGuidesStops, itemBounds) {
          let resultV = [];
          let resultH = [];
          lineGuidesStops.vertical.forEach(lineGuide => {
            itemBounds.vertical.forEach(itemBound => {
              let diff = Math.abs(lineGuide - itemBound.guide);

              if(diff < SNAPPING_GUIDELINE_OFFSET) {
                resultV.push({
                  lineGuide: lineGuide,
                  diff: diff,
                  snap: itemBound.snap,
                  offset: itemBound.offset
                });
              }
            });
          });

          lineGuidesStops.horizontal.forEach(lineGuide => {
            itemBounds.horizontal.forEach(itemBound => {
              let diff = Math.abs(lineGuide - itemBound.guide);
              if(diff < SNAPPING_GUIDELINE_OFFSET) {
                resultH.push({
                  lineGuide: lineGuide,
                  diff: diff,
                  snap: itemBound.snap,
                  offset: itemBound.offset
                });
              }
            });
          });

          let guides = [];

          let minV = resultV.sort((a, b) => a.diff - b.diff)[0];
          let minH = resultH.sort((a, b) => a.diff - b.diff)[0];
          if (minV) {
            guides.push({
              lineGuide: minV.lineGuide,
              offset: minV.offset,
              orientation: 'V',
              snap: minV.snap
            });
          }
          if (minH) {
            guides.push({
              lineGuide: minH.lineGuide,
              offset: minH.offset,
              orientation: 'H',
              snap: minH.snap
            });
          }
          return guides;
        },
        drawGuides(guides) {
          const layer = this.$refs.mainLayer.getNode()
          guides.forEach((lg) => {
            if (lg.orientation === "H") {
              let line = new Konva.Line({
                points: [-6000, 0, 6000, 0],
                stroke: 'rgb(0, 161, 255)',
                strokeWidth: 1,
                name: 'guid-line',
                dash: [4, 6],
              });
              layer.add(line);
              line.absolutePosition({
                x: 0,
                y: lg.lineGuide
              });
              layer.batchDraw();
            } else if (lg.orientation === "V") {
              let line = new Konva.Line({
                points: [0, -6000, 0, 6000],
                stroke: 'rgb(0, 161, 255)',
                strokeWidth: 1,
                name: "guid-line",
                dash: [4, 6]
              });
              layer.add(line);
              line.absolutePosition({
                x: lg.lineGuide,
                y: 0,
              });
              layer.batchDraw();
            }
          });
        },
        handleDragMove(e) {
          const layer = e.target.getLayer();

          if(this.selectedElements.length && e.target.getType() !== "Group"){
            return
          }

          layer.find('.guid-line').destroy();

          let lineGuideStops = this.getLineGuidesStops(e.target.getStage(), e.target);
          let itemBounds = this.getObjectSnappingEdges(e.target);
          let guides = this.getGuides(lineGuideStops, itemBounds);

          if(!guides.length) {
            return;
          }

          this.drawGuides(guides);

          let absPos = e.target.absolutePosition();

          guides.forEach(lg => {
            switch(lg.snap) {
              case 'start': {
                switch(lg.orientation) {
                  case 'V': {
                    absPos.x = lg.lineGuide + lg.offset;
                    break;
                  }
                  case 'H': {
                    absPos.y = lg.lineGuide + lg.offset;
                    break;
                  }
                }
                break;
              }
              case 'center': {
                switch (lg.orientation) {
                  case 'V': {
                    absPos.x = lg.lineGuide + lg.offset;
                    break;
                  }
                  case 'H': {
                    absPos.y = lg.lineGuide + lg.offset;
                    break;
                  }
                }
                break;
              }
              case 'end': {
                switch(lg.orientation) {
                  case 'V': {
                    absPos.x = lg.lineGuide + lg.offset;
                    break;
                  }
                  case 'H': {
                    absPos.y = lg.lineGuide + lg.offset;
                    break;
                  }
                }
              }
            }
          });
          e.target.absolutePosition(absPos);
        },
        handleDragEnd(e) {
          const layer = e.target.getLayer();
          layer.find('.guid-line').destroy();
          layer.batchDraw();
        }
      }
    }
</script>

<style scoped>

</style>