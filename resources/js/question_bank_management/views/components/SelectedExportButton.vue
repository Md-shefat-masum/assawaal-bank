<template>
    <ul class="d-flex gap-2">
        <li>
            <a href="#" @click.prevent="export_and_delete_selected" class="btn btn-outline-danger"><i class="fa fa-trash"></i> Export &amp; Delete</a>
        </li>
        <li>
            <a href="#" @click.prevent="export_selected" class="btn btn-outline-info"><i class="fa fa-download"></i> Export </a>
        </li>
    </ul>
</template>

<script>
export default {
    props: [
        'get_data',
        'api_url',
        'selected_data_list',
        'reset_selection',
    ],
    methods: {
        export_selected: function(){
            axios.post(`/question-bank/${this.api_url}/export-selected`,{data: this.selected_data_list})
                .then(res=>{
                    // console.log(res.data);
                    this.download(location.origin+'/export.csv',`${_.capitalize(this.api_url)}_List_Export_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);
                })
        },
        export_and_delete_selected: function(){
            if(confirm(_.capitalize('sure!! do you want to delete?'))){
                axios.post(`/question-bank/${this.api_url}/export-and-delete-selected`,{data: this.selected_data_list})
                    .then(res=>{
                        // console.log(res.data);
                        this.download(location.origin+'/export.csv',`${_.capitalize(this.api_url)}_List_Export_And_Deleted_${moment().format('DD-MMMM-YYYY hh-mm-ss A')}.csv`);
                        this.reset_selection();
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
    }
}
</script>

<style>

</style>
