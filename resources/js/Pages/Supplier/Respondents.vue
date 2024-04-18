
<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Pagination from "../../Jetstream/Pagination.vue";

import { Inertia } from "@inertiajs/inertia";


export default defineComponent({
    props: ['supplier', 'respondents'],

    data() {
        return {
            form: {},
            title: "Respondents",
            tbody: [
                "Project Name",
                "RESPONDENT ID",
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
                { value: "complete", label: "Completed" },
                { value: "terminate", label: "Terminated" },
                { value: "quotafull", label: "Quotafull" },
            ],
        };
    },
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        Multiselect,
        Pagination
    },
    methods: {
        search() {
            Inertia.get(
                `/supplier/${this.supplier?.data?.id}/respondents`, this.form,
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
        <div class="card mb-5">
            <div>
                <form class="card-header align-items-center" @submit.prevent="search()">
                    <div class="card-title">
                        <h2>{{ title }}</h2>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                        transform="rotate(45 17.0365 15.1223)" fill="currentColor">
                                    </rect>
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <input type="text" v-model="form.q" class="form-control form-control-sm w-180px ps-14"
                                placeholder="Search " />
                        </div>
                        <div class="w-150px">
                            <Multiselect :options="status" label="label" valueProp="value" v-model="form.status"
                                :canClear="false" class="multiselect__content" placeholder="Select One" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Search
                        </button>
                        <a target="_blank" :href="route('supplier.export', supplier?.data?.id, { ...$queryParams() })"
                            class="btn btn-primary btn-sm"><i class="bi bi-graph-down-arrow"></i>Export Report
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 text-center">
                        <thead>
                            <tr class="text-gray-700 fw-bold fs-7 w-100 text-uppercase">

                                <th class="min-w-120px" v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500" v-if="respondents.data.length > 0">
                            <tr v-for="(respondent, index) in respondents.data" :key="index">
                                <td>{{ respondent?.project_name }} </td>
                                <td>{{ respondent.id }}</td>
                                <td>{{ respondent.user }}</td>
                                <td>{{ respondent.starting_ip }}</td>
                                <td>{{ respondent.end_ip }}</td>
                                <td>{{ respondent.duration }}</td>
                                <td>{{ respondent.created_at }}</td>
                                <td>{{ respondent.client_browser }}</td>
                                <td>{{ respondent?.device }}</td>
                                <td>
                                    <div v-if="(respondent.status == 'terminate')" class="badge badge-danger">
                                        Terminate
                                    </div>
                                    <div v-else-if="(respondent.status == 'complete')" class="badge badge-success">
                                        Complete
                                    </div>
                                    <div v-else-if="(respondent.status == 'quotafull')" class="badge badge-info">
                                        Quotafull
                                    </div>
                                    <div v-else-if="(respondent.status == 'security-terminate')" class="badge badge-danger">
                                        Security Terminate</div>
                                    <div v-else class="badge badge-light">Incomplete</div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="10" class="text-center h-full">
                                    <div class="text-center py-10">
                                        <img src="/assets/images/emptyrespondent.png" style="height: 150px" />
                                        <div class="fw-bold fs-2 text-gray-900 mt-5">
                                            No Respondent Found!
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <Pagination :links="respondents" />
            </div>
        </div>
    </AppLayout>
</template>
