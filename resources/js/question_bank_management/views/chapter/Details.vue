<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5 class="text-capitalise">Chapter details</h5>
                <ul>
                    <li>
                        <router-link :to="{name:'chapterAll'}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</router-link>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table details_table">
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Name</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.chapter_name}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Total Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.total_question}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Used Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.used}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Intact Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.intact}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Level 1 Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.level_1}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Level 2 Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.level_2}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 text-bold" style="width: 200px; vertical-align: top;">Level 3 Question</td>
                        <td style="width:50px">:</td>
                        <td>
                            {{data.level_3}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <all :given_chapter_id="data.id"></all>
    </section>
</template>

<script>
import All from '../question/All.vue';
export default {
    components: {
        All,
    },
    created: function () {
        this.get_data();
    },
    data: function () {
        return {
            data: {},
        };
    },
    methods: {
        get_data: function () {
            axios.post("/question-bank/chapter/get/" + this.$route.params.id, { type: "with_details" })
                .then(res => {
                // console.log(res.data);
                this.data = res.data;
            });
        },
    },
    components: { All }
}
</script>

<style>
.details_table tr td:first-child{
    font-weight: 600;
    text-transform: capitalize;
}
</style>
