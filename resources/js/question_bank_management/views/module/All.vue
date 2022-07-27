<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">
                    Module list
                    <span v-if="data.total">
                        <small>( {{data.total}} )</small>
                    </span>
                </h5>
                <div style="max-width: 220px;">
                    <search-box :set_search_key="set_search_key" :search_data="search_data"></search-box>
                </div>
                <div class="d-flex gap-2">
                    <import-export-button :page="'module'"></import-export-button>
                    <ul class="d-flex gap-2">
                        <li>
                            <router-link :to="{name:'moduleCreate'}" class="btn btn-outline-secondary"><i class="fa fa-plus"></i> Create</router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table  table-hover text-center table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <input @change="select_all($event.target);" id="select_all" type="checkbox">
                            </th>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Module no</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.data" :key="item.id">
                            <td>
                                <input :id="`checkbox_${item.id}`" type="checkbox" @click="select_question($event,{...item})">
                            </td>
                            <td >{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.number}}</td>
                            <td class="" style="width: 90px;">
                                <div class="table_actions">
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <router-link :to="{name:'moduleDetails',params:{id:item.id}}">
                                                <i class="fa text-secondary fa-list"></i>
                                                details
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name:'moduleEdit',params:{id:item.id}}">
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
            <div class="card-footer">
                <pagination :data="data" :limit="5" :show-disabled="true" @pagination-change-page="get_data"></pagination>
            </div>
        </div>

        <div class="card" v-if="selected_data_list.length">
            <div class="card-header d-flex flex-wrap gap-2 justify-content-between">
                <h4>Selected data ({{selected_data_list.length}})</h4>
                <selected-export-button :reset_selection="reset_selection" :get_data="get_data" :api_url="`module`" :selected_data_list="selected_data_list"></selected-export-button>
            </div>
            <div class="card-body">
                <div class="question_list_items table-responsive mt-4">
                    <table class="table table-bordered text-center table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Module no</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in selected_data_list" :key="item.id">
                                <td>{{item.id}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.number}}</td>
                                <td class="" style="width: 90px;">
                                    <div class="table_actions">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-gears"></i>
                                        </a>
                                        <ul>
                                            <li>
                                                <router-link :to="{name:'moduleDetails',params:{id:item.id}}">
                                                    <i class="fa text-secondary fa-list"></i>
                                                    details
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{name:'moduleEdit',params:{id:item.id}}">
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
import ImportExportButton from '../components/ImportExportButton.vue';
import SearchBox from '../components/SearchBox.vue';
import SelectedExportButton from '../components/SelectedExportButton.vue';
export default {
    components: { SelectedExportButton, ImportExportButton, SearchBox },
    created: function(){
        this.get_data();
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            url: '/question-bank/module?page=',

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
        reset_selection: function(){
            this.selected_data = [];
            this.selected_data_list = [];
            $('#select_all').prop('checked',false);
            $(`tbody input[type="checkbox"]`).prop('checked',false);
        },

        get_data: function(page = 1){
            this.page = page;
            axios.get(this.url+page)
                .then(res=>{
                    this.data = res.data;
                })
        },
        search_data: function(){
            this.url = '/question-bank/module?key='+this.key+'&page=';
            this.get_data();
        },
        set_search_key: function(key){
            this.key = key;
        },
        remove: function(index, id){
            if(confirm('Sure!! Do You Want to Delete? \nThe module is connected with chapter, question and question papers.')){
                axios.post('/question-bank/module/delete',{id})
                    .then(res=>{
                        this.data.data.splice(index,1);
                        window.s_alert('success', 'data deleted successfully');
                        // this.data = res.data;
                    })
            }
        }
    }
}
</script>
