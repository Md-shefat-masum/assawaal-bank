<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">
                    Question Paper list
                    <span v-if="data.total">
                        <small>( {{data.total}} )</small>
                    </span>
                </h5>
                <div style="max-width: 220px;">
                    <search-box :set_search_key="set_search_key" :search_data="search_data"></search-box>
                </div>
                <ul class="d-flex gap-2">
                    <!-- <li>
                        <router-link :to="{name:'questionPaperImport'}" class="btn btn-outline-primary"><i class="fa fa-upload"></i> Import</router-link>
                    </li> -->
                    <li>
                        <router-link :to="{name:'questionPaperCreate'}" class="btn btn-outline-secondary"><i class="fa fa-plus"></i> Create</router-link>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-center table-bordered">
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
                        <tr v-for="(item, index) in data.data" :key="item.id">
                            <td class="text-start">{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{format_date(item.created_at,'DD-MMMM-yyyy')}}</td>
                            <td>{{item.questions.length}}</td>
                            <td class="text-end pr-2" style="width: 90px;">
                                <div class="table_actions">
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <router-link :to="{name:'questionPaperDetails',params:{id:item.id}}">
                                                <i class="fa text-primary fa-list"></i>
                                                show details
                                            </router-link>
                                        </li>
                                        <li>
                                            <a href="#" @click.prevent="export_data(item)">
                                                <i class="fa text-secondary fa-download"></i>
                                                Export
                                            </a>
                                        </li>
                                        <li>
                                            <router-link :to="{name:'questionPaperEdit',params:{id:item.id}}">
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
    </section>
</template>

<script>
import SearchBox from '../components/SearchBox.vue';
export default {
    components: { SearchBox },
    created: function(){
        this.get_data();
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            url: '/question-bank/question-paper?page=',
        }
    },
    methods: {
        export_data: function(item){
            axios.post('/question-bank/question-paper/export',{data: item,get_data_by_id:item.id})
                .then(res=>{
                    this.download(location.origin+'/export.csv',item.name+'.csv');
                })
        },
        get_data: function(page = 1){
            this.page = page;
            axios.get(this.url+page)
                .then(res=>{
                    this.data = res.data;
                })
        },
        search_data: function(){
            this.url = '/question-bank/question-paper?key='+this.key+'&page=';
            this.get_data();
        },
        set_search_key: function(key){
            this.key = key;
        },
        remove: function(index, id){
            if(confirm('sure want to delete')){
                axios.post('/question-bank/question-paper/delete',{id})
                    .then(res=>{
                        this.data.data.splice(index,1);
                        window.s_alert('success', 'data deleted successfully');
                        this.data = res.data;
                    })
            }
        },
        format_date: function(date,format){
            return moment(date).format(format);
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
