<template>
    <input @change="select_all($event.target);" id="select_all" type="checkbox"/>
</template>

<script>
export default {
    props: [
        'set_selected',
        'data'
    ],
    methods: {
        select_all: function(target){
            // console.log(target.checked);
            this.set_selected({
                selected_data: [],
                selected_data_list: [],
            });
            let selected_data = [];
            let selected_data_list = [];
            if(target.checked){
                this.data.data.forEach(item => {
                    selected_data.push(item.id);
                    selected_data_list.push({...item});
                    $(`#checkbox_${item.id}`).prop('checked',true);
                });
                this.set_selected({
                    selected_data,
                    selected_data_list,
                });
            }
            else{
                $(`tbody input[type="checkbox"]`).prop('checked',false);
            }
        },
    }
}
</script>

<style>

</style>
