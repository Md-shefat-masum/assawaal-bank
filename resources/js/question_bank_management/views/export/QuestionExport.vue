<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <form @submit.prevent="get_module_chapter_data($event.target)">
                            <h5 class="text-capitalise">Question Export</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <div style="width: 220px;">
                                    <label for="" class="text-bold text-capitalize" >Select Module</label> <br/>
                                    <select name="module_id" id="module_id" class="form-control">
                                        <option v-for="s_module in modules" :value="s_module.id" :key="s_module.id">{{s_module.name}}</option>
                                    </select>
                                </div>
                                <div style="width: 220px;">
                                    <label for="chapter_id" class="text-bold text-capitalize">Select chapter</label><br/>
                                    <select name="chapter_id" id="chapter_id" class="form-control">
                                        <option v-for="chapter in chapters" :value="chapter.id" :key="chapter.id">{{chapter.chapter_name}}</option>
                                    </select>
                                </div>
                                <button class="btn btn-outline-secondary" id="module_and_chapter_form_btn" style="align-self: flex-end; height: 42px;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <ul class="d-flex gap-2">
                            <li>
                                <a href="#" @click.prevent="print_docs('exportContent')" class="btn btn-outline-success"><i class="fa fa-floppy-disk"></i> Export</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
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
                                    <label for="created_at" class="me-4">
                                        <input id="created_at" type="checkbox" v-model="created_at">
                                        Created Time
                                    </label>
                                    <label for="updated_at" class="me-4">
                                        <input id="updated_at" type="checkbox" v-model="updated_at">
                                        Updated Time
                                    </label>
                                </div>
                            </div>

                            <div id="exportContent" class="card-body my-5">
                                <h2 style="text-align:center;margin-bottom: 10px;text-decoration: underline;">Aeronautical Institute of Bangladesh (AIB)</h2>
                                <br>
                                <h5>Module: {{selected_module}}</h5>
                                <h5>Chapter: {{selected_chapter}}</h5>
                                <br>
                                <div style="margin: 15px 0px;" v-for="(question,index) in questions.data" :key="question.id" >
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
                                    <div v-if="created_at">
                                        <b>Created time : </b> {{ format_date(question.created_at) !== 'Invalid date' ? format_date(question.created_at) :'' }}
                                    </div>
                                    <div v-if="updated_at">
                                        <b>Last Update : </b> {{ format_date(question.updated_at) !== 'Invalid date' ? format_date(question.created_at) :'' }}
                                    </div>
                                </div>
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
        this.url = `/question-bank/question?per_page=${this.per_page}&page=`;
        this.get_modules();
        // this.get_data();
    },
    watch: {
        per_page: {
            handler: function(newV){
                this.url = `/question-bank/question?per_page=${newV}&page=`;
            }
        },
    },
    data: function(){
        return {
            data: {},
            questions: {},
            page: 1,
            key: null,
            per_page: 10,
            url: null,

            show_ans: false,
            show_ref: false,
            show_level: false,
            created_at: false,
            updated_at: false,

            modules: [],
            chapters: [],

            selected_module: '',
            selected_chapter: '',
        }
    },
    methods: {
        get_data: function(page = 1){
            this.page = page;
            axios.get(this.url+page)
                .then(res=>{
                    this.questions = res.data;
                })
        },
        get_module_chapter_data: function(target){
            let module_id = target.module_id.value;
            let chapter_id = target.chapter_id.value;
            this.url = `/question-bank/question?chapter_id=${chapter_id}&per_page=${this.per_page}&page=`;
            this.get_data();
        },

        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.modules = res.data;
                    this.get_chapters(this.modules[0].id);
                    this.selected_module = this.modules[0].name;
                    let that = this;

                    setTimeout(() => {
                        $('#module_id').select2();
                        $('#module_id').on('select2:select', function (e) {
                            var data = e.params.data;
                            // console.log(data.text);
                            that.selected_module = data.text;
                            that.get_chapters(data.id);
                            $('#module_and_chapter_form_btn').trigger('click');
                        });
                    }, 300);
                })
        },
        get_chapters: function(module_id=null){
            axios.get('/question-bank/chapter/get-all?module_id='+module_id)
                .then(res=>{
                    this.chapters = res.data;
                    let that = this;
                    setTimeout(() => {
                        $('#chapter_id').off().select2();
                        $('#chapter_id').val([]).trigger('change');
                        $('#chapter_id').val('');
                        $('#chapter_id').on('select2:select', function (e) {
                            var data = e.params.data;
                            that.selected_chapter = data.text;
                        });
                    }, 300);
                })
        },
        format_date: function(date){
            return window.formatDate(date,'date_time');
        },
        print_docs: function(element){
            let name = this.selected_module.replaceAll(' ','_')+" ";
            name += this.selected_chapter.replaceAll(' ','_')+" ";
            name += ` ${moment().format('DD-MMMM-YYYY hh-mm-ss A')}`;
            window.print_question_paper_docx(element,name);
        }
    }
}
</script>

<style>

</style>
