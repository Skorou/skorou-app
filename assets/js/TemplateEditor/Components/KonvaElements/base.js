export default {
    props: {
        "type": String,
        "initialConfig": Object,
    },
    data: function() {
        return {
            "config": this.initialConfig
        };
    },
    method: {
        handleTransform(e) {
            this.config.x = e.target.x();
            this.config.y = e.target.y();
            this.config.rotation = e.target.rotation();
            this.scaleX = e.target.scaleX();
            this.scaleY = e.target.scaleY();
        }
    },
}