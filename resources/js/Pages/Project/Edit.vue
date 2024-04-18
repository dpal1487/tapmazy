<script>
import { defineComponent } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { toast } from "vue3-toastify";
export default defineComponent({
    props: ["clients", "countries", "status", "project"],
    setup() {
        return { v$: useVuelidate() };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        VueDatePicker,
    },
    validations() {
        return {
            form: {
                project_name: {
                    required,
                },
                client: {
                    required,
                },
                start_date: {
                    required,
                },
                end_date: {
                    required,
                },
                project_status: {
                    required,
                },
                target: {
                },
                device_type: {
                    required,
                },
                project_type: {
                    required,
                },
            },
        };
    },
    data() {
        return {
            processing: false,
            form: this.$inertia.form({
                id: this.project?.data?.id,
                project_name: this.project?.data?.project_name,
                client: this.project?.data?.client?.id,
                start_date: this.project?.data?.start_date || '',
                end_date: this.project?.data?.end_date || '',
                project_status: this.project?.data?.status,
                target: this.project?.data?.target,
                device_type: JSON.parse(this.project?.data?.device_type) || [],
                project_type: this.project?.data?.project_type,
                page: 'project-edit',
                action: this.getSegment(4)
            }),
            devices: [
                {
                    label: "Desktop/Laptop",
                    value: "desktop",
                },
                {
                    label: "Mobile",
                    value: "mobile",
                },
                {
                    label: "Tablet",
                    value: "tablet",
                },
            ],
        };
    },
    methods: {
        submit() {
            this.v$.$touch();
            this.processing = true;

            if (!this.v$.form.$invalid) {
                this.form
                    .transform((data) => ({
                        ...data,
                    }))
                    .post(this.route('project.update', this.form.id), {
                        onSuccess: (data) => {
                            toast.success(this.$page.props.jetstream.flash.message);
                            this.processing = false

                        },
                        onError: (data) => {
                            if (data.message) {
                                toast.error(data.message)
                            }
                            else {
                                // console.log(data)
                            }
                        },
                    });
            }
        },

        getSegment(numberSegment) {
            let path = window.location.pathname;
            let segments = path.split("/");
            return segments[numberSegment];
        },

    },
    created() {
    },
});
</script>
<template>
    <Head title="Edit Project" />
    <app-layout title="Edit Project">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="/projects/" class="text-muted text-hover-primary">Projects</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Edit Project Form
            </li>
        </template>
        <JetValidationErrors />
        <form @submit.prevent="submit" autocomplete="off" class="form d-flex flex-column flex-lg-row">
            <!--begin::Aside column-->
            <div class="d-flex flex-column gap-5 w-100 w-lg-300px mb-7 me-lg-5 col-sm-12">
                <!--begin::Status-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="fv-row mb-2">
                            <Multiselect :can-clear="false" :options="status.data" label="label" valueProp="value"
                                class="form-control form-control-solid" placeholder="Select status" :class="v$.form.project_status.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " v-model="v$.form.project_status.$model" />
                            <div v-for="(error, index) of v$.form.project_status
                                .$errors" :key="index">
                                <input-error :message="error.$message" />
                            </div>
                        </div>
                        <div class="text-muted fs-7">Set the project status.</div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Status-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Device Type</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="fv-row mb-2">

                            <Multiselect :canClear="false" id="device-type" :options="devices" label="label"
                                valueProp="value" class="form-control form-control-solid" placeholder="Select device"
                                mode="tags" :close-on-select="false" :create-option="true" :class="v$.form.device_type.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " v-model="v$.form.device_type.$model" />
                            <div v-for="(error, index) of v$.form.device_type
                                .$errors" :key="index">
                                <input-error :message="error.$message" />
                            </div>
                        </div>
                        <div class="text-muted fs-7">
                            Set the project device type.
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>

                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Project Link Type</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="fv-row mb-2">
                            <Multiselect :can-clear="false" :options="[
                                {
                                    label: 'Single Link',
                                    value: 'single',
                                },
                                {
                                    label: 'Multi Link',
                                    value: 'multi',
                                },
                            ]" label="label" valueProp="value" class="form-control form-control-solid"
                                placeholder="Select status" :class="v$.form.project_type.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    " v-model="v$.form.project_type.$model" />
                            <div v-for="(error, index) of v$.form.project_type
                                .$errors" :key="index">
                                <input-error :message="error.$message" />
                            </div>
                        </div>
                        <div class="text-muted fs-7">
                            Set the project link type.
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Aside column-->

            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>General</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="row mb-3">
                            <div class="col-6">
                                <!--begin::Input group-->
                                <div class="fv-row mb-3">
                                    <jet-label for="project-name" value="Project Name" />
                                    <jet-input id="project-name" type="text" placeholder="Enter project Name / ID"
                                        v-model="v$.form.project_name.$model" :class="v$.form.project_name.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                    <div v-for="(error, index) of v$.form.project_name
                                        .$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Input group-->
                            <div class="col-6">
                                <div class="fv-row">
                                    <jet-label for="project-client" value="Project Client" />
                                    <Multiselect :can-clear="false" id="project-client" :options="clients.data"
                                        label="display_name" valueProp="id" class="form-control form-control-solid"
                                        placeholder="Select client" :searchable="true" :class="v$.form.client.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " v-model="form.client" />
                                    <div v-for="(error, index) of v$.form.client
                                        .$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12">
                                <jet-label for="project-start" value="Project Start Date" />
                                <VueDatePicker v-model="v$.form.start_date.$model" :enable-time-picker="false"
                                    :clearable="false" auto-apply
                                    input-class-name="form-control form-control-lg form-control-solid fw-normal" :class="v$.form.start_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Project Start Date"></VueDatePicker>
                                <div v-for="(error, index) of v$.form.start_date.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <jet-label for="project-end" value="Project End Date" />
                                <VueDatePicker v-model="v$.form.end_date.$model" :enable-time-picker="false"
                                    :clearable="false" auto-apply
                                    input-class-name="form-control form-control-lg form-control-solid fw-normal" :class="v$.form.end_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Project end date"></VueDatePicker>

                                <div v-for="(error, index) of v$.form.end_date
                                    .$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="fv-row">
                                    <jet-label for="project-target" value="Project Target" />
                                    <textarea rows="5" class="form-control form-control-solid" v-model="form.target"
                                        id="project-target" placeholder="Type important message here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::Automation-->
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->

                    <Link :href="`/projects`" class="btn btn-light me-5">
                    Discard
                    </Link>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': processing }">
                        <div v-if="processing == true" class="spinner-border spinner-border-sm">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Save Changes
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <!--end::Main column-->
        </form>
    </app-layout>
</template>
