<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import { toast } from "vue3-toastify";
import { copyText } from "vue3-clipboard";
import utils from "../../../utils";
import CopyLinkButton from "../../../Components/CopyLinkButton.vue";
export default defineComponent({
    setup() {
        const doCopy = (link) => {
            copyText(link, undefined, (error, event) => {
                if (error) {
                    toast.info("Can not copy", {
                        autoClose: 1000,
                    });
                } else {
                    toast.success("Copied", {
                        autoClose: 1000,
                    });
                }
            });
        };

        return { doCopy };
    },
    props: ["projects", "action"],
    data() {
        return {
            isLoading: false,
            isFullPage: true,
        };
    },
    components: {
        Link,
        Loading,
        CopyLinkButton
    },
    methods: {
        async updateStatus(id, e) {
            this.isLoading = true;
            await utils.changeStatus(route("supplier.status"), {
                id: id,
                status: e,
            });
            this.isLoading = false;
        },
        async confirmDelete(index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(
                route("sampling.destroy", this.projects[index].id),
                this.projects,
                index
            );
            this.isLoading = false;
        },
    },
    created() { },
});
</script>
<template>
    <loading :active="isLoading" :can-cancel="true" :is-full-page="isFullPage"></loading>
    <div class="card mb-5" v-for="(project, index) in projects" :key="index">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-1">
                    <Link class="text-gray-800 text-hover-primary fs-6 fw-bold" :href="`/mapping/${project?.id}`">
                    {{ project?.supplier?.supplier_name }} ({{
                        project?.supplier?.display_name
                    }})</Link>
                    <span class="text-muted fw-semibold d-block fs-7"><i class="bi bi-people-fill me-2"></i>{{
                        project?.project_name }} ({{
        project?.country?.iso2
    }})</span>
                </div>
                <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                    <span>${{ project?.cpi }}/-CPI</span>
                </div>
                <div class="flex-1">
                    <CopyLinkButton :link="project?.supplier_link" v-if="$page.props.user.role.role.slug == 'admin'"
                        tooltip="Copy project link" />
                    <CopyLinkButton :link="$page.props.ziggy.url +
                        '/surveyRoute/init/' +
                        project?.id +
                        '/' +
                        $page.props.user.id" v-else />
                </div>
                <div class="flex-1 text-end" v-if="$page.props.user.role.role.slug == 'admin'">
                    <div class="form-switch form-check-solid d-block form-check-custom form-check-success">
                        <input class="form-check-input h-20px w-30px" type="checkbox" @input="
                            updateStatus(
                                project?.supplier?.id,
                                $event.target.checked
                            )
                            " :checked="project?.supplier?.status == 1 ? true : false
        " />
                        <label class="form-check-label"> Status </label>
                    </div>
                </div>
                <!--begin:Action-->
                <div class="flex-1 text-end" v-if="$page.props.user.role.role.slug == 'admin'">
                    <button class="btn btn-icon btn-outline btn-light btn-circle me-5" :id="`dropdown-${project?.id}`"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="text-left dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                        :aria-labelled:by="`dropdown-${project?.id}`">
                        <div class="menu-item px-3">
                            <Link :href="`/sampling/${project?.id}/edit`" class="menu-link"><i
                                class="bi bi-pencil me-2"></i>Edit
                            </Link>
                        </div>
                        <div class="menu-item px-3">
                            <span @click="confirmDelete(index)" class="menu-link"><i
                                    class="bi bi-trash3 me-2"></i>Delete</span>
                        </div>
                    </div>
                </div>
                <!--end:Action-->
            </div>
            <div class="separator separator-dashed my-4"></div>
            <ul class="nav d-flex justify-content-between fw-bold text-center">
                <!--begin::Item-->
                <li class="nav-item row">
                    <span>
                        {{ project?.sample_size }}
                    </span>
                    <span class="text-gray-400">Sample Size</span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.total_clicks }}
                    </span>
                    <span class="text-gray-400">Total Clicks</span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.complete }}
                    </span>
                    <span class="text-gray-400"> Completes</span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.terminate }}
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
                        {{ project?.reports?.security_terminate }}
                    </span>
                    <span class="text-gray-400">Security Terminates</span>
                </li>
                <li class="nav-item row">
                    <span>
                        {{ project?.reports?.incomplete }}
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
