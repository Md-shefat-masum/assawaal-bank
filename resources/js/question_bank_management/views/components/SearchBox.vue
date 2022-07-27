<template>
    <form class="d-flex gap-2" action="" @submit.prevent="search_data">
        <input type="text" @keydown="set_search_key($event.target.value)" @keyup="!$event.target.value.length?search_data():()=>''" class="form-control border border-secondary" placeholder="search" >
        <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
    </form>
</template>

<script>
export default {
    props: [
        'set_search_key',
        'search_data'
    ]
}
</script>

<style>

</style>
