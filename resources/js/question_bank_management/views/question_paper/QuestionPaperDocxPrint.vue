<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Question paper doc print: {{question.name}} ( {{selected_question_list.length}} )</h4>
                        <ul class="d-flex gap-2">
                            <li>
                                <a href="#" @click.prevent="print_docs('exportContent')" class="btn btn-outline-success"><i class="fa fa-floppy-disk"></i></a>
                            </li>
                            <li>
                                <a href="#/question-paper" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <button @click="demo_data" class="btn btn-sm btn-warning mb-2">Demo info</button>
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="course" class="text-capitalise">course</label>
                                <input type="text" v-model="course" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="exam" class="text-capitalise">exam</label>
                                <input type="text" v-model="exam" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="subject" class="text-capitalise">subject</label>
                                <input type="text" v-model="subject" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="batch" class="text-capitalise">batch</label>
                                <input type="text" v-model="batch" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="duration" class="text-capitalise">duration</label>
                                <input type="text" v-model="duration" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="date" class="text-capitalise">date</label>
                                <input type="text" v-model="date" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="semester" class="text-capitalise">semester</label>
                                <input type="text" v-model="semester" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="total_mark" class="text-capitalise">total_mark</label>
                                <input type="text" v-model="total_mark" class="form-control">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="pass_mark" class="text-capitalise">pass_mark</label>
                                <input type="text" v-model="pass_mark" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="show_ans" class="me-4">
                                    <input id="show_ans" type="checkbox" v-model="show_ans">
                                    Show answer
                                </label>
                                <label for="show_ref" class="me-4">
                                    <input id="show_ref" type="checkbox" v-model="show_ref">
                                    Show reference
                                </label>
                                <label for="show_level" class="me-4">
                                    <input id="show_level" type="checkbox" v-model="show_level">
                                    Show level
                                </label>
                                <label for="sort_by_chapter" class="me-4">
                                    <input id="sort_by_chapter" type="checkbox" v-model="sort_by_chapter">
                                    Chapter wise sort
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="exportContent" class="card-body my-5">
                        <h2 style="text-align:center;margin-bottom: 10px;text-decoration: underline;">Aeronautical Institute of Bangladesh (AIB)</h2>
                        <h3 style="text-align:center;margin-bottom: 10px;">{{ course }}</h3>
                        <h3 style="text-align:center;margin-bottom: 10px;">{{ exam }}</h3>
                        <h3 style="text-align:center;margin-bottom: 10px;">Subject: {{ subject }}</h3>

                        <table style="border-collapse:collapse;vertical-align: center; width:100%; border: 0; margin-top: 30px;" >
                            <tr>
                                <td style="width: 70%;line-height: 24px;">Batch: <b>{{ batch }}</b></td>
                                <td style="line-height: 24px;">Semester: <b>{{ semester }}</b></td>
                            </tr>
                            <tr>
                                <td style="line-height: 24px;">Duration: <b>{{ duration }}</b></td>
                                <td style="line-height: 24px;">Total Marks: <b>{{ total_mark }}</b></td>
                            </tr>
                            <tr>
                                <td style="line-height: 24px;">Date: <b>{{ date }}</b></td>
                                <td style="line-height: 24px;">Pass Marks: <b>{{ pass_mark }}</b></td>
                            </tr>
                        </table>

                        <div style="margin: 15px 0px;" v-for="(question,index) in render_list" :key="question.id" >

                            <div v-if="chapter_render_condition(sort_by_chapter, index, question)" class="mb-4 border-bottom">
                                <h3>Chapter: {{question.chapter.chapter_name}}</h3>
                            </div>

                            <h4>{{index+1}}. {{question.question_title}}</h4>
                            <ol type="a" style="padding-left: 25px; margin: 0;line-height: 24px;">
                                <li style="list-style: lower-alpha; line-height: 24px;">{{question.option_1}}</li>
                                <li style="list-style: lower-alpha; line-height: 24px;">{{question.option_2}}</li>
                                <li style="list-style: lower-alpha; line-height: 24px;">{{question.option_3}}</li>
                            </ol>
                            <div v-if="show_ans">
                                <b>Answer : </b> {{ question.answer }}
                            </div>
                            <div v-if="show_ref">
                                <b>Reference : </b> {{ question.reference }}
                            </div>
                            <div v-if="show_level">
                                <b>Level : </b> {{ question.level }}
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
    watch: {
        sort_by_chapter: {
            handler: function(newV){
                if(newV){
                    this.render_list = this.sort_by_chapter_list;
                }else{
                    this.render_list = this.selected_question_list;
                }
            }
        }
    },
    data: function(){
        return {
            question: {},
            selected_question: [],
            selected_question_list: [],
            sort_by_chapter_list: [],
            render_list: [],
            selected_chapter_id: null,

            show_ans: false,
            show_ref: false,
            show_level: false,
            sort_by_chapter: false,

            course: null,
            exam: null,
            subject: null,

            batch: null,
            duration: null,
            date: null,
            semester: null,
            total_mark: null,
            pass_mark: null,
        }
    },
    methods: {
        get_paper_data: function(){
            axios.post('/question-bank/question-paper/get/'+this.$route.params.id)
                .then(res=>{
                    // console.log(res.data);
                    this.question = res.data;
                    this.question_pattern = res.data.question_pattern;
                    this.selected_question = res.data.selected_question;

                    this.selected_question_list = res.data.selected_question_list;
                    this.sort_by_chapter_list = res.data.sort_by_chapter_list;
                    this.render_list = res.data.selected_question_list;
                })
        },

        chapter_render_condition: function(sort_by_chapter, index, question){
            if(sort_by_chapter && (index >= 0)){
                if(index == 0){
                    return true
                }
                if(question.chapter_id != this.render_list[index-1].chapter_id){
                    return true;
                }
            }
            return false;
        },

        demo_data: function(){
            this.course= 'Aircraft Maintenance Engineering Course';
            this.exam= "Phase Final Examination";
            this.subject= "Aircraft Digital Technique";

            this.batch= "12 (Ae & Av) ";
            this.duration= "2 Hours";
            this.date= "24.08.2021";
            this.semester= "V";
            this.total_mark= 100;
            this.pass_mark= 70;
        },

        print_docs: function(element){
            let name = this.course.replaceAll(' ','_')+" ";
            name += this.batch.replaceAll(' ','_')+" ";
            name += this.exam.replaceAll(' ','_');
            name += ` ${moment().format('DD-MMMM-YYYY hh-mm-ss A')}`;
            window.print_question_paper_docx(element,name);
        }
    }
}
</script>

<style>

</style>
