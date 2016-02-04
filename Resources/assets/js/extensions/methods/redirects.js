export default {
    redirectBack: function(){
        return this.$route.router.go(window.history.back());
    },

    redirectForward: function(){
        return this.$route.router.go(window.history.forward())
    },

    redirectUp: function(){
        return this.$route.router.go({
            name: 'path',
            params: { pool: this.selectedPool.uid, parent_uid: this.meta.parent_uid?this.meta.parent_uid:'null' }
        })
    }
};