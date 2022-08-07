<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Details: {{question.name}} ( {{selected_question_list.length}} )</h4>
                        <ul class="d-flex gap-2">
                            <li>
                                <router-link :to="{name:'QuestionPaperDocxPrint',params:{id:question.id}}" class="btn btn-outline-primary"><i class="fa fa-file-arrow-down"></i> Export question docx</router-link>
                            </li>
                            <li>
                                <a href="#" @click.prevent="get_paper_data()" class="btn btn-outline-warning"><i class="fa fa-shuffle"></i> Shuffle</a>
                            </li>
                            <li>
                                <a href="#" @click.prevent="export_data()" class="btn btn-outline-danger"><i class="fa fa-download"></i> Export</a>
                            </li>
                            <li>
                                <a href="#/question-paper" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table question_list_table question_list_table_for_create_paper_details table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-start">SI</th>
                                    <th>Question</th>
                                    <th>Option1</th>
                                    <th>Option2</th>
                                    <th>Option3</th>
                                    <th>Answer</th>
                                    <th>Reference</th>
                                    <th>Level</th>
                                    <th>Used Number</th>
                                    <th class="text-end pr-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(question) in selected_question_list" :key="question.id+(Math.random())">
                                    <td class="text-start">{{question.id}}</td>
                                    <td>{{question.question_title}}</td>
                                    <td>{{question.option_1}}</td>
                                    <td>{{question.option_2}}</td>
                                    <td>{{question.option_3}}</td>
                                    <td>{{question.answer}}</td>
                                    <td>{{question.reference}}</td>
                                    <td>{{question.level}}</td>
                                    <td>{{question.used_question_count}}</td>
                                    <td class="text-end pr-2" style="width: 90px;">
                                        <div class="table_actions">
                                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-gears"></i>
                                            </a>
                                            <ul>
                                                <li>
                                                    <router-link :to="{name:'questionUsedDetails',params:{id:question.id}}">
                                                        <i class="fa text-primary fa-list"></i>
                                                        used details
                                                    </router-link>
                                                </li>
                                                <li>
                                                    <router-link :to="{name:'questionEdit',params:{id:question.id}}">
                                                        <i class="fa text-warning fa-pencil"></i>
                                                        Edit
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
            question: {},
            selected_question: [],
            selected_question_list: [],
            selected_chapter_id: null,
        }
    },
    methods: {
        get_paper_data: function(){
            axios.post('/question-bank/question-paper/get/'+this.$route.params.id)
                .then(res=>{
                    // console.log(res.data);
                    this.question = res.data;
                    this.question_pattern = res.data.question_pattern;
                    this.selected_question_list = res.data.selected_question_list;
                    this.selected_question = res.data.selected_question;
                })
        },

        export_data: function(){
            axios.post('/question-bank/question-paper/export',{data: this.question})
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',(_.upperCase(this.question.name).replaceAll(' ','_'))+` ${moment().format('DD-MMMM-YYYY hh-mm-ss A')}`+'.csv');
                })
        },
        download: function (dataurl, filename) {
            const link = document.createElement("a");
            link.href = dataurl;
            link.download = filename;
            link.click();
        }
    }
}
</script>

<style>

</style>
