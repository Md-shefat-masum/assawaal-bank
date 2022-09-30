<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h5 class="text-capitalise">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="change_password($event)" class="row">
                            <div class="form-group mb-3 col-lg-4">
                                <label for="old_password" class="text-capitalize">
                                    Old Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="old_password" rows="1" name="old_password" class="form-control" />
                            </div>
                            <div class="form-group mb-3 col-lg-4">
                                <label for="newpassword" class="text-capitalize">
                                    New Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="newpassword" rows="1" name="newpassword" class="form-control" />
                            </div>
                            <div class="form-group mb-3 col-lg-4">
                                <label for="newpassword_confirmation" class="text-capitalize">
                                    Confirm Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" id="newpassword_confirmation" rows="1" name="newpassword_confirmation" class="form-control" />
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-sm btn-success">
                                    <i class="fa fa-upload"></i> Submit
                                </button>
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
    data: function(){
        return {

        }
    },
    methods: {
        change_password: function(e){
            let formData = new FormData(e.target);

            axios.post('/user/update-profile',formData)
                .then(res=>{
                    // console.log(res.data);
                    window.s_alert('success','password updated');
                    e.target.reset();
                })
                // .catch(err=>{
                //     console.log(err.response.data);
                // })
        }
    }
}
</script>

<style>

</style>
