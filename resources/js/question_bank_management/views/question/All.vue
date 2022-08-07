<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">
                    Question list
                    <span v-if="type">( {{type}} )</span>
                </h5>
                <div style="max-width: 220px;">
                    <search-box :set_search_key="set_search_key" :search_data="search_data"></search-box>
                </div>
                <div class="d-flex gap-2">
                    <import-export-button
                        :module_question_export_link="
                            given_module_id?
                            `/question-bank/question/export-question-by-module?module_id=${given_module_id}`
                            :false"
                        :chapter_question_export_link="
                            given_chapter_id?
                            `/question-bank/question/export-question-by-chapter?chapter_id=${given_chapter_id}`
                            :false"
                        :page="'question'">
                    </import-export-button>
                    <ul class="d-flex gap-2">
                        <li>
                            <router-link :to="{name:'questionCreate'}" class="btn btn-outline-secondary"><i class="fa fa-plus"></i> Create</router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover question_list_table text-center table-bordered">
                    <thead>
                        <tr>
                            <th onclick="event.target.children.length ? event.target.children[0].click() : '' ">
                                <input @change="select_all($event.target);" id="select_all" type="checkbox">
                            </th>
                            <th class="has_event" @click="get_data_order_by('id')">SI</th>
                            <th class="has_event" @click="get_data_order_by('module_id')">Module</th>
                            <th class="has_event" @click="get_data_order_by('chapter_id')">Chapter</th>
                            <th class="has_event" @click="get_data_order_by('question_title')">Question</th>
                            <th class="has_event" @click="get_data_order_by('option_1')">Option1</th>
                            <th class="has_event" @click="get_data_order_by('option_2')">Option2</th>
                            <th class="has_event" @click="get_data_order_by('option_3')">Option3</th>
                            <th class="has_event" @click="get_data_order_by('answer')">Answer</th>
                            <th class="has_event" @click="get_data_order_by('reference')">Reference</th>
                            <th class="has_event" @click="get_data_order_by('level')">Level</th>
                            <th >Used Number</th>
                            <th class="has_event" @click="get_data_order_by('created_at')">Created</th>
                            <th class="has_event" @click="get_data_order_by('updated_at')">Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.data" :key="item.id">
                            <td  onclick="event.target.children.length ? event.target.children[0].click() : '' ">
                                <input :id="`checkbox_${item.id}`" type="checkbox" @click="select_question($event,{...item})">
                            </td>
                            <td>{{item.id}}</td>
                            <td>{{item.module.name}}</td>
                            <td>{{item.chapter.chapter_name}}</td>
                            <td>{{item.question_title}}</td>
                            <td>{{item.option_1}}</td>
                            <td>{{item.option_2}}</td>
                            <td>{{item.option_3}}</td>
                            <td>{{item.answer}}</td>
                            <td>{{item.reference}}</td>
                            <td>{{item.level}}</td>
                            <td>{{item.used_question_count}}</td>
                            <td>{{format_date(item.created_at)}}</td>
                            <td>{{format_date(item.updated_at)}}</td>
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
                                        <li>
                                            <a href="#" @click.prevent="remove(index, item.id)">
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
                        <a href="#" @click.prevent="export_and_delete_selected" class="btn btn-outline-danger"><i class="fa fa-trash"></i> Export &amp; Delete</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="export_selected" class="btn btn-outline-info"><i class="fa fa-download"></i> Export </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="question_list_items  table-responsive mt-4">
                    <table class="table question_list_table_selected question_list_table table-bordered text-center table-hover">
                        <thead>
                            <tr>
                                <th>SI</th>
                                <th>Module</th>
                                <th>Chapter</th>
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
                            <tr v-for="(item, index) in selected_data_list" :key="item.id">
                                <td>{{item.id}}</td>
                                <td>{{item.module.name}}</td>
                                <td>{{item.chapter.chapter_name}}</td>
                                <td>{{item.question_title}}</td>
                                <td>{{item.option_1}}</td>
                                <td>{{item.option_2}}</td>
                                <td>{{item.option_3}}</td>
                                <td>{{item.answer}}</td>
                                <td>{{item.reference}}</td>
                                <td>{{item.level}}</td>
                                <td>{{item.used_question_count}}</td>
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
import ImportExportButton from '../components/ImportExportButton.vue';
import SearchBox from '../components/SearchBox.vue';
export default {
    components: { ImportExportButton, SearchBox },
    props:[
        'given_module_id',
        'given_chapter_id',
    ],
    created: function(){
        // console.log(this.given_module_id);
        if(this.$route?.params?.type){
            let type = this.$route?.params?.type;
            this.type = type;
            this.url = `/question-bank/question?type=${type}&per_page=${this.per_page}&page=`;
        }else{
            this.url = `/question-bank/question?per_page=${this.per_page}&page=`;
        }
        this.get_data();
    },
    watch: {
        given_module_id: {
            handler: function(newV){
                this.url = `/question-bank/question?module_id=${newV}&per_page=${this.per_page}&page=`;
                this.get_data();
            }
        },
        given_chapter_id: {
            handler: function(newV){
                this.url = `/question-bank/question?chapter_id=${newV}&per_page=${this.per_page}&page=`;
                this.get_data();
            }
        },
        per_page: {
            handler: function(newV){
                this.url = `/question-bank/question?per_page=${newV}&page=`;
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

            type: null,

            order_by: {
                key: null,
                asc: true,
            },

            selected_data: [],
            selected_data_list: [],
        }
    },
    methods: {
        select_all: function(target){
            // console.log(target.checked);
            this.selected_data = [];
            this.selected_data_list = [];
            if(target.checked){
                this.data.data.forEach(item => {
                    this.selected_data.push(item.id);
                    this.selected_data_list.push({...item});
                    $(`#checkbox_${item.id}`).prop('checked',true);
                });
            }
            else{
                $(`tbody input[type="checkbox"]`).prop('checked',false);
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
            $('#select_all').prop('checked',false);
        },
        export_selected: function(){
            axios.post('/question-bank/question/export-selected',{data: this.selected_data_list})
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',`Question_List_Export_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);

                })
        },
        export_and_delete_selected: function(){
            if(confirm('sure want to delete??')){
                axios.post('/question-bank/question/export-and-delete-selected',{data: this.selected_data_list})
                    .then(res=>{
                        // console.log(res.data);
                        this.download(location.origin+'/export.csv',`Question_List_Export_And_Deleted_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);
                        this.selected_data = [];
                        this.selected_data_list = [];
                        this.get_data();
                    })
            }
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
        get_data_order_by: function(order_by){
            this.order_by = {
                key: order_by,
                type: !this.order_by.type,
            }

            this.url = `/question-bank/question?order_type=${this.order_by.type}&order_by=${this.order_by.key}&per_page=${this.per_page}`;
            if(this.key){
                this.url += `&key=`+this.key;
            }
            this.url += '&page=';
            this.get_data();
        },
        search_data: function(){
            this.url = `/question-bank/question?per_page=${this.per_page}&key=`+this.key+'&page=';
            this.get_data();
        },
        set_search_key: function(key){
            this.key = key;
        },
        remove: function(index, id){
            if(confirm('sure want to delete')){
                axios.post('/question-bank/question/delete',{id})
                    .then(res=>{
                        this.data.data.splice(index,1);
                        window.s_alert('success', 'data deleted successfully');
                        // this.data = res.data;
                    })
            }
        },
        format_date: function(date){
            return window.formatDate(date,'date_time');
        }
    }
}
</script>
