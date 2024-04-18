<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { toast } from "vue3-toastify";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import Multiselect from "@vueform/multiselect";
import { Inertia } from "@inertiajs/inertia";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ['client', 'countries'],
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        Multiselect,
        Loading
    },
    validations() {
        return {
            form: {
                client_name: {
                    required,
                },
                display_name: {
                },
                description: {
                },
                status: {
                },
                website: {
                },
                skype_profile: {
                },
                linkedin_profile: {
                },
                tax_number: {
                },
            },
        };
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.client.data.id,
                client_name: this.client.data.name,
                display_name: this.client.data.display_name,
                website: this.client.data.website,
                skype_profile: this.client.data.skype_profile,
                linkedin_profile: this.client.data.linkedin_profile,
                tax_number: this.client.data.tax_number,
                description: this.client.data.description,
                status: this.client.data.status,
                action: 'overview'
            }),
            isEdit: false,
            isLoading: false,
            isFullPage: true,
            title: "Client Overview",
        };
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                }))
                    .post(this.route("client.update", this.form.id),
                        {
                            onSuccess: (data) => {
                                toast.success(this.$page.props.jetstream.flash.message);
                                this.isEdit = false;
                            },
                            onError: (data) => {
                                console.log(data);
                            },
                        });
            }
        },

        updateStatus(id, e) {
            this.isLoading = true;
            axios
                .post("/client/status", { id: id, status: e })
                .then((response) => {
                    if (response.data.success) {
                        toast.success(response.data.message);
                        Inertia.get('/client/' + id)
                        return;
                    }
                    toast.error(response.data.message);
                })
                .finally(() => (this.isLoading = false));
        },
    },
    created() {
    }
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />

        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/clients" class="text-muted text-hover-primary">Clients</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Client Overview
            </li>
        </template>
        <Header :client="client.data" />
        <div class="card mb-5 mb-xl-10">
            <div class="card-header cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Client Details </h3>
                </div>
                <button class="btn btn-primary btn-sm align-self-center"
                    @click="this.isEdit = this.isEdit ? false : true"><i class="bi bi-pencil me-2"></i>Edit Client
                </button>
            </div>
            <div class="card-body p-9">
                <div class="row" v-if="isEdit">
                    <div class="col-8">
                        <JetValidationErrors />
                        <form @submit.prevent="submit">
                            <div class="row g-5">
                                <div class="col-12 ">
                                    <div class="row mb-6">
                                        <jet-label for="client-name" class="col-4 required" value="Client Name" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="client-name" type="text" v-model="v$.form.client_name.$model"
                                                :class="v$.form.client_name.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Enter client full name" />
                                            <div v-for="(error, index) of v$.form.client_name.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <jet-label for="display-name" class="col-4" value="Display Name" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="display-name" type="text" v-model="v$.form.display_name.$model"
                                                :class="v$.form.display_name.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Enter display name" />
                                            <div v-for="(error, index) of v$.form.display_name.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <jet-label for="website-name" class="col-4" value="Client Website" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="website-name" type="text" v-model="v$.form.website.$model"
                                                :class="v$.form.website.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Enter website url" />
                                            <div v-for="(error, index) of v$.form.website.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <jet-label for="skype" class="col-4" value="Client Skype Profile" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="skype" type="text" v-model="v$.form.skype_profile.$model" :class="v$.form.skype_profile.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter skype profile" />
                                            <div v-for="(error, index) of v$.form.skype_profile.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <jet-label for="linkedin" class="col-4" value="Client LinkedIn Profile" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="linkedin" type="text" v-model="v$.form.linkedin_profile.$model"
                                                :class="v$.form.linkedin_profile.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Enter linkedin profile" />
                                            <div v-for="(error, index) of v$.form.linkedin_profile.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <jet-label for="tax-number" class="col-4" value="Tax number" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <jet-input id="tax-number" type="text" v-model="v$.form.tax_number.$model"
                                                :class="v$.form.tax_number.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Tax number" />
                                            <div v-for="(error, index) of v$.form.tax_number.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <jet-label for="client-name" class="col-4" value="Description" />
                                        <div class="col-8 fv-row fv-plugins-icon-container">
                                            <textarea class="form-control form-control-lg form-control-solid"
                                                id="description" type="text" v-model="v$.form.description.$model" :class="v$.form.description.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Enter description" />
                                            <div v-for="(error, index) of v$.form.description.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-5"
                                    @click="this.isEdit = false">Discard</button>
                                <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Client Name</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.name }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Display Name</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.display_name }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Client Website</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.website }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Client Skype Profile</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.skype_profile }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Client Linkedin Profile</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.linkedin_profile }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Tax number</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.tax_number }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Description</label>
                        <div class="col-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-700">{{ client.data.description }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-6 fw-bold fs-5 text-gray-800">Status</label>
                        <div class="col-6">
                            <div class="form-switch form-check-solid d-block form-check-custom form-check-success">
                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                    @input="updateStatus(client.data.id, $event.target.checked)"
                                    :checked="client.data.status == 1 ? true : false" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
