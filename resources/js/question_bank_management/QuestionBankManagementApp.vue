<template>
    <router-view></router-view>
</template>

<script>
import { mapMutations } from 'vuex';

export default {
    props:['user'],
    created: function(){
        this.set_auth_information(JSON.parse(this.user));
    },
    methods: {
        ...mapMutations(['set_auth_information',]),
    },

};
</script>

<style scoped>
.form_errors {
    position: fixed;
    top: 81px;
    background: white;
    right: 0;
    border: 1px solid #ff000029;
    border-radius: 5px;
}
</style>
