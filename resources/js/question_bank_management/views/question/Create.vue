<template>
    <section class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap justify-content-between">
                        <h4 class="text-capitalise">Create Question</h4>
                        <ul>
                            <li>
                                <a href="#/question" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="#" class="row" @submit.prevent="store($event)">
                            <div class="form-group mb-3 col-lg-4">
                                <label for="module_id"  class="text-capitalize">Select module</label>
                                <select name="module_id" id="module_id" class="form-control">
                                    <option v-for="s_module in modules" :value="s_module.id" :key="s_module.id">{{s_module.name}}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-lg-4">
                                <label for="chapter_id" class="text-capitalize">Select chapter</label>
                                <select name="chapter_id" id="chapter_id" class="form-control">
                                    <option v-for="chapter in chapters" :value="chapter.id" :key="chapter.id">{{chapter.chapter_name}}</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-lg-4">
                                <label for="question_pattern" class="text-capitalize">Question pattern</label>
                                <select id="question_pattern" v-model="question_pattern" name="question_pattern" class="form-control">
                                    <option value="descriptive">Descriptive</option>
                                    <option value="mcq">MCQ</option>
                                </select>
                            </div>
                            <div class="col-12"></div>
                            <div class="form-group mb-3 col-lg-12">
                                <label for="question_title" class="text-capitalize">
                                    Question title
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea type="text" id="question_title" rows="1" name="question_title" class="form-control"></textarea>

                                <label for="question_image" class="text-capitalize mt-3">
                                    image upload
                                </label> <br>
                                <input type="file" accept=".jpg,.jpeg,.png" id="question_image" name="question_image">
                            </div>

                            <div v-if="question_pattern =='mcq'" class="mb-3 row">
                                <div class="form-group mb-3 col-lg-4">
                                    <label for="option_1" class="text-capitalize">
                                        Option 1 <span v-if="question_pattern=='mcq'" class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" id="option_1" rows="1" name="option_1" class="form-control"></textarea>

                                    <label for="option_1_image" class="text-capitalize mt-3">
                                        image upload
                                    </label>
                                    <input type="file" accept=".jpg,.jpeg,.png" id="option_1_image" name="option_1_image">
                                </div>

                                <div class="form-group mb-3 col-lg-4">
                                    <label for="option_2" class="text-capitalize">
                                        Option 2 <span v-if="question_pattern=='mcq'" class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" class="form-control" id="option_2" rows="1" name="option_2"></textarea>

                                    <label for="option_2_image" class="text-capitalize mt-3">
                                        image upload
                                    </label>
                                    <input type="file" accept=".jpg,.jpeg,.png" id="option_2_image" name="option_2_image">
                                </div>

                                <div class="form-group mb-3 col-lg-4">
                                    <label for="option_3" class="text-capitalize">
                                        Option 3 <span v-if="question_pattern=='mcq'" class="text-danger">*</span>
                                    </label>
                                    <textarea type="text" id="option_3" rows="1" name="option_3" class="form-control"></textarea>

                                    <label for="option_3_image" class="text-capitalize mt-3">
                                        image upload
                                    </label>
                                    <input type="file" accept=".jpg,.jpeg,.png" id="option_3_image" name="option_3_image">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="answer" class="text-capitalize">
                                    Answer
                                    <span v-if="question_pattern=='mcq'" class="text-danger">*</span>
                                </label>
                                <textarea type="text" id="answer" rows="1" name="answer" class="form-control"></textarea>

                                <label for="answer_image" class="text-capitalize mt-3">
                                    image upload
                                </label> <br>
                                <input type="file" accept=".jpg,.jpeg,.png" id="answer_image" name="answer_image">
                            </div>

                            <div class="form-group mb-3">
                                <label for="part_66_reference" class="text-capitalize">
                                    part 66 reference
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea type="text" id="part_66_reference" rows="1" name="part_66_reference" class="form-control"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="training_note_reference" class="text-capitalize">
                                    training note reference
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea type="text" id="training_note_reference" rows="1" name="training_note_reference" class="form-control"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="prepared_by" class="text-capitalize">
                                    prepared by
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="prepared_by" rows="1" name="prepared_by" class="form-control"/>
                            </div>

                            <div class="form-group mb-3">
                                <label for="verified_by" class="text-capitalize">
                                    verified by
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="verified_by" rows="1" name="verified_by" class="form-control"/>
                            </div>

                            <div class="form-group mb-3">
                                <label for="level" class="text-capitalize">
                                    Level <span class="text-danger">*</span>
                                </label>
                                <select name="level" id="level" class="form-control">
                                    <option value="level_1">level 1</option>
                                    <option value="level_2">level 2</option>
                                    <option value="level_3">level 3</option>
                                </select>
                                <!-- <textarea type="text" id="level" rows="1" name="level" class="form-control"></textarea> -->
                            </div>

                            <div class="form-group mb-3">
                                <label for="created_at" class="text-capitalize">
                                    created time
                                    <span class="text-danger">*</span>
                                    <span class="text-info ps-1" @click="created_at = format_date()">Set</span>
                                </label>
                                <input type="datetime" v-model="created_at" id="created_at" name="created_at" class="form-control"/>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-info"><i class="fa fa-sign-in"></i> submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    created: function(){
        this.get_modules();
        this.question_pattern = 'descriptive';
        // this.get_chapters();
        this.created_at = this.format_date();
    },
    data: function(){
        return {
            modules: [],
            chapters: [],
            question_pattern: null,
            created_at: null,
        }
    },
    watch: {
        question_pattern: {
            handler: function(newv){
                setTimeout(() => {
                    window.init_preview_image();
                }, 300);
            }
        }
    },
    methods: {
        format_date: function(date){
            return window.formatDate(date,'YYYY-MM-DD HH:mm:ss');
        },
        get_modules: function(){
            axios.get('/question-bank/module/get-all')
                .then(res=>{
                    this.modules = res.data;
                    this.get_chapters(this.modules[0].id);
                    let that = this;

                    setTimeout(() => {
                        $('#module_id').select2();
                        $('#module_id').on('select2:select', function (e) {
                            var data = e.params.data;
                            // console.log(data.id);
                            that.get_chapters(data.id);
                        });
                    }, 300);
                })
        },
        get_chapters: function(module_id=null){
            axios.get('/question-bank/chapter/get-all?module_id='+module_id)
                .then(res=>{
                    this.chapters = res.data;
                     setTimeout(() => {
                        $('#chapter_id').off().select2();
                    }, 300);
                })
        },
        store: function(event){
            let form_data = new FormData(event.target);
            axios.post('/question-bank/question/store',form_data)
                .then(res=>{
                    this.$router.push({name:'questionAll'});
                    // console.log(res.data);
                })
        }
    }
}
</script>

<style>

</style>
