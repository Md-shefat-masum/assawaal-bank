<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Edit Module</h4>
                        <ul>
                            <li>
                                <a href="#/module" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" @submit.prevent="store($event)">
                            <div class="form-group mb-3">
                                <label for="name" class="text-capitalize">Name</label>
                                <textarea type="text" id="name" rows="5" name="name" class="form-control"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="number" class="text-capitalize">module number</label>
                                <input type="text" id="number" name="number" class="form-control">
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
        this.get_data();
    },
    methods: {
        get_data: function(){
            axios.post('/question-bank/module/get/'+this.$route.params.id)
                .then(res=>{
                    // console.log(res.data);
                    for (const key in res.data) {
                        if (Object.hasOwnProperty.call(res.data, key)) {
                            const element = res.data[key];
                            $(`#${key}`).val(element);
                        }
                    }
                })
        },
        store: function(event){
            let form_data = new FormData(event.target);
            form_data.append('id',this.$route.params.id);
            axios.post('/question-bank/module/update',form_data)
                .then(res=>{
                    // this.$router.push({name:'moduleAll'});
                    // console.log(res.data);
                    window.s_alert('success','Information updated successfully');
                })
        }
    }
}
</script>

<style>

</style>
