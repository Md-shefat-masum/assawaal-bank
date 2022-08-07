<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Import Question</h4>
                        <ul>
                            <li>
                                <a href="#/question" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" @submit.prevent="get_file($event.target)" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="file" class="text-capitalize">Choose CSV File</label>
                                <input type="file" @change="get_file($event.target.parentNode.parentNode)" accept=".csv" id="file" name="file" class="form-control">
                            </div>
                            <div class="text-center">
                                <a href="/csv_sample/question.csv" download="" target="_black" class="btn btn-success mx-2">
                                    <i class="fa fa-download"></i> Download Sample
                                </a>
                                <button type="reset" @click="data=[];" class="btn btn-warning mx-2"><i class="fa fa-rotate"></i> Reset</button>
                                <button class="btn btn-info"><i class="fa fa-sign-in mx-2"></i>File Upload</button>
                                <button @click.prevent="store" class="btn btn-secondary mx-2"><i class="fa fa-upload"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Preview</h4>
                        <ul class="d-flex gap-2">
                            <!-- <li>
                                <a href="#/question" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li> -->
                        </ul>
                    </div>
                    <div class="card-body" style="max-height: 60vh; overflow-y: scroll;">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Module</th>
                                    <th>Chapter</th>
                                    <th>Question.Pattern</th>
                                    <th>Question.Title</th>
                                    <th>Option.1</th>
                                    <th>Option.2</th>
                                    <th>Option.3</th>
                                    <th>Answer</th>
                                    <th>Part 66 reference</th>
                                    <th>Training note reference</th>
                                    <th>Verified by</th>
                                    <th>Reference</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in data" :key="index">
                                    <td class="text-start">{{index+1}}</td>
                                    <td>{{item.module}}</td>
                                    <td>{{item.chapter}}</td>
                                    <td>{{item.question_pattern}}</td>
                                    <td>{{item.question_title}}</td>
                                    <td>{{item.option1}}</td>
                                    <td>{{item.option2}}</td>
                                    <td>{{item.option3}}</td>
                                    <td>{{item.answer}}</td>
                                    <td>{{item.part_66_reference}}</td>
                                    <td>{{item.training_note_reference}}</td>
                                    <td>{{item.prepared_by}}</td>
                                    <td>{{item.verified_by}}</td>
                                    <td>{{item.level}}</td>
                                </tr>
                            </tbody>
                        </table>
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
            data: [],
        }
    },
    methods: {
        get_file: function(event){
            let form_data = new FormData(event);
            axios.post('/question-bank/question/load-csv',form_data)
                .then(res=>{
                    this.data = res.data;
                })
                .catch(err=>{
                    window.s_alert('error','something wrong. try again');
                })
        },
        store: function(){
            if(window.confirm('confirm??')){
                axios.post('/question-bank/question/store_csv',{data: this.data})
                    .then(res=>{
                        window.s_alert('success','data imported successfully');
                        this.$router.push({name:'questionAll'});
                        // console.log(res.data);
                    })
            }
        }
    }
}
</script>

<style>

</style>
