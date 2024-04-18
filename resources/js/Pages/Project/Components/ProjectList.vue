<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';
import utils from "../../../utils";

export default defineComponent({
    props: ["projects", "status", 'action'],
    data() {
        return {
            isLoading: false,
            isFullPage: true
        };
    },
    components: {
        Link,
        Head,
        Multiselect,
        Loading,
    },
    methods: {
        async updateStatus(id, e) {
            this.isLoading = true;
            await utils.changeStatus(route('project.status'), { id: id, status: e, page: "project-index", });
            this.isLoading = false;
        },
        async confirmDelete(index) {
            await utils.deleteIndexDialog(route('project.destroy', this.projects[index].id), this.projects, index);
        },
        async cloneProject(id) {
            await utils.cloneProject(route('project.clone'), { id: id });
        }
    },
    created() { },
});
</script>
<template>
    <loading :active="isLoading" :can-cancel="true" :is-full-page="isFullPage"></loading>
    <div class="card mt-5 mb-5 p-0" v-for="(project, index) in projects" :key="index">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="col-2">
                    <Link class="text-gray-800 text-hover-primary text-capitalize fs-6 fw-bold"
                        :href="`/project/${project?.id}`">{{
                            project?.project_name }}</Link>
                    <span class="text-muted fw-semibold d-block fs-7">{{
                        project?.project_id }}</span>
                </div>
                <div class="col-2 row text-center">
                    <span class="text-gray-800 fs-6 fw-bold">Link Type</span>
                    <span class="text-muted">
                        {{ project?.project_type }}
                    </span>
                </div>
                <div class="col-2 row text-center">
                    <span class="text-gray-800 fs-6 fw-bold">Added</span>
                    <span class="text-muted">
                        {{ project?.created_at }}
                    </span>
                </div>
                <div class="col-2 row text-center">
                    <span class="text-gray-800 fs-6 fw-bold">Added By</span>
                    <span class="text-muted">
                        {{ project?.user }}
                    </span>
                </div>
                <div class="col-2 fw-bold" v-if="$page.props?.user?.role?.role?.slug == 'admin'">
                    <Link :href="`client/${project.client?.id}`" :title="project?.client?.name">
                    <span><i class="bi bi-people me-2"></i>{{ project?.client?.display_name }}</span>
                    </Link>
                </div>
                <div class="col-2 fw-bold" v-else>
                    <span :title="project?.client?.name">
                        <span><i class="bi bi-people me-2"></i>{{ project?.client?.display_name }}</span>
                    </span>
                </div>
                <div class="col-2">
                    <div class="badge badge-success text-capitalize" v-if="$page.props?.user?.role?.role.slug == 'user'">
                        {{ project?.status }}
                    </div>
                    <Multiselect v-else :canClear="false" :value="project.status" :options="status"
                        class="btn btn-sm btn-light px-0 py-0 mw-200px" label="label" valueProp="value" @input="
                            updateStatus(project.id, $event)
                            " placeholder="Select Status" />
                </div>
                <div class="flex-1 text-end mx-5" v-if="$page.props?.user?.role?.role?.slug =='admin'">
                    <button class="btn btn-icon btn-outline btn-light btn-circle me-5" :id="`dropdown-${project.id}`"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="text-left dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                        :aria-labelled:by="`dropdown-${project.id}`">
                        <div class="menu-item px-3">
                            <Link :href="`/project/${project.id}/edit`" class="menu-link">
                            <span><i class="bi bi-pencil me-2"></i>Edit</span>
                            </Link>
                        </div>
                        <div class="menu-item px-3">
                            <span @click="cloneProject(project.id)" class="menu-link"><i
                                    class="bi bi-file-plus fs-5 me-2"></i>Clone</span>
                        </div>
                        <div class="menu-item px-3">
                            <span @click="confirmDelete(index)" class="menu-link"><i
                                    class="bi bi-trash3 me-2"></i>Delete</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-dashed my-4"></div>
            <ul class="nav d-flex justify-content-between fw-bold text-center">
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.total_clicks }}
                    </span>
                    <span class="text-gray-400">Total Clicks</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.completes }}
                    </span>
                    <span class="text-gray-400"> Completes</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.terminates }}
                    </span>
                    <span class="text-gray-400">Terminates</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.quotafull }}
                    </span>
                    <span class="text-gray-400">Quotafull</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.security_terminates }}
                    </span>
                    <span class="text-gray-400">Security Terminates</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.incompletes }}
                    </span>
                    <span class="text-gray-400">Incompletes</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.total_ir }}
                    </span>
                    <span class="text-gray-400">Incidence Ratio</span>
                </li>
            </ul>
        </div>
    </div>
</template>
