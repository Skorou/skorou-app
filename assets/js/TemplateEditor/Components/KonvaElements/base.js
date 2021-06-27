export default {
    props: {
        "type": String,
        "config": Object,
    },
    methods: {
        handleTransform(e) {
            let newConfig = this.config;
            newConfig.x = e.target.x();
            newConfig.y = e.target.y();
            newConfig.rotation = e.target.rotation();
            newConfig.scaleX = e.target.scaleX();
            newConfig.scaleY = e.target.scaleY();
            this.$store.commit('updateConfig', newConfig)
        },
        handleDrag(e) {
            let newConfig = this.config;
            newConfig.x = e.target.x();
            newConfig.y = e.target.y();
            this.$store.commit('updateConfig', newConfig)
        },
    },
}