<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Create Module</h4>
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
    methods: {
        store: function(event){
            let form_data = new FormData(event.target);
            axios.post('/question-bank/module/store',form_data)
                .then(res=>{
                    window.s_alert('success','Information inserted successfully');
                    this.$router.push({name:'moduleAll'});
                    // console.log(res.data);
                })
        }
    }
}
</script>

<style>

</style>
