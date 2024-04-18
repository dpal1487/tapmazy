<script>

import { defineComponent } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import PermissionForm from '../Components/PermissionForm.vue';
import { Inertia } from '@inertiajs/inertia';
import Pagination from "../../../Jetstream/Pagination.vue";
import utils from "../../../utils";
export default defineComponent({
    props: ['permissions'],
    data() {
        return {
            isEdit: false,
            permission: [],
            id: '',
            showModal: false,
            title: 'Permissions',
            form: {},
            tbody: [
                "Name",
                "ASSIGNED TO",
                "CREATED DATE",
                "Action",
            ],
        }
    },
    components: {
        AppLayout,
        Head,
        Link,
        PermissionForm,
        Pagination
    },
    methods: {
        toggleModal(value, id) {
            if (value, id) {
                this.isEdit = true;
                this.showModal = true;
                this.id = id;
            }
            else if (value) {
                this.showModal = true;
                this.isEdit = false;

            }
            else {
                this.showModal = false;
            }
        },
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('permission.destroy', id), this.permissions.data, index);
            this.isLoading = false;
        },

        search() {
            Inertia.get(
                "/permissions",
                this.form,
                {
                    preserveState: true,
                }
            );
        },
    }
})
</script>

<template>
    <Head :title=title />
    <AppLayout :title="title">
        <PermissionForm v-if="showModal" :show="showModal" :isEdit="isEdit" @hidemodal="toggleModal(false)"
            :permission="permission?.data" :id="id" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted">Permission</span>
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm btn-light-primary" @click="toggleModal(true)">
                    <span class="svg-icon svg-icon-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </span>
                    Add Permission</button>
            </div>
        </template>
        <div class="card card-flush">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-gray-400 fw-bold fs-7 text-uppercase ">
                                <th v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <tr v-for="(permission, index) in permissions.data" :key="index">
                                <td>{{ permission.name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <div v-for="(role, index) in permission?.roles" :key="index">
                                            <Link :href="`account/${role.id}`" class="badge badge-light-primary fs-7 m-1">
                                            <!-- {{ permission.roles }} -->
                                            {{ role.name }}
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ permission.created_at }}</td>
                                <td class="">
                                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"
                                        @click="toggleModal(true, permission.id)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                        @click="confirmDelete(permission.id, index)">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" v-if="permissions.data.length > 0">
                    <Pagination :links="permissions" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
