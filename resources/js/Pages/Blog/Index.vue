<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { toast } from "vue3-toastify";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import axios from "axios";
import utils from "../../utils";
import NoRecordMessage from "../../Components/NoRecordMessage.vue";
import SearchBar from "../../Components/SearchBar.vue";
export default defineComponent({
    props: ["blogs", 'message'],
    data() {
        return {
            form: {},
            isFullPage: true,
            isLoading: false,
            title: "Blogs",
            tbody: [
                "Image",
                "Name",
                "Content",
                "Status",
                "Action",
            ],
            checkbox: [],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Pagination,
        Multiselect,
        Loading,
        NoRecordMessage,
        SearchBar
    },
    methods: {

        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('blog.destroy', id), this.blogs.data, index);
            this.isLoading = false;
        },
        search(form) {
            this.form = form;
            Inertia.get("/blogs", this.form);
        },
        changeStatus(e, id) {
            this.isLoading = true;
            axios
                .post("/blog/status", { id: id, status: e })
                .then((response) => {
                    if (response.data.success) {
                        toast.success(response.data.message);
                        return;
                    }
                    toast.error(response.data.message);
                })
                .finally(() => (this.isLoading = false));
        },
    },
});
</script>
<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted text-hover-primary">{{ title }}</span>
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link href="/blog/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add New Blog</Link>
            </div>
        </template>

        <Head :title="title" />
        <div class="card">
            <div>
                <SearchBar @search="search" :status="$page.props.ziggy.status" />
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 text-left">
                        <thead>
                            <tr class="text-gray-700 fw-bold fs-7 text-uppercase">
                                <th v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500 " v-if="blogs.data.length > 0">
                            <tr v-for="(blog, index) in blogs.data" :key="index">
                                <td v-if="blog.image">
                                    <Link :href="`/blog/${blog.id}`">
                                    <div class="symbol symbol-50px me-5">
                                        <img alt="Logo" :src="blog.image?.url">
                                    </div>
                                    </Link>
                                </td>
                                <td v-else>
                                    <Link :href="`/blog/${blog.id}`">
                                    <div class="symbol symbol-50px me-5">
                                        <img alt="Logo" src="/assets/images/comingsoon.png">
                                    </div>
                                    </Link>
                                </td>
                                <td>{{ blog.title }}</td>
                                <td v-html="blog?.content"></td>
                                <td>
                                    <div class="form-switch form-check-solid d-block form-check-custom form-check-success">
                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                            @input="changeStatus($event.target.checked, blog.id)"
                                            :checked="blog.is_published == 1 ? true : false" />
                                    </div>
                                </td>
                                <td class="w-150px">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                            :id="`dropdown-${blog.id}`" data-bs-toggle="dropdown"
                                            aria-expanded="false">Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu text-small menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            :aria-labelled:by="`dropdown-${blog.id}`">
                                            <li class="menu-item px-3">
                                                <Link
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center"
                                                    :href="`/blog/${blog.id}/edit`">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                                </Link>
                                            </li>
                                            <li class="menu-item px-3">
                                                <button @click="confirmDelete(blog.id, index)"
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center">
                                                    <i class="bi bi-trash3 me-2"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="5" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" v-if="blogs.data.length > 0">
                <Pagination :links="blogs" />
            </div>
        </div>
    </app-layout>
</template>
