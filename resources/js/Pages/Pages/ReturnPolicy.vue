<script>
import { defineComponent } from 'vue';
import AppLayout from '../../Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Header from "../../Pages/Pages/Components/Header.vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import JetLabel from "@/Jetstream/Label.vue";
import useVuelidate from "@vuelidate/core";
import { required } from '@vuelidate/validators';
import { toast } from "vue3-toastify";

import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";


export default defineComponent({
    props: {
        return_policy: {
            type: Object,
            default: null
        }
    },
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                return_policy: {

                }
            }
        }
    },
    components: {
        AppLayout,
        Head,
        Link,
        Header,
        ClassicEditor,
        JetLabel,
        JetValidationErrors

    },
    data() {
        return {
            title: "Pages",
            editor: ClassicEditor,
            form: this.$inertia.form({
                id: this.return_policy?.id || '',
                return_policy: this.return_policy?.content || '',
            })

        }
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form
                    .transform((data) => ({
                        ...data,
                    }))
                    .post(this.route('page.return-policy.store', this.id),
                        {
                            onSuccess: (data) => {
                                console.log("hello", this.$page)
                                toast.success(this.$page.props.jetstream.flash.message);
                            },
                            onError: (data) => {
                                console.log(data);
                                toast.error(data.message)
                            },
                        })
            }
        }
    }

});
</script>


<template>
    <AppLayout :title="title">
        <template #breadcrumb>

            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted text-hover-primary">{{ title }}</span>
            </li>
        </template>

        <Head :title="title" />
        <Header />

        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-12">
                <JetValidationErrors />
                <form @submit.prevent="submit()" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="">
                        <div class="row g-5">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Return Policy</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="fv-row col-12">
                                            <ckeditor :editor="editor" v-model="v$.form.return_policy.$model"
                                                class="form-control form-control-solid" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end text-align-center gap-5">

                                <div>
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span>Save</span>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </AppLayout>
</template>
