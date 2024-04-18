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
import { required, email } from "@vuelidate/validators";
import { toast } from "vue3-toastify";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";


export default defineComponent({
    props: ['supplier', 'countries'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {

                country: {
                    required
                },
                supplier_name: {
                    required,
                },
                display_name: {
                    required,
                },
                email_address: {
                    required, email,
                },
                website: {
                },
                skype_profile: {
                },
                linkedin_profile: {
                },
                description: {
                },
                status: {
                    required,
                },

            }
        };
    },
    data() {
        return {
            isEdit: false,
            processing: false,
            title: "Overview",
            form: this.$inertia.form({
                id: this.supplier?.data?.id || '',
                supplier_name: this.supplier?.data?.supplier_name || '',
                display_name: this.supplier?.data?.display_name || '',
                email_address: this.supplier?.data?.email_address || '',
                website: this.supplier?.data?.website || '',
                skype_profile: this.supplier?.data?.skype_profile || '',
                linkedin_profile: this.supplier?.data?.linkedin_profile || '',
                description: this.supplier?.data?.description || '',
                country: this.supplier?.data?.country?.id || '',
                status: this.supplier?.data?.status || 0,
                action: "supplier.overview",
            }),
            status: [
                { name: 'Active', id: '1' },
                { name: 'Inactive', id: '0' },
            ]
        };
    },
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        Multiselect,
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
                        .post(this.route('supplier.update', this.form.id),
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
                    <h3 class="fw-bold m-0">Manage Supplier </h3>
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

                            <div class="col-6">
                                <jet-label for="supplier_name" value="Supplier Name" />
                                <jet-input id="supplier_name" type="supplier_name" v-model="v$.form.supplier_name.$model"
                                    :class="v$.form.supplier_name.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Supplier Name" />
                                <div v-for="(error, index) of v$.form.supplier_name.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>

                            <div class="col-6">
                                <jet-label for="display_name" value="Display Name" />
                                <jet-input id="display_name" type="text" v-model="v$.form.display_name.$model" :class="v$.form.display_name.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Enter supplier Display Name" />
                                <div v-for="(error, index) of v$.form.display_name.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-6">
                                <jet-label for="email_address" value="Email Address" />
                                <jet-input id="email_address" type="text" v-model="v$.form.email_address.$model" :class="v$.form.email_address.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Email Address" />
                                <div v-for="(error, index) of v$.form.email_address.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-6">
                                <jet-label for="website" value="Website" />
                                <jet-input id="website" type="text" v-model="v$.form.website.$model" :class="v$.form.website.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Website" />
                                <div v-for="(error, index) of v$.form.website.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-6">
                                <jet-label for="skype_profile" value="Skype Profile" />
                                <jet-input id="skype_profile" type="text" v-model="v$.form.skype_profile.$model" :class="v$.form.skype_profile.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " placeholder="Skype Profile" />
                                <div v-for="(error, index) of v$.form.skype_profile.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>

                            <div class="col-6">
                                <jet-label for="linkedin_profile" value="LinkedIn Profile" />
                                <jet-input id="linkedin_profile" type="text" v-model="v$.form.linkedin_profile.$model"
                                    :class="v$.form.linkedin_profile.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="LinkedIn Profile" />
                                <div v-for="(error, index) of v$.form.linkedin_profile.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-6">
                                <jet-label for="country" value="Country" />
                                <Multiselect :can-clear="false" :options="countries.data" label="display_name"
                                    valueProp="id" class="form-control form-control-lg form-control-solid"
                                    placeholder="Select Country" v-model="form.country" track-by="display_name"
                                    :searchable="true" :class="v$.form.country.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " />
                                <div v-for="(error, index) of v$.form.country.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>

                            </div>
                            <div class="col-6">
                                <jet-label for="status" value="Status" />
                                <Multiselect :can-clear="false" :options="status" label="name" valueProp="id"
                                    class="form-control form-control-lg form-control-solid" placeholder="Select One"
                                    v-model="form.status" track-by="name" :class="v$.form.status.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " />
                                <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-12">
                                <jet-label for="description" value="Description" />
                                <textarea id="description" class="form-control form-control-lg form-control-solid"
                                    type="text" v-model="v$.form.description.$model" :class="v$.form.description.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Type description here..." />
                                <div v-for="(error, index) of v$.form.description.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                        </div>
                        <div class="row text-align-center p-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-5">
                                    <button type="button" class="btn btn-secondary"
                                        @click="this.isEdit = false">Discard</button>

                                    <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': processing }">
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
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Supplier Name</label>
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700 text-capitalize">
                                {{ this.supplier?.data?.supplier_name }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Display Name</label>
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700 text-capitalize">{{ this.supplier?.data?.display_name }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Email Address</label>
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700 text-capitalize">{{ this.supplier?.data?.email_address
                            }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Website
                        </label>
                        <div class="col-lg-6 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-700">{{ this.supplier?.data?.website }} </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Skype ID</label>
                        <div class="col-lg-6">
                            <span class="fw-bold fs-6 text-gray-700">{{
                                this.supplier?.data?.skype_profile }} </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Linked Profile ID</label>
                        <div class="col-lg-6">
                            <span class="fw-bold fs-6 text-gray-700">
                                {{ this.supplier?.data?.linkedin_profile }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Country</label>
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ this.supplier?.data?.country?.display_name }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Status</label>
                        <div class="col-lg-6">
                            <span class="badge badge-success" v-if="this.supplier?.data?.status == 1">Active</span>
                            <span class="badge badge-danger" v-if="this.supplier?.data?.status == 0">Inactive</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-6 fw-bold fs-5 text-gray-800">Description</label>
                        <div class="col-lg-6">
                            <span class="fw-bold fs-6 text-gray-700">{{ this.supplier?.data?.description }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
