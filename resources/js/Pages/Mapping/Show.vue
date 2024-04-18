<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import SupplierList from "../Sampling/Components/SupplierList.vue";
import TopCard from "./Components/TopCard.vue";
import Pagination from "../../Jetstream/Pagination.vue";
export default defineComponent({
    props: ["project", "respondents", "countries", "states", "cities"],

    data() {
        return {
            title: "Link Overview",
            form: {},
            tbody: [
                "S.No",
                "RESPONDENT ID",
                "PROJECT ID",
                "UERNAME",
                "SUPPLIER NAME",
                "STARTING IP",
                "END IP",
                "DURATION",
                "DATE/TIME",
                "BROWSER",
                "STATUS",
            ],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        SupplierList,
        TopCard,
        Pagination,
    },
    methods: {},
    created() { },
});
</script>
<template>
    <app-layout :title="title">

        <Head :title="title" />
        <TopCard :project="project.data" :countries="countries.data" :states="states.data" :cities="cities.data" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link class="text-muted text-hover-primary" href="/projects">Projects</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link class="text-muted text-hover-primary" :href="`/project/${project.data.project_id}`">Project Overview
                </Link>
            </li>
        </template>
        <div class="mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="respondents.data.length > 0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 text-center">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 w-100 text-uppercase">
                                    <th class="min-w-120px" v-for="(th, index) in tbody" :key="index">
                                        {{ th }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <tr v-for="(
                                        respondent, index
                                    ) in respondents.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ respondent.id }}</td>
                                    <td>{{ respondent.project_id }}</td>
                                    <td>{{ respondent.user }}</td>
                                    <td>{{ respondent.supplier_name }}</td>
                                    <td>{{ respondent.starting_ip }}</td>
                                    <td>{{ respondent.end_ip }}</td>
                                    <td>{{ respondent.duration }}</td>
                                    <td>{{ respondent.created_at }}</td>
                                    <td>{{ respondent.client_browser }}</td>
                                    <td>
                                        <div v-if="respondent.status == 'terminate'
                                            " class="badge badge-danger">
                                            Terminate
                                        </div>
                                        <div v-else-if="respondent.status == 'complete'
                                            " class="badge badge-success">
                                            Complete
                                        </div>
                                        <div v-else-if="respondent.status == 'quotafull'
                                            " class="badge badge-info">
                                            Quotafull
                                        </div>
                                        <div v-else-if="respondent.status == 'null'
                                            " class="badge badge-light">
                                            Incomplete
                                        </div>
                                        <div v-else class="badge badge-danger">
                                            Security Terminate
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center align-content-center pt-10 pb-10" v-else>
                        <div class="text-center py-10">
                            <img src="/assets/images/emptyrespondent.png" style="height: 200px" />
                            <div class="fw-bold fs-2 text-gray-900 mt-5">
                                No Data Found!
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="respondents.data.length > 0">
                        <div class="col-sm-12 d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-gray-700">
                                Showing {{ respondents.meta.from }} to
                                {{ respondents.meta.to }} of
                                {{ respondents.meta.total }} entries
                            </span>
                            <Pagination :links="respondents.meta.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
