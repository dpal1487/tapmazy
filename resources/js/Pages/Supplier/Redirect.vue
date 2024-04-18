<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import useVuelidate from "@vuelidate/core";
import { required, url } from "@vuelidate/validators";
import { toast } from "vue3-toastify";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";


export default defineComponent({
    props: ['supplier', 'redirect'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                complete_url: {
                    required, url
                },
                terminate_url: {
                    required, url
                },
                quotafull_url: {
                    required, url
                },
                security_terminate_url: {
                    required, url
                }
            }
        };
    },
    data() {
        return {
            isEdit: false,
            processing: false,
            title: "Redirect",
            form: this.$inertia.form({
                id: this.redirect?.data?.id || '',
                supplier_id: this.supplier?.data?.id || '',
                complete_url: this.redirect?.data?.complete_url || '',
                terminate_url: this.redirect?.data?.terminate_url || '',
                quotafull_url: this.redirect?.data?.quotafull_url || '',
                security_terminate_url: this.redirect?.data?.security_terminate_url || '',
                action: "supplier.redirect",
            }),
        };
    },
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.processing = true,
                    this.form.transform((data) => ({
                        ...data,
                    }))
                        .post(this.route('supplier.redirect.update', this.form.id),
                            {
                                onSuccess: (data) => {
                                    toast.success(this.$page.props.jetstream.flash.message);
                                    this.processing = false,
                                        this.isEdit = false;
                                },
                                onError: (data) => {
                                    toast.error(data.message)
                                },
                            });
            }
        },
    },
});
</script>
<template>
    <Head :title="title" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/suppliers" class="text-muted text-hover-primary">Suppliers</Link>

            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>
        <Header :supplier="supplier?.data" />
        <div class="card mb-5 mb-xl-10">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Supplier Redirect </h3>
                </div>
                <button class="btn btn-primary btn-sm align-self-center"
                    @click="this.isEdit = this.isEdit ? false : true"><i class="bi bi-pencil me-2"></i>Edit
                </button>
            </div>
            <div class="card-body">

                <div class="row" v-if="isEdit">
                    <JetValidationErrors />
                    <form @submit.prevent="submit()" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <jet-label for="complete_url" value="Complete URL" />
                                <jet-input id="complete_url" type="text" v-model="v$.form.complete_url.$model" :class="v$.form.complete_url.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Enter supplier Complete URL" />
                                <div v-for="(error, index) of v$.form.complete_url.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <jet-label for="terminate_url" value="Terminate URL" />
                                <jet-input id="terminate_url" type="text" v-model="v$.form.terminate_url.$model" :class="v$.form.terminate_url.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Terminate URL" />
                                <div v-for="(error, index) of v$.form.terminate_url.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <jet-label for="quotafull_url" value="Quotafull URL" />
                                <jet-input id="quotafull_url" type="text" v-model="v$.form.quotafull_url.$model" :class="v$.form.quotafull_url.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Quotafull URL" />
                                <div v-for="(error, index) of v$.form.quotafull_url.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <jet-label for="security_terminate_url" value="Security Terminate URL" />
                                <jet-input id="security_terminate_url" type="text"
                                    v-model="v$.form.security_terminate_url.$model" :class="v$.form.security_terminate_url.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Security Terminate URL" />
                                <div v-for="(error, index) of v$.form.security_terminate_url.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                        </div>
                        <div class="row text-align-center p-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-5">
                                    <button type="button" class="btn btn-secondary"
                                        @click="this.isEdit = false">Discard</button>


                                        <button type="submit" class="btn btn-primary"
                                            :class="{ 'text-white-50': processing }">
                                            <div v-show="processing" class="spinner-border spinner-border-sm">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span>Save Changes</span>
                                        </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div v-else>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Complete Url</label>
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700 text-capitalize">{{ this.redirect?.data?.complete_url
                            }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Terminate Url
                        </label>
                        <div class="col-lg-6 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-700">{{ this.redirect?.data?.terminate_url }} </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Quotafull Url</label>
                        <div class="col-lg-6">
                            <span class="fw-bold fs-6 text-gray-700">{{
                                this.redirect?.data?.quotafull_url }} </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Security Terminate Url</label>
                        <div class="col-lg-6">
                            <span class="fw-bold fs-6 text-gray-700">{{ this.redirect?.data?.security_terminate_url
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
