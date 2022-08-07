<template>
    <section>
        <h5 class="text-capitalise mb-2 mt-4">Module &amp; Chapter Based Question List</h5>
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <form @submit.prevent="get_module_chapter_data($event.target)"  class="d-flex flex-wrap gap-3">
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
                </form>
                <ul class="d-flex gap-2">
                    <li>
                        <a href="#" @click.prevent="export_all" class="btn btn-outline-info"><i class="fa fa-download"></i> Export All </a>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-center table-bordered">
                    <thead>
                        <tr>
                            <th onclick="event.target.children.length ? event.target.children[0].click() : '' ">
                                <input @change="select_all($event);" id="select_all" type="checkbox">
                            </th>
                            <th>SI</th>
                            <th>Module</th>
                            <th class="has_event" @click="sort_data('chapter_name')">Chapter</th>
                            <th class="has_event" @click="sort_data('questions_count')">Total Question</th>
                            <th class="has_event" @click="sort_data('used_question_count')">Used</th>
                            <th class="has_event" @click="sort_data('intact_question_count')">Intact</th>
                            <th class="has_event" @click="sort_data('level_1_count')">Level 1</th>
                            <th class="has_event" @click="sort_data('level_2_count')">Level 2</th>
                            <th class="has_event" @click="sort_data('level_3_count')">Level 3</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.data" :key="item.id">
                            <td :key="index" onclick="event.target.children.length ? event.target.children[0].click() : '' ">
                                <input :id="`checkbox_${item.id}`" type="checkbox" @click="select_question($event,{...item})">
                            </td>
                            <td>{{item.module.id}}</td>
                            <td>{{item.module.name}}</td>
                            <td>{{item.chapter_name}}</td>
                            <td>{{item.questions_count}}</td>
                            <td>{{item.used_question_count}}</td>
                            <td>{{item.intact_question_count}}</td>
                            <td>{{item.level_1_count}}</td>
                            <td>{{item.level_2_count}}</td>
                            <td>{{item.level_3_count}}</td>
                            <td style="width: 90px;">
                                <div class="table_actions">
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <router-link :to="{name:'moduleDetails',params:{id:item.module.id}}">
                                                <i class="fa text-primary fa-list"></i>
                                                module details
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name:'chapterDetails',params:{id:item.id}}">
                                                <i class="fa text-info fa-list"></i>
                                                chapter details
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex gap-2">
                <pagination :data="data" :limit="5" @pagination-change-page="get_data"></pagination>
                <div class="d-flex gap-2">
                    <label for="per_page" style="line-height: 38px;">
                        Total :
                        <span v-if="data.total">
                            <small>( {{data.total}} )</small>
                        </span>
                    </label>
                    <label for="per_page" style="line-height: 38px;">Per page</label>
                    <input type="number" style="width: 92px;" class="form-control" v-model="per_page"  min="10">
                    <button class="btn btn-sm btn-outline-primary" @click.prevent="per_page>=10 && get_data()" style="line-height: 28px;">Submit</button>
                </div>
            </div>
        </div>

        <div class="card" v-if="selected_data_list.length">
            <div class="card-header d-flex flex-wrap gap-2 justify-content-between">
                <h4>Selected data ({{selected_data_list.length}})</h4>
                <ul class="d-flex gap-2">
                    <li>
                        <a href="#" @click.prevent="export_selected" class="btn btn-outline-info"><i class="fa fa-download"></i> Export </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="question_list_items table-responsive mt-4">
                    <table class="table table-bordered text-center table-hover">
                        <thead>
                            <tr>
                                <th class="p-0 w-0 border-right-0"></th>
                                <th>SI</th>
                                <th>Module</th>
                                <th>Chapter</th>
                                <th>Total Question</th>
                                <th>Used</th>
                                <th>Intact</th>
                                <th>Level 1</th>
                                <th>Level 2</th>
                                <th>Level 3</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in selected_data_list" :key="item.id">
                                <td class="p-0 w-0 border-right-0"></td>
                                <td>{{item.module.id}}</td>
                                <td>{{item.module.name}}</td>
                                <td>{{item.chapter_name}}</td>
                                <td>{{item.questions_count}}</td>
                                <td>{{item.used_question_count}}</td>
                                <td>{{item.intact_question_count}}</td>
                                <td>{{item.level_1_count}}</td>
                                <td>{{item.level_2_count}}</td>
                                <td>{{item.level_3_count}}</td>
                                <td style="width: 90px;">
                                    <div class="table_actions">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-gears"></i>
                                        </a>
                                        <ul>
                                            <li>
                                                <router-link :to="{name:'moduleDetails',params:{id:item.module.id}}">
                                                    <i class="fa text-primary fa-list"></i>
                                                    module details
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{name:'chapterDetails',params:{id:item.id}}">
                                                    <i class="fa text-info fa-list"></i>
                                                    chapter details
                                                </router-link>
                                            </li>
                                            <li>
                                                <a @click.prevent="
                                                        selected_data_list.splice(index,1);
                                                        selected_data.splice(index,1);
                                                        remove_check('#checkbox_'+item.id)
                                                    " href="#">
                                                    <i class="fa text-danger fa-trash"></i>
                                                    delete
                                                </a>
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
    </section>
