<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import Modal from "@/Components/Modal.vue";
import SectionLoader from "../../../../Components/SectionLoader.vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { toast } from "vue3-toastify";


export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ["question", 'project_id', "show", "id",],
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors,
        Modal,
        SectionLoader,
    },
    validations() {
        return {
            form: {
                question: {
                    required,
                },
                question_type: {
                    required
                },
                answers: {
                    $each: {
                        answer: { required },
                        mark_as_correct: { required }
                    }
                }
            },
        };
    },
    data() {
        return {
            rowCount: 1,
            answers: 1,
            isLoading: false,
            isFullPage: true,
            processing: false,
            form: this.$inertia.form({
                id: '',
                project_id: this.project_id,
                question: '',
                question_type: 'radio',
                is_required: false,
                answers: this.question?.data?.answers || [{
                    answer: '',
                    mark_as_correct: false,
                }],
            }),
            question_types: [
                { value: 'text', label: 'Text Input' },
                { value: 'radio', label: 'Radio' },
                { value: 'checkbox', label: 'CheckBox' },
                { value: 'select', label: 'Select' },
            ],
        };
    },
    methods: {
        async submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.processing = true;
                axios.post(this.id ? this.route("project.question-answer.update", this.id) : this.route("project.question-answer.store"), this.form)
                    .then((response) => {
                        console.log(response.data)
                        if (response.data.success) {
                            toast.success(response.data.message)
                            Inertia.get(`/project/${this.project_id}/question-answer`)
                        } else {
                            toast.info(response.data.message)
                        }
                        if (response.data.error) {
                            toast.error(response.data.error)
                        }
                    }).finally(() => {
                        this.$emit('hidemodal')
                        this.processing = false,
                            this.form = ''
                    })

            }
        },
        addMultipleAnsForm(rowCount) {
            for (var i = 0; i < rowCount; i++) {
                this.form.answers.push({
                    answer: '', nature: false
                });
            }
        },
        removeMultipleAnsForm(index) {
            if (this.form.answers.length > 0) {
                console.log(index)
                this.form.answers.splice(index, 1)
            }
        },

    },
    watch: {
        id: {
            async handler() {
                console.log("see this", this.id)
                this.isLoading = true;
                const response = await axios.get(`/project/question-answer/${this.id}/edit`);
                this.form = response?.data?.question;
                this.form.id = response.data?.question.id,
                    this.form.project_id = response.data?.question?.project_id,
                    this.form.question = response.data?.question?.question,
                    this.form.question_type = response.data?.question?.question_type,
                    this.form.is_required = response.data?.question?.is_required,
                    this.form.answers = response.data?.question?.answers,
                    this.isLoading = false;
            }
        }
    }
});
</script>
<template>
    <Modal :show="show" @onhide="$emit('hidemodal')" :title="id ? 'Edit Form' : 'Add Form'">
        <SectionLoader v-if="isLoading" :width="40" :height="40" />
        <div v-else>
            <JetValidationErrors />
            <form @submit.prevent="submit" autocomplete="off">
                <div class="row g-5">
                    <div class="col-md-6 col-sm-12">
                        <jet-label for="question-name" value="Question" />
                        <jet-input id="question-name" type="text" v-model="v$.form.question.$model" :class="v$.form.question.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " placeholder="Enter Question" />
                        <div v-for="(error, index) of v$.form.question.$errors" :key="index">
                            <input-error :message="error.$message" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 d-flex align-items-center">
                        <div class="form-check mt-5">
                            <input type="checkbox" class="form-check-input align-items-center" id="correctCheck"
                                v-model="form.is_required" />
                            <label class="form-check-label" for="correctCheck">Mark as required</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <jet-label value="Question type" />
                        <Multiselect :canClear="false" :options="question_types" label="label" valueProp="value"
                            class="form-control form-control-solid" placeholder="Select question type" :searchable="true"
                            v-model="form.question_type" :class="v$.form.question_type.$errors.length > 0
                                ? 'is-invalid'
                                : ''
                                " />
                    </div>
                    <div class="d-flex flex-column gap-5" v-if="form.question_type == 'text'">
                        <div v-for="(answer, index) in form?.answers" :key="index" class="row align-items-center">
                            <div class="col-8">
                                <input type="text" class="form-control form-control-solid"
                                    v-model="form.answers[index].answer" placeholder="Project Question Answer" />
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input align-items-center"
                                        v-model="form.answers[index].mark_as_correct" id="correctCheck" />
                                    <label class="form-check-label" for="correctCheck">Mark as true</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-5" v-else>
                        <div v-for="(answer, index) in form?.answers" :key="index" class="row align-items-center">
                            <div class="col-6">
                                <input type="text" class="form-control form-control-solid"
                                    v-model="form.answers[index].answer" placeholder="Project Question Answer" />
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input align-items-center"
                                        v-model="form.answers[index].mark_as_correct" id="correctCheck" />
                                    <label class="form-check-label" for="correctCheck">Mark as true</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <button :disabled="index < 1" type="button" class="btn btn-sm btn-icon btn-danger"
                                    @click="removeMultipleAnsForm(index)">
                                    <i class="bi bi-trash3 fs-2" />
                                </button>
                            </div>
                        </div>
                        <div class=" align-top fs-6 fw-bold text-gray-700">
                            <div class="text-primary pt-2">
                                <button type="button" class="btn btn-primary btn-sm mx-5"
                                    @click="addMultipleAnsForm(this.rowCount)"> <i class="bi bi-plus-lg fs-2" />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <Link type="button" class="btn btn-secondary me-5" :href="`/project/${project_id}/question-answer`">
                        Discard
                        </Link>
                        <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': processing }">
                            <div v-if="processing == true" class="spinner-border spinner-border-sm">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
