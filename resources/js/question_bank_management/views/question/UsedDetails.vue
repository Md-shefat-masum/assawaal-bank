<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">Question Used Details</h5>
                <ul>
                    <li>
                        <router-link :to="{name:'questionAll'}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</router-link>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table">
                    <tr>
                        <td class="p-4" style="width: 200px; vertical-align: top;">Question Name</td>
                        <td style="width:10px">:</td>
                        <td>
                            {{data.question_title}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4" style="width: 200px; vertical-align: top;">Used in</td>
                        <td style="width:10px; vertical-align: top;">:</td>
                        <td>
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
                                    <tr v-for="(item) in data.used_question" :key="item.id">
                                        <td class="text-start">{{item.question_paper.id}}</td>
                                        <td>{{item.question_paper.name}}</td>
                                        <td>
                                            {{
                                                item.question_paper && item.question_paper.created_at
                                                ?
                                                format_date(item.question_paper.created_at,'DD-MMMM-yyyy')
                                                : ''
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.question_paper && item.question_paper.questions_count?
                                                item.question_paper.questions_count
                                                :''
                                            }}
                                        </td>
                                        <td class="text-end pr-2" style="width: 90px;">
                                            <div class="table_actions" v-if="item.question_paper">
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-gears"></i>
                                                </a>
                                                <ul>
                                                    <li>
                                                        <router-link :to="{name:'questionPaperDetails',params:{id:item.question_paper.id}}">
                                                            <i class="fa text-primary fa-list"></i>
                                                            show details
                                                        </router-link>
                                                    </li>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    created: function(){
        this.get_used_details();
    },
    data: function(){
        return {
            data: {},
        }
    },
    methods: {
        get_used_details: function(){
            axios.get('/question-bank/question/get-used-details/'+this.$route.params.id)
                .then(res=>{
                    this.data = res.data;
                    console.log(res.data);
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
