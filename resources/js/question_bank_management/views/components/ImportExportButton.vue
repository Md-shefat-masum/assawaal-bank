<template>
    <ul class="d-flex gap-2">
        <li>
            <a href="#" @click.prevent="export_all" class="btn btn-outline-success"><i class="fa fa-download"></i> Export</a>
        </li>
        <li>
            <router-link :to="{name:`${page}Import`}" class="btn btn-outline-primary"><i class="fa fa-upload"></i> Import</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    props: [
        'module_question_export_link',
        'chapter_question_export_link',
        'page',
    ],
    watch: {
        module_question_export_link:{
            handler: function(newV){
                this.url = newV
            }
        },
        chapter_question_export_link:{
            handler: function(newV){
                this.url = newV
            }
        },
    },
    data: function(){
        return {
            url: null,
        }
    },
    created: function(){
        this.url = `/question-bank/${this.page}/export-all`;
    },
    methods:{
        export_all: function(){
            axios.post(this.url)
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',`${_.capitalize(this.page)}_List_Export_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);
                })
        },
        download: function (dataurl, filename) {
            const link = document.createElement("a");
            link.href = dataurl;
            link.download = filename;
            link.click();
        },
    }
}
</script>

<style>

</style>
