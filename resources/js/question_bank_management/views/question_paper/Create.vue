<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Create Question Paper</h4>
                        <ul>
                            <li>
                                <a href="#/question-paper" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" class="row" @submit.prevent="store($event)">
                            <div class="form-group mb-3 col-lg-4">
                                <label for="number" class="text-capitalize">Select module</label>
                                <select name="module_id" id="module_id" class="form-control">
                                    <option v-for="s_module in modules" :value="s_module.id" :key="s_module.id">{{s_module.name}}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-lg-4">
                                <label for="number" class="text-capitalize">Select chapter</label>
                                <select name="chapter_id" id="chapter_id" class="form-control">
                                    <option v-for="chapter in chapters" :value="chapter.id" :key="chapter.id">{{chapter.chapter_name}}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-lg-4 d-none">
                                <label for="okay" class="text-capitalize">Okay</label>
                                <select name="okay" id="okay" class="form-control">
                                    <option value="yes" selected>yes</option>
                                    <option value="no">no</option>
                                </select>
                            </div>
                            <div class="col-12"></div>
                            <div class="form-group mb-3 col-lg-12">
                                <label for="name" class="text-capitalize">Question Paper title</label>
                                <textarea type="text" id="name" rows="1" name="name" class="form-control"></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <div style="max-width: 220px;margin:auto;">
                                    <form class="d-flex" action="" @submit.prevent="search_data">
                                        <input type="text" v-model="key" @keyup="!$event.target.value.length?search_data():()=>''" class="form-control" placeholder="search" >
                                        <button class="btn ms-3 btn-outline-primary"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 table-responsive">
                                <label id="selected_question" class="text-capitalize">
                                    <b>Select Question</b>
                                </label>
                                <table class="table question_list_table question_list_table_selected_for_create_paper table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SI</th>
                                            <th>Question</th>
                                            <th>Option1</th>
                                            <th>Option2</th>
                                            <th>Option3</th>
                                            <th>Answer</th>
                                            <th>Reference</th>
                                            <th>Level</th>
                                            <th>Used Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item) in data.data" :key="item.id">
                                            <td onclick="event.target.children.length ? event.target.children[0].click() : '' ">
                                                <input :id="`checkbox_${item.id}`" type="checkbox" @click="select_question($event,{...item})">
                                            </td>
                                            <td>{{item.id}}</td>
                                            <td>{{item.question_title}}</td>
                                            <td>{{item.option_1}}</td>
                                            <td>{{item.option_2}}</td>
                                            <td>{{item.option_3}}</td>
                                            <td>{{item.answer}}</td>
                                            <td>{{item.reference}}</td>
                                            <td>{{item.level}}</td>
                                            <td>{{item.used_number}}</td>
                                            <td style="width: 90px;">
                                                <div class="table_actions">
                                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-gears"></i>
                                                    </a>
                                                    <ul>
                                                        <li>
                                                            <router-link :to="{name:'questionUsedDetails',params:{id:item.id}}">
                                                                <i class="fa text-primary fa-list"></i>
                                                                used details
                                                            </router-link>
                                                        </li>
                                                        <li>
                                                            <router-link :to="{name:'questionEdit',params:{id:item.id}}">
                                                                <i class="fa text-warning fa-pencil-square"></i>
                                                                edit
                                                            </router-link>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="9">
                                                <div class="d-flex gap-1">
                                                    <pagination :data="data" @pagination-change-page="get_data"></pagination>
                                                    <div class="d-flex gap-1">
                                                        <label for="per_page" style="line-height: 38px;">
                                                            Total :
                                                            <span v-if="data.total">
                                                                <small>( {{data.total}} )</small>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-center pt-3">
                                <button class="btn btn-info"><i class="fa fa-sign-in"></i> submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" v-if="selected_question_list.length">
                    <div class="card-body ">
                        <h4>Selected question list ({{selected_question_list.length}})</h4>
                        <div class="question_list_items table-responsive">
                            <table class="table question_list_table question_list_table_selected_for_create_paper_selected table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>SI</th>
                                        <th>Question</th>
                                        <th>Option1</th>
                                        <th>Option2</th>
                                        <th>Option3</th>
                                        <th>Answer</th>
                                        <th>Reference</th>
                                        <th>Level</th>
                                        <th>Used.Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(question, index) in selected_question_list" :key="question.id">
                                        <td style="width: 90px;">
                                            <div class="table_actions">
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-gears"></i>
                                                </a>
                                                <ul style="right: unset; left:100%;">
                                                    <li>
                                                        <router-link :to="{name:'questionUsedDetails',params:{id:question.id}}">
                                                            <i class="fa text-primary fa-list"></i>
                                                            used details
                                                        </router-link>
                                                    </li>
                                                    <li>
                                                        <a @click.prevent="
                                                                selected_question_list.splice(index,1);
                                                                selected_question.splice(index,1);
                                                                remove_check('#checkbox_'+question.id)
                                                            " href="#">
                                                            <i class="fa text-danger fa-trash"></i>
                                                            delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>{{question.id}}</td>
                                        <td>{{question.question_title}}</td>
                                        <td>{{question.option_1}}</td>
                                        <td>{{question.option_2}}</td>
                                        <td>{{question.option_3}}</td>
                                        <td>{{question.answer}}</td>
                                        <td>{{question.reference}}</td>
                                        <td>{{question.level}}</td>
                                        <td>{{question.used_question_count}}</td>
                                    </tr>
                                </tbody>
                            </table>
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
        this.get_modules();
        // this.get_chapters();
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            url: '/question-bank/question?page=',

            modules: [],
            chapters: [],
            question_pattern: 'descriptive',

            selected_question: [],
            selected_question_list: [],
            selected_chapter_id: null,
        }
    },
    methods: {
        select_question: function(event,item){
            let check = this.selected_question.findIndex(i=>i==item.id);
            if(check >= 0){
                this.selected_question_list.splice(check,1);
                this.selected_question.splice(check,1);
            }else{
                this.selected_question.push(item.id);
                this.selected_question_list.push({...item});
            }
            // console.log(this.selected_question, check, item);
        },
        remove_check: function(target){
            $(target).removeAttr('checked');
            $(target).prop('checked',false);
        },
        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.modules = res.data;
                    this.get_chapters(this.modules[0].id);
                    let that = this;

                    setTimeout(() => {
                        $('#module_id').off().select2();
                        $('#module_id').on('select2:select', function (e) {
                            var data = e.params.data;
                            // console.log(data.id);
                            that.selected_question_list = [];
                            that.selected_question = [];
                            that.get_chapters(data.id);
                        });
                    }, 300);
                })
        },
        get_chapters: function(module_id=null){
            axios.get('/question-bank/chapter/get-all?module_id='+module_id)
                .then(res=>{
                    this.chapters = res.data;

                    if(res.data.length){
                        this.url = '/question-bank/question?chapter_id='+res.data[0].id+'&page=';
                        this.get_data();
                        let that = this;
                        that.selected_chapter_id = res.data[0].id;

                        setTimeout(() => {
                            $('#chapter_id').off().select2();
                            $('#chapter_id').val([that.selected_chapter_id]).trigger('change');
                            $('#chapter_id').on('select2:select', function (e) {
                                var data = e.params.data;
                                // console.log(data.id);
                                that.selected_chapter_id = data.id;
                                that.url = '/question-bank/question?chapter_id='+data.id+'&page=';
                                that.get_data();
                            });
                        }, 300);
                    }
                })
        },
        store: function(event){
            let form_data = new FormData(event.target);
            form_data.append('selected_question',JSON.stringify(this.selected_question));
            axios.post('/question-bank/question-paper/store',form_data)
                .then(res=>{
                    window.s_alert('success','A new question paper created.');
                    $('#name').val('');
                    this.selected_question = [];
                    this.selected_question_list = [];
                    // $('input[type="checkbox"]').prop('check',false);
                    this.$router.push({name:'questionPaperAll'});
                    // console.log(res.data);
                })
        },
        get_data: function(page = 1){
            this.page = page;
            axios.get(this.url+page)
                .then(res=>{
                    this.data = res.data;
                    setTimeout(() => {
                        res.data.data.forEach(element => {
                            if(this.selected_question.includes(element.id)){
                                $(`#checkbox_${element.id}`).prop('checked',true);
                            }
                        });
                    }, 300);
                })
        },
        search_data: function(){
            this.url = `/question-bank/question?chapter_id=${this.selected_chapter_id}&key=`+this.key+'&page=';
            this.get_data();
        },
    }
}
</script>

<style>

</style>
