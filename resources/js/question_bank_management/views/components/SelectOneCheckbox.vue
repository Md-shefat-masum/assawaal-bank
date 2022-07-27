<template>
    <input
        :id="`checkbox_${item.id}`"
        type="checkbox"
        @click="select_question($event,item)"/>
</template>

<script>
export default {
    props:[
        'data',
        'item',
        'selected_data',
        'selected_data_list',
        'set_selected',
    ],
    methods: {
        select_question: function(event,item){
            let check = this.selected_data.findIndex(i=>i==item.id);
            let selected_data_list = [...this.selected_data_list];
            let selected_data = [...this.selected_data];
            if(check >= 0){
                selected_data_list.splice(check,1);
                selected_data.splice(check,1);
                $('#select_all').prop('checked',false);
            }else{
                selected_data.push(item.id);
                selected_data_list.push({...item});
            }
            this.set_selected({
                selected_data,
                selected_data_list
            });
        },
    }
}
</script>

<style>

</style>
