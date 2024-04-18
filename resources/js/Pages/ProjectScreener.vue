<script>
import { defineComponent } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import { VueReCaptcha, useReCaptcha } from 'vue-recaptcha-v3'

export default defineComponent({
    props: {
        project_screener: {
            type: Object,
            default: Object
        }
    },
    setup() {

        const form = useForm({
            name: null,
            email: null,
            phone: null,
            message: null,
            captcha_token: null,
        })

        const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()
        const recaptcha = async () => {
            await recaptchaLoaded()
            form.captcha_token = await executeRecaptcha('login')
            submit();
        }

        function submit() {
            form.post(route('contact-us.store'), {
                preserveScroll: true,
                onSuccess: () => console.log('success'),
            })
        }

        return {
            form, submit, recaptcha,
        }
    },
    data() {
        return {
            isEdit: false,
            form: this.$inertia.form({
                question_id: this.project_screener?.data?.id,
                project_id: this.project_screener?.data?.project_id || "",
                question: this.project_screener?.data?.question || "",
                question_type: this.project_screener?.data?.question_type || "",
                answer: "",

            }),

        };
    },
    components: {
        Link,
        JetInput,
        JetLabel,
        InputError,
        VueReCaptcha,
    },
    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.route("survey.answer-check"),
                    {
                        onSuccess: (data) => {
                            toast.success(this.$page.props.jetstream.flash.message);
                        },
                        onError: (data) => {
                            toast.error(data.message)
                        },
                    }
                );
        },
    },

});
</script>
<template>
    <div class="d-flex justify-content-center align-items-center my-5">
        <div class="col-6">
            <form action="#" method="POST" @submit.prevent="recaptcha">
                ...

                <input-error :message="form.errors.captcha_token" class="mt-2" />
            </form>
            <form @submit.prevent="submit()" autocomplete="off" class="d-flex flex-column flex-row-fluid gap-5">
                <div class="card" v-for="(project_screener, index) in project_screener.data" :key="index">
                    <div class="card-body">
                        <div class="mb-5">
                            <div class="text-gray-800 fw-semibold fs-4"> {{ project_screener?.question }}
                            </div>
                        </div>
                        <div class="mb-5">
                            <div class="d-flex flex-column gap-5">
                                <div class="d-flex  align-items-center" v-for="(answer, index) in project_screener?.answers"
                                    :key="index">
                                    <label class="form-check form-check-custom form-check-solid me-10">
                                        <input class="form-check-input h-20px w-20px"
                                            :type="`${project_screener?.question_type}`" name="answer[]"
                                            v-model="form.answer" :value="answer.answer" checked="checked" />
                                        <span class="form-check-label fw-semibold">{{ answer?.answer }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center gap-5">
                            <button type="submit" class="btn btn-primary btn-sm"
                                :class="{ 'text-white-50': form.processing }">
                                <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span>
                                    Submit <i class="bi bi-send-fill" />
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
