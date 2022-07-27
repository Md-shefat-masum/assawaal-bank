<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Exam Details</h4>
                        <ul>
                            <li>
                                <a href="#/exam" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="question_list_items">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-start">SI</th>
                                            <th>Paper Name</th>
                                            <th>Date</th>
                                            <th>Total Question</th>
                                            <th class="text-end pr-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item) in questions" :key="item.id">
                                            <td class="text-start">{{item.id}}</td>
                                            <td>{{item.question_paper_details.name}}</td>
                                            <td>{{format_date(item.question_paper_details.created_at,"DD-MMMM-yyyy")}}</td>
                                            <td>{{item.question_paper_details.questions_count}}</td>
                                            <td class="text-end pr-2" style="width: 90px;">
                                                <div class="table_actions">
                                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-gears"></i>
                                                    </a>
                                                    <ul>
                                                        <li>
                                                            <router-link :to="{name:'questionPaperDetails',params:{id:item.question_paper_details.id}}">
                                                                <i class="fa text-primary fa-list"></i>
                                                                show details
                                                            </router-link>
                                                        </li>
                                                        <li>
                                                            <router-link :to="{name:'questionPaperEdit',params:{id:item.question_paper_details.id}}">
                                                                <i class="fa text-warning fa-pencil-square"></i>
                                                                edit
                                                            </router-link>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
export default {
    created: function(){
        this.get_paper_data();
        // this.get_chapters();
        // this.get_data();
    },
    data: function(){
        return {
            questions: {},
        }
    },
    methods: {
        get_paper_data: function(){
            axios.post('/question-bank/exam/get/'+this.$route.params.id)
                .then(res=>{
                    this.questions = res.data.question_paper;
                })
        },
        format_date: function(data,format="dd-MMMM-YYYY hh:ss a"){
            return moment(data).format(format);
        }
    }
}
</script>

<style>

</style>
