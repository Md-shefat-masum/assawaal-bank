<template>
    <section>
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h4 class="text-capitalise">All</h4>
                <ul>
                    <li>
                        <router-link :to="{name:'chapterCreate'}" class="btn btn-outline-secondary"><i class="fa fa-plus"></i> Create</router-link>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-center table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SI</th>
                            <th>Title</th>
                            <th>aa</th>
                            <th>aa</th>
                            <th>aa</th>
                            <th class="text-end pr-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in 10" :key="item">
                            <td class="text-start">1</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end pr-2" style="width: 90px;">
                                <div class="table_actions">
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-gears"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <router-link :to="{name:'chapterEdit'}">
                                                <i class="fa text-warning fa-pencil-square"></i>
                                                edit
                                            </router-link>
                                        </li>
                                        <li>
                                            <a href="#">
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
    </section>
</template>

<script>
export default {

}
</script>

<style>

</style>
