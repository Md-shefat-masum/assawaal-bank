<template>
    <div>
        <h3>Welcome to Question Bank</h3>
        <div class="at_a_glance mt-4">
            <div class="card bg-success">
                <router-link :to="{name:'moduleAll'}">
                    <div class="card-header">
                        <h3>Total Module</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.total_module}}
                    </div>
                </router-link>
            </div>
            <div class="card bg-info">
                <router-link :to="{name:'chapterAll'}">
                    <div class="card-header">
                        <h3>Total Chapter</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.total_chapter}}
                    </div>
                </router-link>
            </div>
            <div class="card " style="background:#673ab7">
                <router-link :to="{name:'questionAll'}">
                    <div class="card-header">
                        <h3>Total Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.total_question}}
                    </div>
                </router-link>
            </div>
            <div class="card " style="background:#673ab7">
                <router-link :to="{name:'questionType',params:{type:'used'}}">
                    <div class="card-header">
                        <h3>Used Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.used_question}}
                    </div>
                </router-link>
            </div>
            <div class="card " style="background:#673ab7">
                <router-link :to="{name:'questionType',params:{type:'intact'}}">
                    <div class="card-header">
                        <h3>Intact Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.intact_question}}
                    </div>
                </router-link>
            </div>
            <div class="card bg-primary">
                <router-link :to="{name:'questionType',params:{type:'Level 1'}}">
                    <div class="card-header">
                        <h3>Level 1 Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.level_1_question}}
                    </div>
                </router-link>
            </div>
            <div class="card bg-primary">
                <router-link :to="{name:'questionType',params:{type:'Level 2'}}">
                    <div class="card-header">
                        <h3>Level 2 Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.level_2_question}}
                    </div>
                </router-link>
            </div>
            <div class="card bg-primary">
                <router-link :to="{name:'questionType',params:{type:'Level 3'}}">
                    <div class="card-header">
                        <h3>Level 3 Question</h3>
                    </div>
                    <div class="card-body">
                        {{at_a_glance.level_3_question}}
                    </div>
                </router-link>
            </div>
            <!-- <div class="card" style="background: #702525;">
                <div class="card-header">
                    <h3>Last Update</h3>
                </div>
                <div class="card-body">
                    51
                </div>
            </div> -->
        </div>

        <div>
            <all-moduele-chapter-based></all-moduele-chapter-based>
        </div>
    </div>
</template>

<script>
import AllModueleChapterBased from './question/AllModueleChapterBased.vue';
export default {
    components: {
        AllModueleChapterBased,
    },
    data: function(){
        return {
            at_a_glance: {},
        }
    },
    created: function(){
        this.get_at_a_glance();
    },
    methods: {
        get_at_a_glance: function(){
            axios.get('/question-bank/at-a-glance')
                .then(res=>{
                    this.at_a_glance = res.data;
                })
        }
    },
};
</script>

<style>
</style>
