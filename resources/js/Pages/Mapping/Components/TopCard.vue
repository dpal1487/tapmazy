<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import MappingForm from "./MappingForm.vue";
import { toast } from "vue3-toastify";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: ["countries", "project", "states", "cities"],
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        Loading,
        MappingForm,
    },
    validations() {
        return {};
    },
    data() {
        return {
            isLoading: false,
            isEdit: false,
            form: {}
        };
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form.processing = true;
            axios.post(this.route("mapping.update", this.project.id), this.form)
                .then((response) => {
                    if (response.data.success) {
                        toast.success(response.data.message)
                    }
                    else {
                        toast.error(response.data.message)
                    }
                }).finally(() => {
                    this.isEdit = false;
                    if (this.route().current() == "mapping.suppliers") {
                        Inertia.get('/mapping/' + this.project.id + '/suppliers')
                    }
                    if (this.route().current() == "mapping.show") {
                        Inertia.get('/mapping/' + this.project.id)
                    }
                    this.form.processing = false;
                });
        },
    },
    created() { },
});
</script>
<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <div class="mb-5">
        <div class="card">
            <div class="card-header align-items-center my-5">
                <div class="card-title">
                    <h2>#{{ project.project_uid }} ({{ project.project_name }})</h2>
                </div>
                <div class="flex-shrink-0 d-flex">
                    <button v-if="!isEdit" @click="isEdit = true" class="btn btn-sm btn-primary me-2">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </button>
                    <Link :href="`/sampling/${project.id}/create`" class="btn btn-sm btn-primary"><i
                        class="bi bi-plus-circle me-1"></i>Add Supplier
                    </Link>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="row p-5" v-if="isEdit">
                    <mapping-form @submitted="submit" :project="project" :countries="countries" :states="states"
                        :cities="cities">
                        <template #action>
                            <div class="d-flex align-items-center justify-content-end">
                                <button @click="isEdit = false" type="button" class="btn btn-secondary me-5">
                                    Discard
                                </button>
                                <button type="submit" class="btn btn-primary" :class="{
                                    'text-white-50':
                                        form.processing,
                                }">
                                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Save Change
                                </button>
                            </div>
                        </template>
                    </mapping-form>
                </div>
                <div class="row" v-else>
                    <div class="col-md-8">
                        <div class="d-flex flex-row px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Project ID</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">{{
                                project.project_uid }}</div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Project Name</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">{{
                                project.project_name }}</div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Project Link</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">{{
                                project.project_link }}</div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Project Country</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">
                                <Link :href="`/client/${project.client.id}`">{{ project.country.display_name }}</Link>
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Client Name</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">
                                <Link :href="`/client/${project.client.id}`">{{ project.client.client_name }}</Link>
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="flex-shrink-0" style="width: 200px;">Sample Size</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800 mw-100" style="width: 120px;">
                                {{ project.sample_size }}N
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="" style="width: 200px;">Project CPI</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800">
                                ${{ project.cpi }}
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="" style="width: 200px;">Project LOI</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800">
                                {{ project.loi }} Min
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="" style="width: 200px;">Incidence Ratio</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800">
                                {{ project.ir }}%
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="" style="width: 200px;">Project Status</div>
                            <div class="flex-root fs-6 fw-bold text-gray-800">
                                <span class="badge badge-success" v-if="project.status">Active</span>
                                <span class="badge badge-danger" v-else>Inactive</span>
                            </div>
                        </div>
                        <div class="d-flex flex-row  px-5 my-5">
                            <div class="" style="width: 200px;">Target </div>
                            <div class="flex-root fs-6 fw-bold text-gray-800">
                                {{ project.target }}

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="h-100 d-flex justify-content-center align-items-center bg-gray-100">
                            <div class="text-center">
                                <div class="badge badge-success" v-if="project.status">
                                    Active
                                </div>
                                <div class="badge badge-danger" v-else>
                                    Inactive
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <h1>
                                        {{ project.reports.complete }}&nbsp;
                                    </h1>
                                    <span>Out Of</span>
                                    <h1>&nbsp;{{ project.sample_size }}</h1>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span>Actula IR&nbsp;</span>
                                    <h1>{{ project.reports.total_ir }}%</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold px-5">
                    <li class="nav-item">
                        <Link class="nav-link text-active-primary ms-0 me-10 py-5" :href="`/mapping/${project.id}`" :class="route().current() == `mapping.show`
                            ? 'active'
                            : ''
                            ">
                        Link Overview
                        </Link>
                        <Link class="nav-link text-active-primary ms-0 me-10 py-5"
                            :href="`/mapping/${project.id}/suppliers`" :class="route().current() == `mapping.suppliers`
                                ? 'active'
                                : ''
                                ">
                        Suppliers ({{ project.supplier_count }})
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
