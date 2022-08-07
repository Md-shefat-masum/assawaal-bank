<template>
    <router-view v-if="last_active_time_checked"></router-view>
    <div v-else></div>
</template>

<script>
import moment from 'moment';
import { mapActions, mapMutations } from 'vuex';

export default {
    data: function(){
        return {
            last_active_time_checked: false,
        }
    },
    created: function(){

        if(window.localStorage.getItem('last_time')){
            let now = new moment();
            let last_time = new moment(window.localStorage.getItem('last_time'));

            let duration = moment.duration(now.diff(last_time)).as('minutes');
            console.log(`last online ${duration} min ago.`);

            if(duration >= 15){
                window.clear_session();
                window.location = '/login';
            }
        }
        this.last_active_time_checked = true;
        this.fetch_check_auth();
        this.set_auth_information(JSON.parse(window.localStorage.user));
    },
    methods: {
        ...mapMutations(['set_auth_information']),
        ...mapActions(['fetch_check_auth']),
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
