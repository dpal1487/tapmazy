<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import utils from "../../../utils";
import CopyLinkButton from "../../../Components/CopyLinkButton.vue";

export default defineComponent({

    props: ["project", "index"],
    emits: ['onSupplier', 'onDelete', 'editProjectLink'],
    data() {
        return {
            isLoading: false,
            isModalOpen: false,
            activeId: null,
            isReadonly: true,
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
            await utils.changeStatus(route("mapping.status"), {
                id: id,
                status: e,
            });
            this.isLoading = false;
        },


        changeSampleSize() {
            this.isReadonly = false;
        },
        handelBlur() {
            this.isReadonly = true;
        },
        async handelKeyDown(id, e, project_id) {
            if (e.key === 'Enter') {
                this.project.sample_size = e.target.value;
                await utils.changeSampleSizeValue(route("mapping.sample-size"), {
                    id: id,
                    sample_size: e.target.value,
                    project_id: project_id
                });
                this.isReadonly = true;
            }
        }
    },
    created() { },
});
</script>
<template>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-1">
                <Link class="text-gray-800 text-hover-primary fs-6 fw-bold" :href="`/mapping/${project.id}`"
                    v-if="$page.props.user.role.role.slug == 'admin'">{{ project.project_name }}</Link>
                <div class="text-gray-800 fs-6 fw-bold" v-else>
                    {{ project.project_name }}
                </div>
                <span class="text-muted fw-semibold d-block fs-7"><i class="bi bi-geo-alt-fill me-2"></i>{{
                    project.country?.display_name }}</span>
            </div>
            <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                <span>${{ project.cpi }}/-CPI</span>
            </div>
            <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                <span>{{ project.loi }}Min/LOI</span>
            </div>
            <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                <span>{{ project.ir }}%/IR</span>
            </div>

            <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                <span v-if="project?.user"><i class="bi bi-people me-2"></i>{{ project?.user }}</span>
                <span v-else><i class="bi bi-people me-2"></i>Admin</span>
            </div>
            <div class="flex-1 fw-bold" v-if="$page.props.user.role.role.slug == 'admin'">
                <button type="button" class="btn btn-link" @click="$emit('onSupplier', project.id)">Suppliers
                    ({{ project.supplier_count }})</button>
            </div>
            <div class="flex-1">
                <CopyLinkButton :link="project.project_link" v-if="$page.props.user.role.role.slug == 'admin'"
                    tooltip="Copy project link" />
                <CopyLinkButton :link="$page.props.ziggy.url +
                    '/survey/init/' +
                    project.id +
                    '/' +
                    $page.props.user.id" v-else />
            </div>
            <div class="flex-1 text-end">
                <div class="form-switch form-check-solid d-block form-check-custom form-check-success"
                    v-if="$page.props.user.role.role.slug == 'admin'">
                    <input class="form-check-input h-20px w-30px" type="checkbox" @input="
                        updateStatus(project.id, $event.target.checked)
                        " :checked="project.status == 1 ? true : false" />
                    <label class="form-check-label"> Status </label>
                </div>
                <div v-else>
                    <span class="badge badge-success" v-if="project.status">Active</span>
                    <span class="badge badge-danger" v-else>Inactive</span>
                </div>
            </div>
            <!--begin:Action-->
            <div class="flex-1 text-end" v-if="$page.props.user.role.role.slug == 'admin'">
                <button class="btn btn-icon btn-outline btn-light btn-circle me-5" :id="`dropdown-${project.id}`"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="text-left dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                    :aria-labelled:by="`dropdown-${project.id}`">
                    <div class="menu-item px-3">

                        <!-- edit modal  -->
                        <span type="button" v-if="$page.props.user.role.role.slug == 'admin'" class="menu-link"
                            @click="$emit('editProjectLink', { id: project?.id, pageName: 'editPage' })"><i
                                class="bi bi-pencil me-2"></i>Edit
                        </span>

                        <!-- end edit modal -->
                    </div>
                    <div class="menu-item px-3">
                        <Link title="Add Supplier" :href="`/sampling/${project.id}/create`" class="menu-link"><i
                            class="bi bi-plus-circle me-2"></i>Supplier
                        </Link>
                    </div>
                    <div class="menu-item px-3">
                        <span @click="$emit('onDelete', index)" class="menu-link"><i
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
                <input type="text"
                    :class="`form-control w-100px mx-auto p-0 text-gray-900 text-center fw-bold ${isReadonly && 'border-0'}`"
                    @dblclick="changeSampleSize()" :readonly="isReadonly" :value="project.sample_size"
                    @keydown="(e) => handelKeyDown(project.id, e, project?.project_id, index)" @blur="handelBlur()"
                    title="Double click to edit" />
                <span class="text-gray-400">Sample Size</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item row">
                <span>
                    {{ project.reports.total_clicks }}
                </span>
                <span class="text-gray-400">Total Clicks</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item row">
                <span>
                    {{ project.reports.complete }}
                </span>
                <span class="text-gray-400">Completes</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item row">
                <span>
                    {{ project.reports.terminate }}
                </span>
                <span class="text-gray-400">Terminates</span>
            </li>
            <li class="nav-item row">
                <span>
                    {{ project.reports.quotafull }}
                </span>
                <span class="text-gray-400">Quotafull</span>
            </li>
            <li class="nav-item row">
                <span>
                    {{ project.reports.security_terminate }}
                </span>
                <span class="text-gray-400">Security Terminates</span>
            </li>
            <li class="nav-item row">
                <span>
                    {{ project.reports.incomplete }}
                </span>
                <span class="text-gray-400">Incompletes</span>
            </li>
            <li class="nav-item row">
                <span>
                    {{ project.reports.total_ir }}
                </span>
                <span class="text-gray-400">Incidence Ratio</span>
            </li>
        </ul>
    </div>
</template>
