<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">
                    Chapter list
                    <span v-if="data.total">
                        <small>( {{data.total}} )</small>
                    </span>
                </h5>
                <div style="max-width: 220px;">
                    <search-box :set_search_key="set_search_key" :search_data="search_data"></search-box>
                </div>
                <div class="d-flex gap-2">
                    <import-export-button :page="'chapter'"></import-export-button>
                    <ul class="d-flex gap-2">
                        <li>
                            <router-link :to="{name:'chapterCreate'}" class="btn btn-outline-secondary"><i class="fa fa-plus"></i> Create</router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body table-responsive" style="height: 60vh; overflow-y: scroll;">
                <table class="table table-hover text-center table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <select-all-checkbox
                                    :set_selected="set_selected"
                                    :data="data">
                                </select-all-checkbox>
                            </th>
                            <th>SL</th>
                            <th>Module</th>
                            <th>Chapter</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.data" :key="item.id">
                            <td>
                                <select-one-checkbox
                                    :data="data"
                                    :item="{...item}"
                                    :selected_data="selected_data"
                                    :selected_data_list="selected_data_list"
                                    :set_selected="set_selected"
                                ></select-one-checkbox>
                            </td>
                            <td>{{item.id}}</td>
                            <td>
                                <span v-if="item.module">
                                    {{item.module.name}}
                                </span>
                                <span class="p-1 text-capitalize bg-danger text-light" v-else>
                                    module was deleted
                                </span>
                            </td>
                            <td>{{item.chapter_name}}</td>
                            <td class="" style="width: 90px;">
                                <div class="table_actions">
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <router-link :to="{name:'chapterDetails',params:{id:item.id}}">
                                                <i class="fa text-primary fa-list"></i>
                                                details
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link :to="{name:'chapterEdit',params:{id:item.id}}">
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
                <pagination :data="data" :limit="5" @pagination-change-page="get_data"></pagination>
            </div>
        </div>

        <div class="card" v-if="selected_data_list.length">
            <div class="card-header d-flex flex-wrap gap-2 justify-content-between">
                <h4>Selected data ({{selected_data_list.length}})</h4>
                <selected-export-button
                    :reset_selection="reset_selection"
                    :get_data="get_data"
                    :api_url="`chapter`"
                    :selected_data_list="selected_data_list"/>
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
                                <td>
                                    <span v-if="item.module">
                                        {{item.module.name}}
                                    </span>
                                    <span class="p-1 text-capitalize bg-danger text-light" v-else>
                                        module was deleted
                                    </span>
                                </td>
                                <td>{{item.chapter_name}}</td>
                                <td class="" style="width: 90px;">
                                    <div class="table_actions">
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-gears"></i>
                                        </a>
                                        <ul>
                                            <li>
                                                <router-link :to="{name:'chapterDetails',params:{id:item.id}}">
                                                    <i class="fa text-primary fa-list"></i>
                                                    details
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{name:'chapterEdit',params:{id:item.id}}">
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
import SelectAllCheckbox from '../components/SelectAllCheckbox.vue';
import SelectedExportButton from '../components/SelectedExportButton.vue';
import SelectOneCheckbox from '../components/SelectOneCheckbox.vue';
export default {
    components: { ImportExportButton, SelectedExportButton, SearchBox, SelectAllCheckbox, SelectOneCheckbox },
    created: function(){
        this.get_data();
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            url: '/question-bank/chapter?page=',

            selected_data: [],
            selected_data_list: [],
        }
    },
    methods: {
        set_selected: function(set_selected){
            this.selected_data = set_selected.selected_data;
            this.selected_data_list = set_selected.selected_data_list;
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
            this.url = '/question-bank/chapter?key='+this.key+'&page=';
            this.get_data();
        },
        set_search_key: function(key){
            this.key = key;
        },
        remove: function(index, id){
            if(confirm('Sure!! Do want to delete?\nThe chapter is connected with question and question papers.')){
                axios.post('/question-bank/chapter/delete',{id})
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

<style scoped>
    table th, table td {
        white-space: unset;
    }
</style>
