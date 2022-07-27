<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Edit Exam</h4>
                        <ul>
                            <li>
                                <a href="#/exam" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" class="row" @submit.prevent="store($event)">
                            <div class="form-group col-lg-4 mb-3">
                                <label for="name" class="text-capitalize">Exam Name</label>
                                <textarea type="text" id="name" rows="1" name="name" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <label for="class" class="text-capitalize">Class Name</label>
                                <textarea type="text" id="class" rows="1" name="class" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <label for="session" class="text-capitalize">Session</label>
                                <textarea type="text" id="session" rows="1" name="session" class="form-control"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label id="selected_question" class="text-capitalize">Select question paper</label>
                                <div class="d-flex flex-wrap mb-3 justify-content-center">
                                    <div style="max-width: 220px;">
                                        <form class="d-flex" action="" @submit.prevent="search_data">
                                            <input type="text" v-model="key" @keyup="!$event.target.value.length?search_data():()=>''" class="form-control" placeholder="search" >
                                            <button class="btn btn-outline-primary"><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover text-center table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-start"></th>
                                                <th class="text-start">SI</th>
                                                <th>Paper.Name</th>
                                                <th>Date</th>
                                                <th>Total.Question</th>
                                                <th class="text-end pr-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in data.data" :key="item.id*index+item.name">
                                                <td>
                                                    <input :id="`checkbox_${item.id}`" type="checkbox" @click="select_question($event,{...item})">
                                                </td>
                                                <td class="text-start">{{item.id}}</td>
                                                <td>{{item.name}}</td>
                                                <td>{{format_date(item.created_at,'DD-MMMM-yyyy')}}</td>
                                                <td>{{item.questions_count}}</td>
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
                                                                <router-link :to="{name:'questionPaperEdit',params:{id:item.id}}">
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
                                <div>
                                    <pagination :data="data" @pagination-change-page="get_data"></pagination>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-info"><i class="fa fa-sign-in"></i> submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" v-if="selected_question_list.length">
                    <div class="card-body">
                        <h4>Selected question paper ({{selected_question_list.length}})</h4>
                        <div class="question_list_items">
                            <table class="table table-bordered text-center table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-start">SI</th>
                                        <th>Paper.Name</th>
                                        <th>Date</th>
                                        <th>Total.Question</th>
                                        <th class="text-end pr-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(question, index) in selected_question_list" :key="question.id">
                                        <td class="text-start">{{question.id}}</td>
                                        <td>{{question.name}}</td>
                                        <td>{{format_date(question.created_at,'DD-MMMM-yyyy')}}</td>
                                        <td>{{question.questions_count}}</td>
                                        <td class="text-end pr-2" style="width: 90px;">
                                            <div class="table_actions">
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-gears"></i>
                                                </a>
                                                <ul>
                                                    <li>
                                                        <router-link :to="{name:'questionPaperDetails',params:{id:question.id}}">
                                                            <i class="fa text-primary fa-list"></i>
                                                            show details
                                                        </router-link>
                                                    </li>
                                                    <li>
                                                        <router-link :to="{name:'questionPaperEdit',params:{id:question.id}}">
                                                            <i class="fa text-warning fa-pencil-square"></i>
                                                            edit
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
        this.get_exam();
    },
    data: function(){
        return {
            data: {},
            page: 1,
            key: null,
            url: '/question-bank/question-paper?page=',

            selected_question: [],
            selected_question_list: [],
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
        get_exam: function(){
            axios.post('/question-bank/exam/get/'+this.$route.params.id)
                .then(res=>{
                    // console.log(res.data);
                    this.selected_question = res.data.selected_question;
                    this.selected_question_list = res.data.selected_question_list;

                    for (const key in res.data) {
                        if (Object.hasOwnProperty.call(res.data, key)) {
                            const element = res.data[key];
                            $(`#${key}`).val(element);
                        }
                    }

                    this.get_data();
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
            this.url = '/question-bank/question-paper?key='+this.key+'&page=';
            this.get_data();
        },
        store: function(event){
            let form_data = new FormData(event.target);
            form_data.append('selected_question',JSON.stringify(this.selected_question));
            form_data.append('id',this.$route.params.id);
            axios.post('/question-bank/exam/update',form_data)
                .then(res=>{
                    // this.$router.push({name:'examAll'});
                    // console.log(res.data);
                    window.s_alert('success','Information updated successfully');
                })
        },
        format_date: function(date,format){
            return moment(date).format(format);
        }
    }
}
</script>

<style>

</style>
