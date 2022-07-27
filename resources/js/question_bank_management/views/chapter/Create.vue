<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Create Chapter</h4>
                        <ul>
                            <li>
                                <a href="#/chapter" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" @submit.prevent="store($event)">
                            <div class="form-group mb-3">
                                <label for="number" class="text-capitalize">Select module</label>
                                <select name="module_id" id="module_id" class="form-control">
                                    <option v-for="s_module in modules" :value="s_module.id" :key="s_module.id">{{s_module.name}}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="chapter_name" class="text-capitalize">Chapter Name</label>
                                <textarea type="text" id="chapter_name" rows="4" name="chapter_name" class="form-control"></textarea>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-info"><i class="fa fa-sign-in"></i> submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    created: function(){
        this.get_modules();
    },
    data: function(){
        return {
            modules: [],
        }
    },
    methods: {
        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.modules = res.data;
                     setTimeout(() => {
                        $('#module_id').select2();
                    }, 300);
                })
        },
        store: function(event){
            let form_data = new FormData(event.target);
            axios.post('/question-bank/chapter/store',form_data)
                .then(res=>{
                    window.s_alert('success','Information inserted successfully');
                    this.$router.push({name:'chapterAll'});
                    // console.log(res.data);
                })
        }
    }
}
</script>

<style>

</style>
