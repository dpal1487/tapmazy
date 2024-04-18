<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Header from "./Components//Header.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "../../Jetstream/Pagination.vue";
import ProjectList from "../Project/Components/ProjectList.vue";
import Multiselect from "@vueform/multiselect";
import { Inertia } from "@inertiajs/inertia";
import NoRecordMessage from "../../Components/NoRecordMessage.vue";
export default defineComponent({
    props: ["surveys", "user", 'header'],
    data() {
        return {
            title: "User",
            form: {},
            tbody: [
                "S.No",
                "Project Name",
                "USERNAME",
                "STARTING IP",
                "END IP",
                "DURATION",
                "DATE/TIME",
                "BROWSER",
                "Device",
                "STATUS",
            ],
            status: [
                { value: "all", name: "All" },
                { value: "complete", name: "Completed" },
                { value: "terminate", name: "Terminated" },
                { value: "quotafull", name: "Quotafull" },
                { value: "security-terminate", name: "Security Terminate" }
            ],
        };
    },
    components: {
        Link,
        Head,
        Header,
        AppLayout,
        Pagination,
        ProjectList,
        Multiselect,
        NoRecordMessage
    },
    methods: {
        search() {
            Inertia.get(
                "/user/" + this.user.data.id + "/projects",
                this.form,
            );
        },
        $queryParams(...args) {

            let queryString = this.$page.url;

            if (queryString.indexOf("?") === -1) {
                return {};
            }

            queryString = queryString.substring(queryString.indexOf("?") + 1);
            return Object.assign(Object.fromEntries(new URLSearchParams(queryString)), ...args);
        },

    },
    created() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        this.form.status = urlParams.get('status')
        this.form.q = urlParams.get('q')
    }
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/users" class="text-muted text-hover-primary">Users </Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }} Projects
            </li>
        </template>
        <Header :user="user.data" :header="header" />
        <div class="card mb-5">
            <div class="card-header align-items-center gap-5">
                <div class="card-title">
                    <h2>Projects</h2>
                </div>
                <form class="card-toolbar flex-row-fluid justify-content-end gap-3" @submit.prevent="search()">
                    <div class="d-flex align-items-center position-relative">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span>
                        <input type="text" v-model="form.q" class="form-control form-control-solid w-200px  ps-12"
                            placeholder="Search" />
                    </div>

                    <div class="w-200px">
                        <Multiselect :options="status" :can-clear="false" label="name" valueProp="value" :searchable="true"
                            class="form-control form-control-solid" placeholder="Select status" v-model="form.status" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>

                    <a target="_blank" :href="route('user-project.report', { ...$queryParams(), user_id: user.data.id })"
                        class="btn btn-primary  m-1"><i class="bi bi-graph-down-arrow"></i>Export
                        Report</a>
                </form>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 text-center">
                        <thead>
                            <tr class="text-gray-700 fw-bold fs-7 w-100 text-uppercase">

                                <th class="min-w-120px" v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500" v-if="surveys?.data?.length > 0">
                            <tr v-for="(survey, index) in surveys.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ survey.project_id }}</td>
                                <td>{{ survey.user }}</td>
                                <td>{{ survey.starting_ip }}</td>
                                <td>{{ survey.end_ip }}</td>
                                <td>{{ survey.duration }}</td>
                                <td>{{ survey.created_at }}</td>
                                <td>{{ survey.client_browser }}</td>
                                <td>{{ survey.device }}</td>
                                <td>
                                    <div v-if="(survey.status == 'terminate')" class="border-pill badge badge-light-danger">
                                        Terminate</div>
                                    <div v-else-if="(survey.status == 'complete')"
                                        class="border-pill badge badge-light-success">Complete
                                    </div>
                                    <div v-else-if="(survey.status == 'quotafull')"
                                        class="border-pill badge badge-light-info">Quotafull</div>
                                    <div v-else-if="(survey.status == 'null')" class="border-pill badge badge-light-light">
                                        Incomplete</div>
                                    <div v-else class="border-pill badge badge-light-danger">Security Terminate
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="9" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row my-5" v-if="surveys.data.length > 0">
                    <div class="col-sm-12 d-flex align-items-center justify-content-between" v-if="surveys.meta">
                        <span class="fw-bold text-gray-700">
                            Showing {{ surveys.meta.from }} to {{ surveys.meta.to }}
                            of {{ surveys.meta.total }} entries
                        </span>
                        <Pagination :links="surveys.meta.links" />
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
