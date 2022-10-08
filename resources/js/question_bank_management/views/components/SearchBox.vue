<template>
    <form class="d-flex gap-2" action="" @submit.prevent="search_data">
        <input type="text" name="key" @keydown="set_search_key($event.target.value)" @keyup="!$event.target.value.length?search_data():()=>''" class="form-control border border-secondary" placeholder="search" >

        <select @change="handle_search_module_id($event.target.value)" v-if="check_given_module_id()" class="form-control border border-secondary" >
            <option value="">module</option>
            <option v-for="qmodule in qmodules" :key="qmodule.id" :value="qmodule.id">
                {{ qmodule.name }}
            </option>

        </select>

        <select @change="set_search_chapter_id($event.target.value)" v-if="check_given_chapter_id()" class="form-control border border-secondary" >
            <option value="">chapter</option>
            <option v-for="chapter in chapters" :key="chapter.id" :value="chapter.id">
                {{ chapter.chapter_name }}
            </option>
        </select>

        <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
    </form>
</template>

<script>
export default {
    props: [
        'set_search_key',
        'search_data',
        'set_search_module_id',
        'set_search_chapter_id',
    ],
    data: function(){
        return {
            qmodules: [],
            chapters: [],
            slected_module_id: null,
        }
    },
    created: async function(){
        await this.get_modules();
        await this.get_chapters();
    },
    watch: {
        slected_module_id: {
            handler: function(newV){
                if(this.check_given_chapter_id()){
                    return this.get_chapters();
                }
            }
        }
    },
    methods: {
        handle_search_module_id: function(newV){
            this.slected_module_id = newV;
            this.set_search_module_id(newV);
        },
        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.qmodules = res.data;
                    this.slected_module_id = res.data[0].id;
                })
        },
        get_chapters: function(){
            axios.get('/question-bank/chapter/get-all'+`?module_id=${this.slected_module_id}`)
                .then(res=>{
                    this.chapters = res.data;
                })
        },
        check_given_module_id: function(){
            if(this.set_search_module_id){
                return true;
            }else{
                return false;
            }
        },
        check_given_chapter_id: function(){
            if(this.set_search_chapter_id){
                return true;
            }else{
                return false;
            }
        },
    }
}
</script>

<style>

</style>
