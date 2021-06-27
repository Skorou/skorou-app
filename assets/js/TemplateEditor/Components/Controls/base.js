export default {
    methods: {
        addElement (type, config) {
            config = {...config, name: 'konvaElement'};
            this.$store.commit("addElement", {type, config});
        }
    }
}