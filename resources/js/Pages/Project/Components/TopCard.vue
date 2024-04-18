<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import Datepicker from "vue3-datepicker";
import { toast } from "vue3-toastify";
import axios from "axios";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

export default defineComponent({
    props: ["clients", "status", "project"],
    setup() {
        return { v$: useVuelidate() };
    },
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        Datepicker,
        Loading,
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
                start_date: {},
                end_date: {},
                project_status: {
                    required,
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
            form: this.$inertia.form({
                project_name: this.project?.project_name,
                client: this.project?.client.id,
                start_date: this.project?.start_date,
                end_date: this.project?.end_date,
                project_status: this.project?.status,
                target: this.project?.target,
                device_type: this.project.device_type
                    ? JSON.parse(this.project?.device_type)
                    : this.device_type,
                project_type: this.project?.project_type,
                action: "project_show",
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
            isEdit: false,
            isLoading: false,
        };
    },
    methods: {
        formSubmit(e) {
            const file = e.currentTarget.files[0];
            this.$data.form.image = file;
            this.selectedFilename = file?.name;
            this.url = URL.createObjectURL(file);
            const config = {
                headers: { "content-type": "multipart/form-data" },
            };
            const formData = new FormData();
            formData.append("file", file);
            // console.log(formData);
            this.isLoading = true;
            axios
                .post(`/project/${this.project.id}/importid`, formData, config)
                .then(function (response) {
                    if (response.data.success) {
                        toast.success(response.data.message);
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(function (error) {
                    toast.error(error.message);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        submit() {
            const formattedStartDate = this.formatDate(this.form.start_date);
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form
                    .transform((data) => ({
                        ...data,
                    }))
                    .post(
                        this.route("project.update", this.project.id, {
                            start_date: formattedStartDate,
                        }),
                        {
                            onSuccess: (data) => {
                                this.isEdit = false;
                                toast.success(
                                    this.$page.props.jetstream.flash.message
                                );
                            },
                            onError: (data) => {
                                // toast.error(data);
                            },
                        }
                    );
            }
        },
        formatDate(dateString) {
            // console.log(dateString);
            // Convert a YYYY-MM-DD format date to 'm/d/Y'
            // const [year, month, day] = dateString.split('/');
            // return `${month}/${day}/${year}`;
        },
    },
    created() { },
});
</script>
<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <div class="card card-flush mb-5">
        <div class="card-header align-items-center">
            <div class="card-title">
                <h2>#{{ project.project_id }} ({{ project.project_name }})</h2>
            </div>
            <div v-if="$page.props.user.role.role.slug != 'user'">
                <button class="btn btn-primary btn-sm" for="importId">
                    <span class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <span>Import ID's</span>
                        <input type="file" @change="formSubmit" id="importId" class="d-none" />
                    </span>
                </button>
                <a target="_blank" :href="`/project/${project.id}/finalids`" v-if="project?.finalids?.length > 0"
                    class="btn btn-danger m-1 btn-sm"><i class="bi bi-graph-down-arrow"></i>Export Final Id's
                </a>
                <a target="_blank" :href="`/project/${project.id}/report`" class="btn btn-primary m-1 btn-sm"><i
                        class="bi bi-graph-down-arrow"></i>Export Report
                </a>
                <button class="btn btn-primary m-1 btn-sm" @click="this.isEdit = this.isEdit ? false : true">
                    <i class="bi bi-pencil"></i>Edit
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row">
                <div class="col-12 " v-if="isEdit">
                    <JetValidationErrors />
                    <form @submit.prevent="submit" autocomplete="off">
                        <div class="row g-5 mx-5">
                            <div class="fv-row col-12">
                                <jet-label for="project-name" value="Project Name" />
                                <jet-input id="project-name" type="text" placeholder="Enter project Name / ID"
                                    v-model="v$.form.project_name.$model" :class="v$.form.project_name.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " />
                                <div v-for="(error, index) of v$.form
                                    .project_name.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6">
                                <jet-label for="project-client" value="Project Client" />
                                <Multiselect :canClear="false" id="project-client" :options="clients" label="display_name"
                                    valueProp="id" class="form-control form-control-solid" placeholder="Select client"
                                    :searchable="true" :class="v$.form.client.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " v-model="form.client" />
                                <div v-for="(error, index) of v$.form.client
                                    .$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6">
                                <jet-label for="link-type" value="Project Link Type" />
                                <Multiselect id="link-type" :canClear="false" :options="[
                                    {
                                        label: 'Single Link',
                                        value: 'single',
                                    },
                                    {
                                        label: 'Multi Link',
                                        value: 'multi',
                                    },
                                ]" label="label" valueProp="value" class="form-control form-control-solid"
                                    placeholder="Select link type" :class="v$.form.project_type.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " v-model="v$.form.project_type.$model" />
                                <div v-for="(error, index) of v$.form
                                    .project_type.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6 fs-5">
                                <jet-label for="project-start" value="Project Start Date" />
                                <input type="date" v-model="v$.form.start_date.$model"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Enter project start date" :class="v$.form.start_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " />

                                <div v-for="(error, index) of v$.form.start_date
                                    .$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6 fs-5">
                                <jet-label for="project-end" value="Project End Date" />
                                <input type="date" v-model="v$.form.end_date.$model"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Enter project start date" :class="v$.form.end_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " />
                                <div v-for="(error, index) of v$.form.end_date
                                    .$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6">
                                <jet-label for="device-type" value="Project Device" />
                                <Multiselect :can-clear="false" id="project-status" :options="devices" label="label"
                                    valueProp="value" class="form-control form-control-solid" placeholder="Select status"
                                    mode="tags" :close-on-select="false" :create-option="true" :class="v$.form.device_type.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " v-model="v$.form.device_type.$model" />

                                <div v-for="(error, index) of v$.form.device_type
                                    .$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-md-6 col-sm-12">
                                <jet-label for="project-status" value="Project Status" />
                                <Multiselect :canClear="false" id="project-status" :options="status" label="label"
                                    valueProp="value" class="form-control form-control-solid" placeholder="Select status"
                                    :class="v$.form.project_status.$errors.length >
                                        0
                                        ? 'is-invalid'
                                        : ''
                                        " v-model="v$.form.project_status.$model" />
                                <div v-for="(error, index) of v$.form
                                    .project_status.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="fv-row col-12">
                                <jet-label for="project-target" value="Project Target" />
                                <textarea rows="5" class="form-control form-control-solid" v-model="form.target"
                                    id="project-target" placeholder="Type important message here..."></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-5">
                                <!--begin::Button-->
                                <button type="button" class="btn btn-secondary " @click="this.isEdit = false">
                                    Discard
                                </button>
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary" :class="{
                                    'text-white-50': form.processing,
                                }">
                                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-7 mx-5" v-else>
                    <table class="table table-bordered text-start">
                        <tbody>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Project ID
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    {{ project.project_id }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Project Name
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    {{ project.project_name }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Status
                                </th>
                                <td class="fs-6 fw-bold text-gray-800 text-capitalize">
                                    {{ project?.status }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Client
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    <Link :href="`/client/${project.client.id}`" v-if="$page.props.user.role.role.slug !=
                                        'user'
                                        ">{{ project.client.display_name }}
                                    </Link>
                                    <span v-else>{{
                                        project.client.display_name
                                    }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Project Type
                                </th>
                                <td class="fs-6 fw-bold text-gray-800 text-capitalize">
                                    {{ project.project_type }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                    ">
                                    Device Type
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    <span class="badge badge-success mx-1 text-capitalize rounded-pill"
                                        v-if="project.device_type" v-for="(type, index) in JSON.parse(
                                            project.device_type
                                        )" :key="index">{{ type }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                        min-width: 150px;
                                    ">
                                    Start Date
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    {{ project.start_date }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                        min-width: 150px;
                                    ">
                                    End Date
                                </th>
                                <td class="fs-6 fw-bold text-gray-800">
                                    {{ project.end_date }}
                                </td>
                            </tr>
                            <tr>
                                <th style="
                                        white-space: nowrap;
                                        min-width: 150px;
                                    ">
                                    Target
                                </th>
                                <td class="fs-6 fw-bold text-gray-800 whitespace-break">
                                    {{ project.target }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ul class="nav nav-stretch nav-line-tabs fs-5 fw-bold px-5">
            <li class="nav-item">
                <Link class="nav-link text-active-primary ms-0 me-10 py-5"
                    :class="route().current() == 'project.show' ? 'active' : ''" :href="`/project/${project.id}`" v-if="$page.props.user.role.role.slug == 'user' ||
                        $page.props.user.role.role.slug == 'pm' ||
                        $page.props.user.role.role.slug == 'admin'
                        ">
                Project Overview</Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link text-active-primary ms-0 me-10 py-5" :class="route().current() == 'project.suppliers' ? 'active' : ''
                    " :href="`/project/${project.id}/suppliers`" v-if="$page.props.user.role.role.slug == 'pm' ||$page.props.user.role.role.slug == 'admin'">
                Suppliers</Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link text-active-primary ms-0 me-10 py-5" :class="route().current() == 'project.activity' ? 'active' : ''
                    " :href="`/project/${project.id}/activity`" v-if="$page.props.user.role.role.slug == 'pm' || $page.props.user.role.role.slug == 'admin'">
                Activity</Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link text-active-primary ms-0 me-10 py-5" :class="route().current() == 'project.question-answer' ? 'active' : ''
                    " :href="`/project/${project.id}/question-answer`" v-if="$page.props.user.role.role.slug == 'pm' ||$page.props.user.role.role.slug == 'admin'">
                Question Answer</Link>
            </li>
        </ul>
    </div>
</template>