</template>

<script>
import moment from 'moment';
export default {
    created: function(){
        this.url = `/question-bank/question/module-chapter-based-question?per_page=${this.per_page}&page=`;
        this.get_modules();
        this.get_data();
    },
    watch: {
        per_page: {
            handler: function(newV){
                this.url = `/question-bank/question/module-chapter-based-question?per_page=${newV}&page=`;
            }
        },
        data: {
            handler: function(){
                $('#select_all').prop('checked',false);
            },
            deep: true,
        }
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            per_page: 10,
            url: null,

            modules: [],
            chapters: [],

            selected_data: [],
            selected_data_list: [],

            sort_asc: true,
        }
    },
    methods: {
        select_all: function(event){
            this.selected_data = [];
            this.selected_data_list = [];

            if(event.target.checked){
                this.data.data.forEach(item => {
                    this.selected_data.push(item.id);
                    this.selected_data_list.push({...item});
                    $(`#checkbox_${item.id}`).prop('checked',true);
                });
            }else{
                $('table tbody input[type="checkbox"]').prop('checked',false);
            }
        },
        select_question: function(event,item){
            let check = this.selected_data.findIndex(i=>i==item.id);
            if(check >= 0){
                this.selected_data_list.splice(check,1);
                this.selected_data.splice(check,1);
                $('#select_all').prop('checked',false);
            }else{
                this.selected_data.push(item.id);
                this.selected_data_list.push({...item});
            }
            // console.log(this.selected_data, check, item);
        },
        remove_check: function(target){
            $(target).removeAttr('checked');
            $(target).prop('checked',false);
        },
        export_all: function(){
            axios.post('/question-bank/question/export-all-module-based')
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',`Module_and_Chapter_Based_Question_Data_Export_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);

                })
        },
        export_selected: function(){
            axios.post('/question-bank/question/export-all-module-based-selected',{data: this.selected_data_list})
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',`Module_and_Chapter_Based_Question_Data_Export_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);

                })
        },
        download: function (dataurl, filename) {
            const link = document.createElement("a");
            link.href = dataurl;
            link.download = filename;
            link.click();
        },
        get_data: function(page = 1){
            this.page = page;
            axios.get(this.url+page)
                .then(res=>{
                    this.data = res.data;
                })
        },
        get_module_chapter_data: function(target){
            let module_id = target.module_id.value;
            let chapter_id = target.chapter_id.value;
            this.url = `/question-bank/question/module-chapter-based-question?module_id=${module_id}&chapter_id=${chapter_id}&per_page=${this.per_page}&page=`;
            this.get_data();
        },
        search_data: function(){
            this.url = `/question-bank/question?per_page=${this.per_page}&key=`+this.key+'&page=';
            this.get_data();
        },
        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.modules = res.data;
                    this.get_chapters(this.modules[0].id);
                    let that = this;

                    setTimeout(() => {
                        $('#module_id').select2();
                        $('#module_id').on('select2:select', function (e) {
                            var data = e.params.data;
                            // console.log(data.id);
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
                     setTimeout(() => {
                        $('#chapter_id').off().select2();
                        $('#chapter_id').val([]).trigger('change');
                        $('#chapter_id').val('');
                    }, 300);
                })
        },
        sort_data: function(key){
            let that = this;
            this.data.data.sort((a, b) => {
                let nameA = a[key];
                let nameB = b[key];

                if(typeof a[key] != 'number'){
                    nameA = a[key]?.toUpperCase();
                    nameB = b[key]?.toUpperCase();
                }

                if (nameB < nameA) {
                    if(that.sort_asc){
                        return -1
                    }else{
                        return 1 ;
                    }
                }
                if (nameB > nameA) {
                    if(that.sort_asc){
                        return 1
                    }else{
                        return -1 ;
                    }
                }
                return 0;
            });
            that.sort_asc = !that.sort_asc;
        },
    }
}
</script>

<style scoped>
    table tr th{
        white-space: pre;
    }
    table tr td{
        white-space: unset;
    }
    table tr td:nth-child(1){
        width: 40px;
    }
    table tr td:nth-child(2){
        width: 90px;
    }
    table tr td:nth-child(3),
    table tr td:nth-child(4){
        width: 200px;
        white-space: unset;
    }
    table tr td:nth-child(5){
        width: 135px;
    }
    table tr td:nth-child(6),
    table tr td:nth-child(7),
    table tr td:nth-child(8),
    table tr td:nth-child(9),
    table tr td:nth-child(10)
    {
        width: 90px;
    }
</style>
