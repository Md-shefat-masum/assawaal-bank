<template>
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 pt-2 col-lg-2 d-md-block sidebar show" >
            <!-- <div class="card">
                <div class="card-body text-center">
                    <img :src="`https://png.pngtree.com/png-vector/20191101/ourmid/pngtree-cartoon-color-simple-male-avatar-png-image_1934459.jpg`" class="rounded rounded-circle img-thumbnail" style="width: 70px" />
                    <h6 class="text-capitalize">
                        {{get_auth_information.first_name}}
                        {{get_auth_information.last_name}} <br>
                        <small class="text-uppercase" style="font-size: 12px;">#{{get_auth_information.user_name}}</small>
                    </h6>
                </div>
            </div> -->
            <div class="pt-3 d-flex flex-column h-100">
                <ul class="nav flex-column" style="flex:1;">
                    <li class="nav-item">
                        <router-link class="nav-link" aria-current="page" :to="{name:'Dashboard'}">
                            <i class="fa fa-home"></i> &nbsp; Dashboard
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'moduleAll'}">
                            <i class="fa fa-list"></i> &nbsp; module
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'chapterAll'}">
                            <i class="fa fa-list"></i> &nbsp; chapter
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'questionAll'}">
                            <i class="fa fa-list"></i> &nbsp; question
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'questionPaperAll'}">
                            <i class="fa fa-list"></i> &nbsp; question paper
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'examAll'}">
                            <i class="fa fa-list"></i> &nbsp; exam
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <!-- <a class="nav-link" href="/logout" onclick="event.preventDefault();window.localStorage.removeItem('token');window.confirm('want to logout??') && document.getElementById('logout-form').submit();"> -->
                        <a class="nav-link" href="/logout" @click.prevent="logout()">
                            <i class="fa fa-sign-out"></i> &nbsp; Logout
                        </a>
                    </li>
                </ul>
                <div class="watch">
                    <div class="title">left time</div>:
                    <div class="min ms-3">{{left_time_min}}</div>:
                    <div class="sec">{{left_time_sec}}</div>
                    <div class="meridian">min</div>
                </div>
            </div>
        </nav>
        <main class="p-3 main_content" style="flex: 1 1 0%">
            <div class="loader_body">
                <div class="loader"><div></div><div></div></div>
            </div>
            <router-view></router-view>
        </main>
    <div class="form_errors"></div>
    </div>

</template>

<script>
import { mapGetters } from 'vuex';
export default {
    computed: {
        ...mapGetters(['get_auth_information'])
    },
    created: function(){
        this.update_left_time();
    },
    data: function(){
        return {
            left_time_min: '00',
            left_time_sec: '00',
        }
    },
    methods: {
        update_left_time: function(){
            let that = this;
            setInterval(() => {
                let diff = ( 60000 * window.auto_logout_time ) - window.sessionStorage.getItem('idle_time');
                that.left_time_min = moment.utc(diff).format('mm');
                that.left_time_sec = moment.utc(diff).format('ss');
            }, 1000);
        },
        logout: function(){
            window.logout();
        }
    }
};
</script>

<style>
</style>
